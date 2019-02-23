using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Microsoft.Office.Interop.Excel;

namespace StudentReports
{
    /// <summary>
    /// Reads, Maintains and Writes the graph of pre-requisites.
    /// </summary>
    class PreRequisites
    {
        private const int ARRAY_COLS = 10;

        //  course -> [ next courses ] and course -> pre-req text
        private readonly SortedList<string, string> preReqText = new SortedList<string, string>();
        private readonly Dictionary<string, List<string>> leadsTo = new Dictionary<string, List<string>>();

        private List<string> GetNextList(string course)
        {
            if (!leadsTo.ContainsKey(course)) leadsTo.Add(course, new List<string>());
            return leadsTo[course];
        }

        public void ParseSubjectAssessmentOutline(Worksheet sheet)
        {
            string courseCode = sheet.Get("courseCodeRange");
            preReqText[courseCode] = sheet.Get("preReqTextRange");

            foreach (string cell in sheet.Range["leadsToRange"].Cells.Cast<Range>()
                             .Select(preReqCell => (string) preReqCell.Value2)
                             .Where(cell => !string.IsNullOrWhiteSpace(cell)))
            {
                GetNextList(cell).Add(courseCode);
            }
                
        }
        
        public void WriteToSheet(Worksheet sheet)
        {
            int rows = preReqText.Count + 1;
            const int cols = ARRAY_COLS;
            var array = new object[rows, cols];

            array[0, 0] = preReqText.Keys.Aggregate(1, (curRow, course) => WriteCourseRow(array, course, curRow));
            XLApp.WriteArrayToSheet(sheet, array, rows, cols);
        }

        // returns next free row
        private int WriteCourseRow(object[,] array, string course, int row)
        {
            array[row, 0] = course;
            array[row, 1] = GetNextList(course).Aggregate(2, (col,next) => WriteNextCourseCell(array, row, col, next)) - 2;
            return row + 1;
        }

        // returns next free col
        private int WriteNextCourseCell(object[,] array, int row, int col, string nextCourse)
        {
            array[row, col] = nextCourse;
            if (preReqText.ContainsKey(nextCourse))
                array[row, col+1] = preReqText[nextCourse];
            return col + 2;
        }
    }
}

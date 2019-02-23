using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Mufasa.BackEnd.Designer;

//Copyright (C) 2015 Jakub Bartoszewicz (if not stated otherwise)
namespace Mufasa.BackEnd.Scores
{
    abstract class Score
    {
        /// <summary>
        /// Score copying constructor.
        /// </summary>
        /// <param name="s">Score to copy.</param>
        public Score(Score s)
        {
            this.RawScore = s.RawScore;
            this.NormalizedScore = s.NormalizedScore;
        }

         /// <summary>
        /// Score copying constructor.
        /// </summary>
        public Score()
        {

        }

        /// <summary>
        /// Raw score.
        /// </summary>
        public double RawScore { get; protected set; }

        /// <summary>
        /// Normalized Score.
        /// </summary>
        public double NormalizedScore { get; protected set; }

        /// <summary>
        /// Score label or name
        /// </summary>
        public String Label { get; set; }

        /// <summary>
        /// Score description
        /// </summary>
        public String Description { get; set; }

        /// <summary>
        /// Scoring function.
        /// </summary>
        /// <param name="overlaps">Overlap list.</param>
        abstract public void Rescore(List<Overlap> overlaps);

        /// <summary>
        /// Prints the overlap in the CSV format.
        /// </summary>
        /// <returns>CSV String represanting the overlap.</returns>
        public string ToCsv()
        {
            String sep = System.Globalization.CultureInfo.CurrentCulture.TextInfo.ListSeparator;
            String result = this.Label + sep + Math.Round(this.RawScore, 2) + sep + Math.Round(this.NormalizedScore, 2);
            return result;
        }
    }
}

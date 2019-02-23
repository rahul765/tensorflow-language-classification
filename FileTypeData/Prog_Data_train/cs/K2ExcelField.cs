//-----------------------------------------------------------------------
// <copyright file="K2ExcelField.cs" company="KaiTrade LLC">
// Copyright (c) 2013, KaiTrade LLC.
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
// http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.
// </copyright>
// <author>John Unwin</author>
// <website>https://github.com/junwin/K2RTD.git</website>
//-----------------------------------------------------------------------
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace K2RTDRequestHandler
{
    /// <summary>
    /// This class models some field(cell) that makes an RTD call in terms of the
    /// connection to the K2 RTD Hanlder and the Excel ID
    /// </summary>
    public class K2ExcelField
    {
        string m_AccessID;
        int m_ExcelFieldID;

        /// <summary>
        /// Create an Excell field
        /// </summary>
        /// <param name="accessID">access id  of the inbound session</param>
        /// <param name="excelFieldID">excel id of the cell that makes the RTD call</param>
        public K2ExcelField(string accessID, int excelFieldID)
        {
            m_AccessID = accessID;
            m_ExcelFieldID = excelFieldID;
        }

        /// <summary>
        /// Get/Set the accessID - this represents a communication to some workbook
        /// </summary>
        public string AccessID
        { get { return m_AccessID; } set { m_AccessID = value; } }

        /// <summary>
        /// Get/Set the ExcelFieldID - this is an identifier allocated by Excel for
        /// some cell with an RTD call
        /// </summary>
        public int ExcelFieldID
        { get { return m_ExcelFieldID; } set { m_ExcelFieldID = value; } }
    }
}

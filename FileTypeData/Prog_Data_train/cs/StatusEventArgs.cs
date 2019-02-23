//-----------------------------------------------------------------------
// <copyright file="StatusEventArgs.cs" company="KaiTrade LLC">
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

namespace K2RTDServerKit
{
    /// <summary>
    /// Evant arguments used on a status updates
    /// </summary>
    public class StatusEventArgs : EventArgs
    {
        /// <summary>
        /// 
        /// </summary>
        /// <param name="channelStatus">new status of chennel (used for comms)</param>
        /// <param name="text">text description of the new state</param>
        public StatusEventArgs(string channelStatus, string text)
        {
            this.channelStatus = channelStatus;
            this.text = text;
        }

        /// <summary>
        /// new status of chennel (used for comms)
        /// </summary>
        public string channelStatus;

        /// <summary>
        /// text description of the new state
        /// </summary>
        public string text;


    }
    
}

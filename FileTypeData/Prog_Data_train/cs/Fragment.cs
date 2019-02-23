using Bio;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

//Copyright (C) 2014 Jakub Bartoszewicz (if not stated otherwise)
namespace Mufasa.BackEnd.Designer
{
    /// <remarks>
    /// DNA fragment class.
    /// </remarks>
    public class Fragment
    {
        /// <value>
        /// Path to the file or url containing the fragment.
        /// </value>
        public String Source { get; set; }
        /// <summary>
        /// Name of the fragment.
        /// </summary>
        public String Name { get; set; }
        /// <summary>
        /// Fragment sequence.
        /// </summary>
        public ISequence Sequence { get; set; }
        /// <summary>
        /// Concentration.
        /// </summary>
        public double Concentration { get; set; }
        /// <summary>
        /// Length.
        /// </summary>
        public long Length { get; set; }

        /// <summary>
        /// True if this is a vector sequence.
        /// </summary>
        public bool IsVector { get; set; }

        /// <summary>
        /// Fragment sample volume.
        /// </summary>
        public double Volume { get; set; }

        /// <summary>
        /// Reaction volume.
        /// </summary>
        public double ReactionVolume { get; set; }  

        /// <summary>
        /// Fragment constructor.
        /// </summary>
        /// <param name="source">Filename or URL.</param>
        /// <param name="name">Fragment name.</param>
        public Fragment(String source, String name, ISequence sequence, bool vector = false)
        {
            this.Source = source;
            this.Name = name;
            this.Sequence = sequence;
            this.Length = sequence.Count;
            this.IsVector = vector;
        }

        /// <summary>
        /// Fragment constructor.
        /// </summary>
        public Fragment()
        {
        }

        /// <summary>
        /// Copying Fragment constructor.
        /// </summary>
        public Fragment(Fragment frag)
        {
            this.Name = frag.Name;
            this.Sequence = new Sequence(frag.Sequence);
            this.Source = frag.Source;
        }

        /// <summary>
        /// Returns full fragment sequence as a string. Based on .NET Bio Programming Guide.
        /// </summary>
        /// <returns>Sequence string.</returns>
        public string GetString()
        {
            char[] symbols = new char[this.Sequence.Count];
            for (long index = 0; index < this.Sequence.Count; index++)
            {
                symbols[index] = (char)this.Sequence[index]; 
            }
            return new String(symbols); 
        }

        /// <summary>
        /// Returns full fragment reverse complement sequence as a string.
        /// </summary>
        /// <returns>Reverse complement sequence string.</returns>
        public string GetReverseComplementString()
        {
            ISequence revComp = this.Sequence.GetReverseComplementedSequence();
            char[] symbols = new char[revComp.Count];
            for (long index = 0; index < revComp.Count; index++)
            {
                symbols[index] = (char)revComp[index];
            }
            return new String(symbols); 
        }
    }
}

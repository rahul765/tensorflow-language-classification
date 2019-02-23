
namespace Aimirim.iView
{
	using System;
	using System.ComponentModel;
	using System.Drawing;
	using System.Drawing.Imaging;
	using System.Windows.Forms;
	using System.Xml;
	using System.Collections.Generic;

	[TypeConverter(typeof(BehaviorConverter))]
	public class TextBehavior : AbstractBehavior
	{
		#region FIELDS
		private bool _enable;
		private TextTableCollection _textTableCollection = new TextTableCollection();
		private delegate void OutputUpdateDelegate();
		#endregion

		#region PROPERTIES
		public bool Enable
		{
			get
			{
				return _enable;
			}
			set
			{
				_enable = value;
			}
		}
		public override string ToString()
		{
			// Mostra vazio na propertieGrid
			return String.Empty;
		}

		[DesignerSerializationVisibility(DesignerSerializationVisibility.Content)]
		[TypeConverter(typeof(TextTableCollectionConverter))]
		[RefreshProperties(RefreshProperties.All)]
		public TextTableCollection List
		{
			get
			{
				return _textTableCollection;
			}
		}
		#endregion

		#region CONSTRUCTORS
		public TextBehavior()
		{
		}
		#endregion

		#region METHODS
		public void Attach(Control parent)
		{
			_parent = parent;
			_parent.Disposed += delegate 
			{	
				foreach (ITag tag in _tagList)
				{
					tag.ValueChanged -= new EventHandler(tag_ValueChanged);
				}
			};
		}
		public override void Execute()
		{
			if (_parent != null)
			{
				if (_parent.InvokeRequired)
				{
					_parent.Invoke(new OutputUpdateDelegate(OutputUpdateCallback), new object[] {});
				}
				else
				{
					OutputUpdateCallback(); //call directly
				}
			}
		}
		private void OutputUpdateCallback()
		{
			if (Enable)
			{
				TextTable currTextTable = null;
				foreach (TextTable textTable in _textTableCollection)
				{
					if (getResult(textTable.Expression, _parent.FindForm() as IFrmTagGroup).ToString().Equals("True"))
					{
						currTextTable = textTable;
						break;
					}
				}
				
				if (currTextTable != null)
				{
					string val = getResult(currTextTable.Text, _parent.FindForm() as IFrmTagGroup).ToString();
					double rstDouble;
					int rstInteger;
					if (Double.TryParse(val, out rstDouble))
					{
						_parent.Text = String.Format(currTextTable.Mask, rstDouble);
					}
					else if (int.TryParse(val, out rstInteger))
					{
						_parent.Text = String.Format(currTextTable.Mask, rstInteger);
					}
					else
					{
						_parent.Text = String.Format(currTextTable.Mask, val);
					}
				}
				
				_parent.Parent.Invalidate(_parent.Bounds, true);
				//_parent.Invalidate();
			}
		}
		#endregion
	}
}

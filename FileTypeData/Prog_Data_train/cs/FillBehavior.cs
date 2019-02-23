
namespace Aimirim.iView
{
	using System;
	using System.ComponentModel;
	using System.Drawing;
	using System.Drawing.Imaging;
	using System.Windows.Forms;
	using System.Collections.Generic;
	using System.Xml;

	[TypeConverter(typeof(BehaviorConverter))]
	public class FillBehavior : AbstractBehavior
	{
		#region FIELDS
		private bool _enable = false;
		private string _expression = string.Empty;

		private int _min = 0;	                    // Minimum value for progress range
		private int _max = 100;	                    // Maximum value for progress range
		private Color _progressBarColor = Color.Blue;		// Color of progress meter
		private Direction _direction = Direction.Up;
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
		public string Expression
		{
			get
			{
				return _expression;
			}
			set
			{
				_expression = value;
			}
		}

		public int Minimum
		{
			get
			{
				return _min;
			}

			set
			{
				_min = value;
			}
		}
		public int Maximum
		{
			get
			{
				return _max;
			}

			set
			{
				_max = value;
			}
		}
		public Color ProgressBarColor
		{
			get
			{
				return _progressBarColor;
			}

			set
			{
				_progressBarColor = value;
			}
		}
		public Direction FillDirection
		{
			get
			{
				return _direction;
			}
			set
			{
				_direction = value;
			}
		}
		public override string ToString()
		{
			return "";
		}
		#endregion

		#region CONSTRUCTORS
		public FillBehavior()
		{
		}
		#endregion

		#region METHODS
		public void Attach(Control parent)
		{
			_parent = parent;
			_parent.Paint += new PaintEventHandler(Paint);
			_parent.Disposed += delegate
			{
				foreach (ITag tag in _tagList)
				{
					tag.ValueChanged -= new EventHandler(tag_ValueChanged);
				}

				_parent.Paint -= new PaintEventHandler(Paint);
			};
		}
		public override void Execute()
		{
			if (_parent != null)
			{
				//_parent.Parent.Invalidate(_parent.Bounds, true);
				_parent.Invalidate();
			}
		}
		
		private void Paint(object sender, PaintEventArgs e)
		{
			if (Enable)
			{
				int val;
				if (!int.TryParse(getResult(_expression, _parent.FindForm() as IFrmTagGroup).ToString(), out val))
				{
					return;
				}
				
				Graphics g = e.Graphics;
				SolidBrush brush = new SolidBrush(_progressBarColor);
				float percent = (float)(val - _min) / (float)(_max - _min);
				Rectangle rect = _parent.ClientRectangle;
				
				// Draw a three-dimensional border around the control.
				//Draw3DBorder(g);

				switch (_direction)
				{
					case Direction.Down:
						rect.Width = (int)((float)rect.Width * 100);
						rect.Height = (int)((float)rect.Height * percent);
						break;
					case Direction.Left:
						rect.Width = (int)((float)rect.Width * percent);
						//g.TranslateTransform((float)_parent.ClientRectangle.Width, (float)_parent.ClientRectangle.Height);
						//g.RotateTransform(180);
						rect.X = rect.X + (_parent.ClientRectangle.Width - rect.Width);
						break;
					case Direction.Right:
						rect.Width = (int)((float)rect.Width * percent);
						break;
					case Direction.Up:
						rect.Width = (int)((float)rect.Width * 100);
						rect.Height = (int)((float)rect.Height * percent);
						
						rect.Y = rect.Y + (_parent.ClientRectangle.Height - rect.Height);
						//g.TranslateTransform((float)_parent.ClientRectangle.Width, (float)_parent.ClientRectangle.Height);
						//g.RotateTransform(180);
						break;
				}

				// Draw the progress meter.
				g.FillRectangle(brush, rect);
			}
		}
		#endregion
	}
}

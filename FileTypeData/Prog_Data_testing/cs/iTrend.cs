using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Threading;
using System.Windows.Forms;
using System.Windows.Forms.DataVisualization.Charting;

namespace Aimirim.iView
{
	public partial class iTrend : UserControl
	{
		#region FIELDS
		// 
		private Thread _thdRunner;
		// 
		private delegate void RunnerDelegate();
		private RunnerDelegate _runnerDelegate;
		// 
		private iPenCollection _pens = new iPenCollection();
		private BindingSource _bs;
		private Random rand = new Random();
		private iTrendMode _trendMode;
		#endregion
		
		#region PROPERTIES
		[DesignerSerializationVisibility(DesignerSerializationVisibility.Content)]
		[TypeConverter(typeof(iPenCollectionConverter))]
		[RefreshProperties(RefreshProperties.All)]
		public iPenCollection Pens
		{
			get
			{
				if (_pens == null)
				{
					_pens = new iPenCollection();
				}
				return _pens;
			}
		}

		[Browsable(false)]
		public iTrendMode TrendMode
		{
			get
			{
				return _trendMode;
			}
			set
			{
				_trendMode = value;
				OnTrendModeChanged(new EventArgs());
			}
		}
		#endregion
		
		#region CONSTRUCTORS
		public iTrend()
		{
			InitializeComponent();
		}
		#endregion
		
		#region METHODS
		private void iTrendLoad(object sender, System.EventArgs e)
		{
			if (!DesignMode)
			{
				// Cria uma série para cada tag
				CreateSeries();
				// Faz as ligações dos dados com a grid
				BindingData();
				//
				StartThreadService();
			}
		}
		private void CreateSeries()
		{
			// Scrollbars position
			chart1.ChartAreas[0].AxisX.ScrollBar.IsPositionedInside = false;
			chart1.ChartAreas[0].AxisX.ScrollBar.Enabled = false;
			
			chart1.ChartAreas[0].CursorX.IsUserSelectionEnabled = true;
			chart1.ChartAreas[0].CursorX.Interval = 10;
			chart1.ChartAreas[0].CursorX.IntervalType = DateTimeIntervalType.Seconds;
			
			chart1.ChartAreas[0].AxisX.Interval = 10;
			chart1.ChartAreas[0].AxisX.IntervalType = DateTimeIntervalType.Seconds;
			
			chart1.ChartAreas[0].AxisX.MajorGrid.Interval = Double.NaN;
			chart1.ChartAreas[0].AxisX.MajorGrid.IntervalType = DateTimeIntervalType.NotSet;
			chart1.ChartAreas[0].AxisX.MajorTickMark.Interval = Double.NaN;
			chart1.ChartAreas[0].AxisX.MajorTickMark.IntervalType = DateTimeIntervalType.NotSet;
			
			// Reset number of series in the chart.
			chart1.Series.Clear();

			//Random rand = new Random();
			foreach (iPen pen in _pens)
			{
				pen.Attach(this);
				Series newSeries = new Series(pen.Name);
				newSeries.ChartType = SeriesChartType.Line;
				newSeries.BorderWidth = pen.BorderWidth;
				newSeries.Color = pen.LineColor;
				newSeries.ShadowOffset = 1;
				newSeries.XValueType = ChartValueType.DateTime;
				newSeries.Tag = pen;
				// Use point index instead of the X value
				newSeries.IsXValueIndexed = false;
				
				if (pen.Historical != null)
				{
					DataTableReader reader = pen.Historical.CreateDataReader();
					newSeries.Points.DataBindXY(reader, "TmStamp", reader, "Value");
				}
				
				chart1.Series.Add( newSeries );
			}
		}
		private void BindingData()
		{
			_bs = new BindingSource();
			_bs.PositionChanged += new EventHandler(BsPositionChanged);
			_bs.DataSource = _pens;
			dgvPen.DataSource = _bs;
			dgvPen.DataError += new DataGridViewDataErrorEventHandler(DgvPenDataError);
		}
		private void StartThreadService()
		{
			// 
			ThreadStart runnerThreadStart = new ThreadStart(RunnerThreadLoop);
			_thdRunner = new Thread(runnerThreadStart);
			_runnerDelegate += new RunnerDelegate(Runner);
			
			// start worker threads.
			if ( _thdRunner.IsAlive == true )
			{
				_thdRunner.Resume();
			}
			else
			{
				_thdRunner.Start();
			}
		}

		private void BsPositionChanged(object sender, EventArgs e)
		{
			if (_bs.Position < 0)
				return;
			
			chart1.ChartAreas[0].AxisY.Minimum = Convert.ToDouble(_pens[_bs.Position].Minimum) - 1;
			chart1.ChartAreas[0].AxisY.Maximum = Convert.ToDouble(_pens[_bs.Position].Maximum) + 1;
		}
		private void DgvPenDataError(object sender, DataGridViewDataErrorEventArgs e)
		{
			e.Cancel = true;
		}
		
		private void RunnerThreadLoop()
		{
			try
			{
				while (true)
				{
					// Invoke method must be used to interact with the chart
					// control on the form!
					if (TrendMode != iTrendMode.Historical)
					{
						Invoke(_runnerDelegate);
						
						//chart1.Invoke(_runnerDelegate);
	
						// Thread is inactive for 200ms
						Thread.Sleep(1000);
					}
				}
			}
			catch
			{
				// Thread is aborted
			}
		}
		
		private bool _lk = true;
		private void Runner()
		{
			if (TrendMode == iTrendMode.Historical)
			{
				foreach (Series item in chart1.Series)
				{
					iPen pen = item.Tag as iPen;
					iPen curPen = _pens[_bs.Position];
					if (curPen.Name.Equals(pen.Name))
					{
						chart1.ChartAreas[0].AxisX.Minimum = item.Points[0].XValue;
						chart1.ChartAreas[0].AxisX.Maximum = DateTime.FromOADate(item.Points[item.Points.Count - 1].XValue).AddSeconds(20).ToOADate();
					}

					
					if (pen.Historical != null)
					{
						DataTableReader reader = pen.Historical.CreateDataReader();
						item.Points.DataBindXY(reader, "TmStamp", reader, "Value");
					}
				}
				
				chart1.Invalidate();
			}
			else // Real Time Mode
			{
				DateTime timeStamp = DateTime.Now;
				foreach ( Series ptSeries in chart1.Series )
				{
					AddNewPoint( timeStamp, ptSeries );
				}
			}

			dgvPen.Refresh();
		}
		private void AddNewPoint( DateTime timeStamp, System.Windows.Forms.DataVisualization.Charting.Series ptSeries )
		{
			// Extrai o objeto pen previamente colocado na propriedade Tag
			iPen pen = ptSeries.Tag as iPen;
			
			// Add new data point to its series.
			ptSeries.Points.AddXY( timeStamp.ToOADate(), Double.Parse(pen.Value));

			chart1.ChartAreas[0].AxisX.Minimum = timeStamp.AddSeconds(-30).ToOADate();
			chart1.ChartAreas[0].AxisX.Maximum = timeStamp.AddSeconds(30).ToOADate();
			
			double zoomMin = DateTime.FromOADate(chart1.ChartAreas[0].AxisX.Minimum).AddSeconds(-10).ToOADate();
			double zoomMax = timeStamp.AddSeconds(5).ToOADate();
			chart1.ChartAreas[0].AxisX.ScaleView.Zoom(zoomMin, zoomMax);

			chart1.Invalidate();
		}

		private void RunRealTimeMode()
		{
			chart1.ChartAreas[0].AxisX.LabelStyle.Interval = 10;
			chart1.ChartAreas[0].AxisX.LabelStyle.IntervalType = DateTimeIntervalType.Seconds;
			
			chart1.ChartAreas[0].AxisX.ScrollBar.Enabled = false;
		}
		private void RunHistoricalMode()
		{
			chart1.ChartAreas[0].AxisX.ScaleView.ZoomReset();
			chart1.ChartAreas[0].AxisX.LabelStyle.Interval = 5;
			chart1.ChartAreas[0].AxisX.LabelStyle.IntervalType = DateTimeIntervalType.Minutes;

			chart1.ChartAreas[0].AxisX.ScrollBar.Enabled = true;
			
			Runner();
		}
		public void Zoom(DateTime startDate, DateTime endDate)
		{
			chart1.ChartAreas[0].AxisX.ScaleView.Zoom(startDate.ToOADate(), endDate.ToOADate());
		}
		protected virtual void OnTrendModeChanged(EventArgs e)
		{
			if (TrendMode == iTrendMode.RealTime)
			{
				RunRealTimeMode();
			}
			else
			{
				RunHistoricalMode();
			}
			
			if (_trendModeChanged != null)
			{
				_trendModeChanged(this, e);
			}
		}
		#endregion

		#region EVENTS
		protected event EventHandler _trendModeChanged;
		public event EventHandler TrendModeChanged
		{
			add
			{
				_trendModeChanged += value;
			}
			remove
			{
				_trendModeChanged -= value;
			}
		}
		#endregion
	}
}

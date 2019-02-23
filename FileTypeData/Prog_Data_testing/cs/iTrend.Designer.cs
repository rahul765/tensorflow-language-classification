namespace Aimirim.iView
{
	partial class iTrend
	{
		/// <summary>
		/// Designer variable used to keep track of non-visual components.
		/// </summary>
		private System.ComponentModel.IContainer components = null;
		
		/// <summary>
		/// Disposes resources used by the control.
		/// </summary>
		/// <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
		protected override void Dispose(bool disposing)
		{
			if (disposing) {
				if (components != null) {
					components.Dispose();
				}
			}
			base.Dispose(disposing);
		}
		
		/// <summary>
		/// This method is required for Windows Forms designer support.
		/// Do not change the method contents inside the source code editor. The Forms designer might
		/// not be able to load this method if it was changed manually.
		/// </summary>
		private void InitializeComponent()
		{
			System.Windows.Forms.DataVisualization.Charting.ChartArea chartArea1 = new System.Windows.Forms.DataVisualization.Charting.ChartArea();
			System.Windows.Forms.DataVisualization.Charting.Legend legend1 = new System.Windows.Forms.DataVisualization.Charting.Legend();
			System.Windows.Forms.DataVisualization.Charting.Series series1 = new System.Windows.Forms.DataVisualization.Charting.Series();
			this.splitContainer1 = new System.Windows.Forms.SplitContainer();
			this.chart1 = new System.Windows.Forms.DataVisualization.Charting.Chart();
			this.dgvPen = new System.Windows.Forms.DataGridView();
			this.ColumnName = new System.Windows.Forms.DataGridViewTextBoxColumn();
			this.ColumnDescription = new System.Windows.Forms.DataGridViewTextBoxColumn();
			this.ColumnValue = new System.Windows.Forms.DataGridViewTextBoxColumn();
			this.ColumnLineColor = new System.Windows.Forms.DataGridViewTextBoxColumn();
			this.ColumnBorderWidth = new System.Windows.Forms.DataGridViewTextBoxColumn();
			this.ColumnRange = new System.Windows.Forms.DataGridViewTextBoxColumn();
			((System.ComponentModel.ISupportInitialize)(this.splitContainer1)).BeginInit();
			this.splitContainer1.Panel1.SuspendLayout();
			this.splitContainer1.Panel2.SuspendLayout();
			this.splitContainer1.SuspendLayout();
			((System.ComponentModel.ISupportInitialize)(this.chart1)).BeginInit();
			((System.ComponentModel.ISupportInitialize)(this.dgvPen)).BeginInit();
			this.SuspendLayout();
			// 
			// splitContainer1
			// 
			this.splitContainer1.Dock = System.Windows.Forms.DockStyle.Fill;
			this.splitContainer1.Location = new System.Drawing.Point(0, 0);
			this.splitContainer1.Name = "splitContainer1";
			this.splitContainer1.Orientation = System.Windows.Forms.Orientation.Horizontal;
			// 
			// splitContainer1.Panel1
			// 
			this.splitContainer1.Panel1.Controls.Add(this.chart1);
			// 
			// splitContainer1.Panel2
			// 
			this.splitContainer1.Panel2.Controls.Add(this.dgvPen);
			this.splitContainer1.Size = new System.Drawing.Size(617, 557);
			this.splitContainer1.SplitterDistance = 411;
			this.splitContainer1.TabIndex = 0;
			// 
			// chart1
			// 
			this.chart1.BackColor = System.Drawing.Color.FromArgb(((int)(((byte)(211)))), ((int)(((byte)(223)))), ((int)(((byte)(240)))));
			this.chart1.BackGradientStyle = System.Windows.Forms.DataVisualization.Charting.GradientStyle.TopBottom;
			this.chart1.BackSecondaryColor = System.Drawing.Color.White;
			this.chart1.BorderlineColor = System.Drawing.Color.FromArgb(((int)(((byte)(26)))), ((int)(((byte)(59)))), ((int)(((byte)(105)))));
			this.chart1.BorderlineDashStyle = System.Windows.Forms.DataVisualization.Charting.ChartDashStyle.Solid;
			this.chart1.BorderlineWidth = 2;
			this.chart1.BorderSkin.SkinStyle = System.Windows.Forms.DataVisualization.Charting.BorderSkinStyle.Emboss;
			chartArea1.Area3DStyle.Inclination = 15;
			chartArea1.Area3DStyle.IsClustered = true;
			chartArea1.Area3DStyle.IsRightAngleAxes = false;
			chartArea1.Area3DStyle.Perspective = 10;
			chartArea1.Area3DStyle.Rotation = 10;
			chartArea1.Area3DStyle.WallWidth = 0;
			chartArea1.AxisX.LabelStyle.Font = new System.Drawing.Font("Trebuchet MS", 8.25F, System.Drawing.FontStyle.Bold);
			chartArea1.AxisX.LabelStyle.Format = "hh:mm:ss";
			chartArea1.AxisX.LabelStyle.Interval = 10D;
			chartArea1.AxisX.LabelStyle.IntervalType = System.Windows.Forms.DataVisualization.Charting.DateTimeIntervalType.Seconds;
			chartArea1.AxisX.LineColor = System.Drawing.Color.FromArgb(((int)(((byte)(64)))), ((int)(((byte)(64)))), ((int)(((byte)(64)))), ((int)(((byte)(64)))));
			chartArea1.AxisX.MajorGrid.Interval = 10D;
			chartArea1.AxisX.MajorGrid.IntervalType = System.Windows.Forms.DataVisualization.Charting.DateTimeIntervalType.Seconds;
			chartArea1.AxisX.MajorGrid.LineColor = System.Drawing.Color.FromArgb(((int)(((byte)(64)))), ((int)(((byte)(64)))), ((int)(((byte)(64)))), ((int)(((byte)(64)))));
			chartArea1.AxisX.MajorTickMark.Interval = 10D;
			chartArea1.AxisX.MajorTickMark.IntervalType = System.Windows.Forms.DataVisualization.Charting.DateTimeIntervalType.Seconds;
			chartArea1.AxisY.IsLabelAutoFit = false;
			chartArea1.AxisY.IsStartedFromZero = false;
			chartArea1.AxisY.LabelStyle.Font = new System.Drawing.Font("Trebuchet MS", 8.25F, System.Drawing.FontStyle.Bold);
			chartArea1.AxisY.LineColor = System.Drawing.Color.FromArgb(((int)(((byte)(64)))), ((int)(((byte)(64)))), ((int)(((byte)(64)))), ((int)(((byte)(64)))));
			chartArea1.AxisY.MajorGrid.LineColor = System.Drawing.Color.FromArgb(((int)(((byte)(64)))), ((int)(((byte)(64)))), ((int)(((byte)(64)))), ((int)(((byte)(64)))));
			chartArea1.BackColor = System.Drawing.Color.FromArgb(((int)(((byte)(64)))), ((int)(((byte)(165)))), ((int)(((byte)(191)))), ((int)(((byte)(228)))));
			chartArea1.BackGradientStyle = System.Windows.Forms.DataVisualization.Charting.GradientStyle.TopBottom;
			chartArea1.BackSecondaryColor = System.Drawing.Color.White;
			chartArea1.BorderColor = System.Drawing.Color.FromArgb(((int)(((byte)(64)))), ((int)(((byte)(64)))), ((int)(((byte)(64)))), ((int)(((byte)(64)))));
			chartArea1.BorderDashStyle = System.Windows.Forms.DataVisualization.Charting.ChartDashStyle.Solid;
			chartArea1.InnerPlotPosition.Auto = false;
			chartArea1.InnerPlotPosition.Height = 85F;
			chartArea1.InnerPlotPosition.Width = 86F;
			chartArea1.InnerPlotPosition.X = 8.3969F;
			chartArea1.InnerPlotPosition.Y = 3.63068F;
			chartArea1.Name = "Default";
			chartArea1.Position.Auto = false;
			chartArea1.Position.Height = 86.76062F;
			chartArea1.Position.Width = 88F;
			chartArea1.Position.X = 5.089137F;
			chartArea1.Position.Y = 5.895753F;
			chartArea1.ShadowColor = System.Drawing.Color.Transparent;
			this.chart1.ChartAreas.Add(chartArea1);
			this.chart1.Dock = System.Windows.Forms.DockStyle.Fill;
			legend1.Alignment = System.Drawing.StringAlignment.Far;
			legend1.BackColor = System.Drawing.Color.Transparent;
			legend1.DockedToChartArea = "Default";
			legend1.Docking = System.Windows.Forms.DataVisualization.Charting.Docking.Top;
			legend1.Font = new System.Drawing.Font("Trebuchet MS", 8.25F, System.Drawing.FontStyle.Bold);
			legend1.IsTextAutoFit = false;
			legend1.LegendStyle = System.Windows.Forms.DataVisualization.Charting.LegendStyle.Row;
			legend1.Name = "Default";
			this.chart1.Legends.Add(legend1);
			this.chart1.Location = new System.Drawing.Point(0, 0);
			this.chart1.Name = "chart1";
			series1.BorderColor = System.Drawing.Color.FromArgb(((int)(((byte)(180)))), ((int)(((byte)(26)))), ((int)(((byte)(59)))), ((int)(((byte)(105)))));
			series1.BorderWidth = 2;
			series1.ChartArea = "Default";
			series1.ChartType = System.Windows.Forms.DataVisualization.Charting.SeriesChartType.Line;
			series1.Color = System.Drawing.Color.FromArgb(((int)(((byte)(224)))), ((int)(((byte)(64)))), ((int)(((byte)(10)))));
			series1.Legend = "Default";
			series1.Name = "Series1";
			series1.ShadowOffset = 1;
			this.chart1.Series.Add(series1);
			this.chart1.Size = new System.Drawing.Size(617, 411);
			this.chart1.TabIndex = 36;
			// 
			// dgvPen
			// 
			this.dgvPen.AllowUserToAddRows = false;
			this.dgvPen.AllowUserToDeleteRows = false;
			this.dgvPen.BackgroundColor = System.Drawing.Color.White;
			this.dgvPen.ColumnHeadersHeightSizeMode = System.Windows.Forms.DataGridViewColumnHeadersHeightSizeMode.AutoSize;
			this.dgvPen.Columns.AddRange(new System.Windows.Forms.DataGridViewColumn[] {
									this.ColumnName,
									this.ColumnDescription,
									this.ColumnValue,
									this.ColumnLineColor,
									this.ColumnBorderWidth,
									this.ColumnRange});
			this.dgvPen.Dock = System.Windows.Forms.DockStyle.Fill;
			this.dgvPen.Location = new System.Drawing.Point(0, 0);
			this.dgvPen.Name = "dgvPen";
			this.dgvPen.ReadOnly = true;
			this.dgvPen.RowHeadersWidth = 8;
			this.dgvPen.RowHeadersWidthSizeMode = System.Windows.Forms.DataGridViewRowHeadersWidthSizeMode.DisableResizing;
			this.dgvPen.Size = new System.Drawing.Size(617, 142);
			this.dgvPen.TabIndex = 36;
			// 
			// ColumnName
			// 
			this.ColumnName.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill;
			this.ColumnName.DataPropertyName = "Name";
			this.ColumnName.HeaderText = "Nome";
			this.ColumnName.Name = "ColumnName";
			this.ColumnName.ReadOnly = true;
			// 
			// ColumnDescription
			// 
			this.ColumnDescription.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill;
			this.ColumnDescription.DataPropertyName = "Description";
			this.ColumnDescription.HeaderText = "Descrição";
			this.ColumnDescription.Name = "ColumnDescription";
			this.ColumnDescription.ReadOnly = true;
			// 
			// ColumnValue
			// 
			this.ColumnValue.DataPropertyName = "Value";
			this.ColumnValue.HeaderText = "Valor";
			this.ColumnValue.MinimumWidth = 100;
			this.ColumnValue.Name = "ColumnValue";
			this.ColumnValue.ReadOnly = true;
			// 
			// ColumnLineColor
			// 
			this.ColumnLineColor.DataPropertyName = "LineColor";
			this.ColumnLineColor.HeaderText = "LineColor";
			this.ColumnLineColor.Name = "ColumnLineColor";
			this.ColumnLineColor.ReadOnly = true;
			this.ColumnLineColor.Visible = false;
			// 
			// ColumnBorderWidth
			// 
			this.ColumnBorderWidth.DataPropertyName = "BorderWidth";
			this.ColumnBorderWidth.HeaderText = "BorderWidth";
			this.ColumnBorderWidth.Name = "ColumnBorderWidth";
			this.ColumnBorderWidth.ReadOnly = true;
			this.ColumnBorderWidth.Visible = false;
			// 
			// ColumnRange
			// 
			this.ColumnRange.DataPropertyName = "Range";
			this.ColumnRange.HeaderText = "Range";
			this.ColumnRange.Name = "ColumnRange";
			this.ColumnRange.ReadOnly = true;
			this.ColumnRange.Visible = false;
			// 
			// iTrend
			// 
			this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
			this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
			this.Controls.Add(this.splitContainer1);
			this.Name = "iTrend";
			this.Size = new System.Drawing.Size(617, 557);
			this.Load += new System.EventHandler(this.iTrendLoad);
			this.splitContainer1.Panel1.ResumeLayout(false);
			this.splitContainer1.Panel2.ResumeLayout(false);
			((System.ComponentModel.ISupportInitialize)(this.splitContainer1)).EndInit();
			this.splitContainer1.ResumeLayout(false);
			((System.ComponentModel.ISupportInitialize)(this.chart1)).EndInit();
			((System.ComponentModel.ISupportInitialize)(this.dgvPen)).EndInit();
			this.ResumeLayout(false);
		}
		private System.Windows.Forms.SplitContainer splitContainer1;
		private System.Windows.Forms.DataVisualization.Charting.Chart chart1;
		private System.Windows.Forms.DataGridViewTextBoxColumn ColumnRange;
		private System.Windows.Forms.DataGridViewTextBoxColumn ColumnBorderWidth;
		private System.Windows.Forms.DataGridViewTextBoxColumn ColumnLineColor;
		private System.Windows.Forms.DataGridViewTextBoxColumn ColumnValue;
		private System.Windows.Forms.DataGridViewTextBoxColumn ColumnDescription;
		private System.Windows.Forms.DataGridViewTextBoxColumn ColumnName;
		private System.Windows.Forms.DataGridView dgvPen;
	}
}

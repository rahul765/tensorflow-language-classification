namespace Aimirim.iView
{
	partial class iAlarm
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
			this.dgvAlarms = new System.Windows.Forms.DataGridView();
			this.ColumnName = new System.Windows.Forms.DataGridViewTextBoxColumn();
			this.ColumnDescription = new System.Windows.Forms.DataGridViewTextBoxColumn();
			this.ColumnTimeStamp = new System.Windows.Forms.DataGridViewTextBoxColumn();
			this.ColumnTimeStampAck = new System.Windows.Forms.DataGridViewTextBoxColumn();
			this.ColumnDataType = new System.Windows.Forms.DataGridViewTextBoxColumn();
			((System.ComponentModel.ISupportInitialize)(this.dgvAlarms)).BeginInit();
			this.SuspendLayout();
			// 
			// dgvAlarms
			// 
			this.dgvAlarms.AllowUserToAddRows = false;
			this.dgvAlarms.AllowUserToDeleteRows = false;
			this.dgvAlarms.BackgroundColor = System.Drawing.Color.White;
			this.dgvAlarms.ColumnHeadersHeightSizeMode = System.Windows.Forms.DataGridViewColumnHeadersHeightSizeMode.AutoSize;
			this.dgvAlarms.Columns.AddRange(new System.Windows.Forms.DataGridViewColumn[] {
									this.ColumnName,
									this.ColumnDescription,
									this.ColumnTimeStamp,
									this.ColumnTimeStampAck,
									this.ColumnDataType});
			this.dgvAlarms.Dock = System.Windows.Forms.DockStyle.Fill;
			this.dgvAlarms.Location = new System.Drawing.Point(0, 0);
			this.dgvAlarms.MultiSelect = false;
			this.dgvAlarms.Name = "dgvAlarms";
			this.dgvAlarms.ReadOnly = true;
			this.dgvAlarms.Size = new System.Drawing.Size(739, 211);
			this.dgvAlarms.TabIndex = 0;
			// 
			// ColumnName
			// 
			this.ColumnName.DataPropertyName = "Name";
			this.ColumnName.HeaderText = "Nome";
			this.ColumnName.Name = "ColumnName";
			this.ColumnName.ReadOnly = true;
			this.ColumnName.Width = 150;
			// 
			// ColumnDescription
			// 
			this.ColumnDescription.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill;
			this.ColumnDescription.DataPropertyName = "Description";
			this.ColumnDescription.HeaderText = "Descrição";
			this.ColumnDescription.MinimumWidth = 200;
			this.ColumnDescription.Name = "ColumnDescription";
			this.ColumnDescription.ReadOnly = true;
			// 
			// ColumnTimeStamp
			// 
			this.ColumnTimeStamp.DataPropertyName = "TimeStamp";
			this.ColumnTimeStamp.HeaderText = "Alarme";
			this.ColumnTimeStamp.MinimumWidth = 100;
			this.ColumnTimeStamp.Name = "ColumnTimeStamp";
			this.ColumnTimeStamp.ReadOnly = true;
			this.ColumnTimeStamp.Width = 150;
			// 
			// ColumnTimeStampAck
			// 
			this.ColumnTimeStampAck.DataPropertyName = "TimeStampAck";
			this.ColumnTimeStampAck.HeaderText = "Reconhecimento";
			this.ColumnTimeStampAck.MinimumWidth = 100;
			this.ColumnTimeStampAck.Name = "ColumnTimeStampAck";
			this.ColumnTimeStampAck.ReadOnly = true;
			this.ColumnTimeStampAck.Width = 150;
			// 
			// ColumnDataType
			// 
			this.ColumnDataType.HeaderText = "DataType";
			this.ColumnDataType.Name = "ColumnDataType";
			this.ColumnDataType.ReadOnly = true;
			this.ColumnDataType.Visible = false;
			// 
			// iAlarm
			// 
			this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
			this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
			this.Controls.Add(this.dgvAlarms);
			this.Name = "iAlarm";
			this.Size = new System.Drawing.Size(739, 211);
			((System.ComponentModel.ISupportInitialize)(this.dgvAlarms)).EndInit();
			this.ResumeLayout(false);
		}
		private System.Windows.Forms.DataGridViewTextBoxColumn ColumnTimeStampAck;
		private System.Windows.Forms.DataGridViewTextBoxColumn ColumnTimeStamp;
		private System.Windows.Forms.DataGridViewTextBoxColumn ColumnDataType;
		private System.Windows.Forms.DataGridViewTextBoxColumn ColumnDescription;
		private System.Windows.Forms.DataGridViewTextBoxColumn ColumnName;
		private System.Windows.Forms.DataGridView dgvAlarms;
	}
}

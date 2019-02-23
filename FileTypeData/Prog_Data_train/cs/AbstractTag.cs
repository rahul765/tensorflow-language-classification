
namespace Aimirim.iView
{
	using System;
	using System.ComponentModel;
	using System.Windows.Forms;
	using System.Xml;

	public abstract class AbstractTag: ITag
	{
		#region FIELDS
		protected string _name;
		protected string _description;
		protected string _alarmState = "Ok";
		#endregion

		#region PROPERTIES
		public string Name
		{
			get
			{
				return _name;
			}
			set
			{
				_name = value;
			}
		}
		public string Description
		{
			get
			{
				return _description;
			}
			set
			{
				_description = value;
			}
		}
		public string AlarmState
		{
			get
			{
				return _alarmState;
			}
		}
		public abstract TagType DataType { get; }
		public abstract string Value {get; set;}
		public abstract string Address {get; set;}
		public abstract IDriver Driver {get; set;}
		#endregion

		#region CONSTRUCTORS
		#endregion

		#region METHODS
		protected virtual void OnValueChanged(EventArgs e)
		{
			if (Name == "INICIOOP")
			{
				Console.WriteLine("dentro do base OnValueChanged");
			}
			if (_valueChanged != null)
			{
				if (Name == "INICIOOP")
				{
					Console.WriteLine("if != null do base OnValueChanged");
				}
				_valueChanged(this, e);
			}
			if (Name == "INICIOOP")
			{
				Console.WriteLine("saindo do base OnValueChanged");
			}
		}

		public virtual DialogResult ShowConfigurator()
		{
			using (DefaultTagConfigurator frm = new DefaultTagConfigurator())
			{
				frm.Tag = this;
				return frm.ShowDialog();
			}
		}
		public virtual void ReadXml(XmlNode tag)
		{
			Name = tag.Attributes["Name"].Value;
			Description = tag.Attributes["Description"].Value;
			Address = tag.Attributes["Address"].Value;
			Driver = DriverManager.Instance.GetDriver(tag.Attributes["Driver"].Value);
			Value = Driver.GetValue(Address);
		}
		public virtual void WriteXml(ref XmlElement parent)
		{
			//Create a new Tag node.
			XmlElement tagElement = parent.OwnerDocument.CreateElement("Tag");
			tagElement.SetAttribute("Name", Name);
			tagElement.SetAttribute("Description", Description);
			tagElement.SetAttribute("DataType", DataType.ToString());
			tagElement.SetAttribute("Address", Address);
			tagElement.SetAttribute("Driver", Driver.Id);
			
			parent.AppendChild(tagElement);
		}
		#endregion

		#region EVENTS
		protected event EventHandler _valueChanged;
		public event EventHandler ValueChanged
		{
			add
			{
				_valueChanged += value;
			}
			remove
			{
				_valueChanged -= value;
			}
		}
		#endregion
	}
}

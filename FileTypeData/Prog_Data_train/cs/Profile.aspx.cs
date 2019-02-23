using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;

using System.Data.SqlClient;
using System.Data;
using System.Text;
using System.IO;
using System.Security.Cryptography;

namespace Lrbd.Account
{
    public partial class Profile : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            if (!Page.IsPostBack)
            {
                string Id = HttpUtility.ParseQueryString(this.ClientQueryString).Get("id");
                SqlConnection connection = new SqlConnection(System.Configuration.ConfigurationManager.ConnectionStrings["lrbdConnectionString"].ConnectionString);
                SqlCommand cmd = new SqlCommand();
                cmd.Connection = connection;
                cmd.CommandText = "CustomerMasterById";
                cmd.CommandType = CommandType.StoredProcedure;
                cmd.Parameters.Add("@customer_id", SqlDbType.Int).Value = Id;
                connection.Open();
                SqlDataReader reader = cmd.ExecuteReader();
                DataTable returnItems = new DataTable();
                returnItems.Load(reader);

                reader.Close();
                connection.Close();
                foreach (DataRow row in returnItems.Rows)
                {
                    CustomerId.Value = row["customer_id"].ToString();
                    Username.Text = row["username"].ToString();
                    Password.Text = row["password"].ToString();
                    Name.Text = row["name"].ToString();
                    Email.Text = row["email"].ToString();
                    Phone.Text = row["phone"].ToString();
                }
            }
        }

        protected void Button1_Click(object sender, EventArgs e)
        {
            SqlConnection connection = new SqlConnection(System.Configuration.ConfigurationManager.ConnectionStrings["lrbdConnectionString"].ConnectionString);
            connection.Open();
            SqlCommand cmd = new SqlCommand("CustomerMasteredit", connection);
            cmd.CommandType = CommandType.StoredProcedure;
            cmd.Parameters.Add("@customer_id", SqlDbType.Int).Value = CustomerId.Value;
            cmd.Parameters.Add("@username", SqlDbType.VarChar).Value = Username.Text;
            cmd.Parameters.Add("@password", SqlDbType.VarChar).Value = GetMD5HashData(Password.Text);
            cmd.Parameters.Add("@name", SqlDbType.Text).Value = Name.Text;
            cmd.Parameters.Add("@email", SqlDbType.VarChar).Value = Email.Text;
            cmd.Parameters.Add("@phone", SqlDbType.VarChar).Value = Phone.Text;
            cmd.ExecuteNonQuery();
            connection.Close();
            Response.Redirect("Dashboard.aspx");
        }

        private string GetMD5HashData(string data)
        {
            //create new instance of md5
            MD5 md5 = MD5.Create();

            //convert the input text to array of bytes
            byte[] hashData = md5.ComputeHash(Encoding.Default.GetBytes(data));

            //create new instance of StringBuilder to save hashed data
            StringBuilder returnValue = new StringBuilder();

            //loop for each byte and add it to StringBuilder
            for (int i = 0; i < hashData.Length; i++)
            {
                returnValue.Append(hashData[i].ToString());
            }

            // return hexadecimal string
            return returnValue.ToString();

        }
    }
}
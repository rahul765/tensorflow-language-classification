<?php

class TicketDownload 
{
	private  $csv_terminated = "\n";
	private  $csv_separator = ",";
	private  $csv_enclosed = '"';
	private  $csv_escaped = "\\";
	private  $schema_insert = ''; 
	private  $ExportFile='';
	private  $DB;
	private  $Dte;
	private  $FromDate;
	private  $ToDate;	
	public function __Construct($dbHost,$dbUser,$dbPwd,$dbName,$Date,$fromDate,$toDate)
	 {
		$Db=new DbConnection($dbHost,$dbUser,$dbPwd,$dbName);
		$this->DB=$Db->SelectDb();  
		if(!empty($toDate))
			{	
				
				$this->FromDate=gmdate($fromDate);	
				$this->ToDate=gmdate($toDate);	
			//	echo $this->FromDate;
			//	echo $this->ToDate;
				
			}
		else
			{
			    $this->Dte=gmdate($Date);								
				//echo $this->Dte;
			}
	 }
	
	private function TicketTimerQuery()
     {
		return mysql_query("SELECT * FROM `bugs_activity` WHERE `bug_when` >='$this->Dte' ORDER BY `bug_when` DESC");	  
     }  
 
    private function TicketHistoryQuery()
     {
		return mysql_query("SELECT * FROM `bugs_activity` WHERE `bug_when` Between '$this->FromDate' and '$this->ToDate' ORDER BY `bug_when` DESC");	  
     }

	function ExportToCSV()
	{		 
		if(!empty($this->ToDate))
		{
		//  Gets the data from the database
			$this->ExportFile='TicketHistory.csv';
		//	echo "Ticketisotry";
			$result = $this->TicketHistoryQuery();
			$fields_cnt = mysql_num_fields($result);
		}
		else
		{
		//  Gets the data from the database
		//   echo "Ticket Timer";
			$this->ExportFile='TicketTimer.csv';
			$result = $this->TicketTimerQuery();
			$fields_cnt = mysql_num_fields($result);
		}	
		for ($i = 0; $i < $fields_cnt; $i++)
		{
			$l = $this->csv_enclosed . str_replace($this->csv_enclosed, $this->csv_escaped . $this->csv_enclosed,
            stripslashes(mysql_field_name($result, $i))) . $this->csv_enclosed;
			$this->schema_insert .= $l;
			$this->schema_insert .= $this->csv_separator;
		} // end for	
		
		$Export=trim(substr($this->schema_insert, 0, -1));
		$Export .= $this->csv_terminated;
		
		// Format the data
		while ($row = mysql_fetch_array($result))
		{
			$this->schema_insert = '';
			for ($j = 0; $j < $fields_cnt; $j++)
			{
				if ($row[$j] == '0' || $row[$j] != '')
				{
 
					if ($this->csv_enclosed == '')
					{
						$this->schema_insert .= $row[$j];
					}
					else
					{
						$this->schema_insert .= $this->csv_enclosed . 
						str_replace($this->csv_enclosed, $this->csv_escaped . $this->csv_enclosed, $row[$j]) . $this->csv_enclosed;
					}
				}
				else
				{
					$this->schema_insert .= '';
				}
 
				if ($j < $fields_cnt - 1)
				{
					$this->schema_insert .= $this->csv_separator;
				}
			} // end for
 
			$Export .= $this->schema_insert;
			$Export .= $this->csv_terminated;
		} // end while
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Length: " . strlen($Export));
		// Output to browser with appropriate mime type, you choose ;)
		header("Content-type: text/x-csv");
		//header("Content-type: text/csv");
		//header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename=$this->ExportFile");
		echo $Export;
		exit();
 
	}
 }
 ?>
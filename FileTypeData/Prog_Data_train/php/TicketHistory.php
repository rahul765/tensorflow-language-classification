<?php
include("DBconfig.php");
include("TicketClass.php");
$Tfrm_dte=$_GET['fdate'];
$Tto_dte=$_GET['tdate'];
$TicketHistory=new TicketLog($dbHost,$dbUser,$dbPwd,$dbName,'',$Tfrm_dte,$Tto_dte);
$result=$TicketHistory->Display();
?>
<html>
 <head>
    <?php
	   include("header.php");
	?>
      <script type="text/javascript">
		$(document).ready( function () {
    		$('#TicketHistoryDetails').dataTable({
				"iDisplayLength": 20,
				"iDisplayStart": 20
			});			
		} );
   </script>
   
 </head>
 <body>
   <?php
		include("Menu.php");
	?>
   <div class="positionDownload"><a href="TicketTimerDownload.php?Tfdate=<?php echo $Tfrm_dte; ?>&Ttdate=<?php echo $Tto_dte; ?>">Download<a/></div><br/>
   <div class="TableTitle TableDetails">Ticket Log History Details</div><hr/>
   <table id="TicketHistoryDetails" class="display">
		<thead>
	 	 <tr>
			<th>BugId</th>
			<th>AttachId</th>
			<th>Who</th>
			<th>BugWhen</th>
			<th>FieldId</th>
			<th>Added</th>
			<th>Removed</th>
			<th>CommentId</th>
		 </tr>	
		</thead>
		<tbody>
		<?php
			while($data = mysql_fetch_row($result))
		{ ?>
        <tr>
			<td><?php  echo $data[0] ?></td>
			<td><?php  echo $data[1] ?></td>
			<td><?php  echo $data[2] ?></td>
			<td><?php  echo $data[3] ?></td>
			<td><?php  echo $data[4] ?></td>
			<td><?php  echo $data[5] ?></td>
			<td><?php  echo $data[6] ?></td>
			<td><?php  echo $data[7] ?></td>
		</tr>
		<?php } ?>
		</tbody>			
	</div>
 </body>
</html>

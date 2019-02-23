<?php
include("DBconfig.php");
include("ProfileClass.php");
$Pfrm_dte=$_GET['fdate'];
$Pto_dte=$_GET['tdate'];
$ProfileHistory=new ProfileActivity($dbHost,$dbUser,$dbPwd,$dbName,'',$Pfrm_dte,$Pto_dte);
$result=$ProfileHistory->Display();
?>
<html>
 <head>
	<?php
		include("header.php");
	?>
	<script type="text/javascript">
		$(document).ready( function () {
    		$('#ProfileHistoryDetails').dataTable({
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
	<div class="positionDownload"><a href="ProfileTimerDownload.php?Pfdate=<?php echo $Pfrm_dte; ?>&ptdate=<?php echo $Pto_dte; ?>">Download<a/></div><br/>
	<div class="TableTitle TableDetails">Profile Log History Details</div><hr/>
    <table id="ProfileHistoryDetails" class="display">
		<thead>
	 	 <tr>
			<th>UserId</th>
			<th>Who</th>
			<th>ProfileWhen</th>
			<th>FieldId</th>
			<th>OldValue</th>
			<th>NewValue</th>
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
		</tr>
		<?php } ?>
		</tbody>		
	
	</div>
 </body>
</html>


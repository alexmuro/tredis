<?php
	$commodity =$_POST['sctg'];
	$mode = $_POST['mode'];
	$granularity = $_POST['granularity'];

	include '../../config/db.php'; 
	$test = new db();

	$colors=array('#E41A1C','#FFFF33','#FF7F00','#999999','#984EA3','#377EB8','#4DAF4A','#F781BF');

	$inscon = $test->connect();
	$sql = "select dest_fips,sum(all_tons) as all_tons from Duluth_Flows where orig_fips = 27137 and sctg2 = '$commodity' group by dest_fips order by all_tons desc ";
	
	$csv = array();
	
	$i = 0;
	$rs=mysql_query($sql) or die($sql." ".mysql_error());
	while($row = mysql_fetch_assoc( $rs )){

		$csvrow['orig'] = $row['dest_fips'];
		$csvrow['tons'] = $row['all_tons'];
		$csv[] = $csvrow;

	}

	echo json_encode($csv);
?>
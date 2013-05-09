<?php
	$commodity =$_POST['sctg'];
	$mode = $_POST['mode'];
	$granularity = $_POST['granularity'];

	include '../../config/db.php'; 
	$test = new db();

	$colors=array('#E41A1C','#FFFF33','#FF7F00','#999999','#984EA3','#377EB8','#4DAF4A','#F781BF';
	$inscon = $test->connect();
	$sql = "SELECT distinct orig_fips, sum(all_tons) as all_tons FROM MN_Flows where
    orig_state = '27' and dest_state = '27' and sctg2 = '".$commodity."' group by orig_fips";
	
	$csv = array();
	$matrix = array();
	
	$i = 0;
	$rs=mysql_query($sql) or die($sql." ".mysql_error());
	while($row = mysql_fetch_assoc( $rs )){

		$csvrow['name'] = $row['orig_fips'];
		$csvrow['sum'] = $row['all_tons'];
		$index = number_format ( $i/12 );
		$csvrow['color'] = $colors[$index];
		$csv[] = $csvrow;
		$i++;

		 $sub_sql = "SELECT sum(all_tons) as all_tons FROM MN_Flows WHERE orig_fips = '".$row['orig_fips']."' and dest_state = '27' and sctg2 = '".$commodity."' group by dest_fips";
		
		 $sub_rs = mysql_query($sub_sql) or die($sub_sql." ".mysql_error());
		 $matrix_row = [];
		while($sub_row = mysql_fetch_assoc( $sub_rs )){

		 	$trade = ($sub_row['all_tons'] / $row['all_tons']) * 100;

		 	if($trade > $granularity){
		 		$matrix_row[] = $trade;
		 	}else{
		 		$matrix_row[] = 0;
		 	}
		 }
		$matrix[] = $matrix_row;
	}
	$data['matrix'] = $matrix;
	$data['csv'] = $csv;
	echo json_encode($data);
?>
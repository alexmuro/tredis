<?php
	ini_set('display_errors','On');
 	error_reporting(E_ALL);
	//$geo_type = $_POST['geo_type'];
	//$current_zone = $_POST['current_zone'];\
	$commodity =$_GET['sctg'];

	include '../../config/db.php'; 
	
	$test = new db();
		
	$inscon = $test->connect();
	$sql = "SELECT distinct orig_fips, sum(all_tons) as all_tons FROM MN_All_Flows where
    orig_state = '27' and dest_state = '27' and sctg2 = '".$commodity."' group by orig_fips";
	//echo "SQL=".$sql;
	$matrix = [];
	$rs=mysql_query($sql) or die($sql." ".mysql_error());
	while($row = mysql_fetch_assoc( $rs )){
		//echo $row['orig_fips'].' '.$row['all_tons'].'<br>';
		
		 $sub_sql = "SELECT sum(all_tons) as all_tons FROM MN_All_Flows WHERE orig_fips = '".$row['orig_fips']."' and dest_state = '27' and sctg2 = '".$commodity."' group by dest_fips";
		
		 $sub_rs = mysql_query($sub_sql) or die($sub_sql." ".mysql_error());
		 $matrix_row = [];
		while($sub_row = mysql_fetch_assoc( $sub_rs )){
			$matrix_row[] = $sub_row['all_tons'];
		 	//echo $sub_row['all_tons'].',';
		 	// $trade = ($sub_row['all_tons'] / $row['all_tons']) * 100;
		 	// if($trade > 15){
		 	// 	$matrix_row[] = $trade;
		 	// }else{
		 	// 	$matrix_row[] = 0;
		 	// }
		 }
		$matrix[] = $matrix_row;
		//echo '<br>';
	}
	echo json_encode($matrix);
?>
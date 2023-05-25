<?php

session_start();

if (isset($_SESSION['id'])) {
	$userId	= $_SESSION['id'];
	$dateRange = $_POST['date'];
	$date_start ;
	$date_end ;
	
	if( $_SESSION['date_start'] != 0 && $_SESSION['date_end'] != 0){
		$date_start = $_SESSION['date_start'];
		$date_end = $_SESSION['date_end'];
	}
	else{
		if($dateRange == 'this_month'){
			$date_start = date("Y-m-01");
			$date_end = date("Y-m-d");
		}
		else if($dateRange == 'last_month'){
			$date_start = date('Y-m-01', strtotime('last month'));
			$date_end = date("Y-m-01");
		}
		else if($dateRange == 'this_year'){
			$date_start = date('Y-01-01', strtotime('last month'));
			$date_end = date("Y-12-31");	
		}
	}
	require_once 'database.php';
			
			$sql = 'SELECT category, SUM(amount)  FROM expenses WHERE userId=userId GROUP BY amount ORDER BY SUM(amount) DESC';
			$stmt = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$stmt->execute();
			
	while ($row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT)) {
		$category = $row[0];
		$amount = $row[1];


		echo $category;
		echo " -> ";
		echo $amount;
		echo "<br>";
	}		
	$_SESSION['row'] = $row;
			
			
			
			$_SESSION['display_balance'] = true;
			header('Location: przegladajBilans.php'); 

} else {
	$_SESSION['display_info'] = 'Coś poszło nie tak :/';
	header('Location: przegladajBilans.php');
	exit();
}

?>





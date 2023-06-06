<?php
session_start();
if (isset($_SESSION['id'])) {
	$userId	= $_SESSION['id'];
	$dateRange;
	$date_start ;
	$date_end ;

	if( isset($_POST['date_start'] ) ){
		$date_start = $_POST['date_start'];
		$date_end = $_POST['date_end'];
	}
	
	else{
		if($_POST['date'] == 'this_month'){
			$date_start = date('Y-m-01', strtotime('now'));
			$date_end = date('Y-m-t', strtotime('now'));
		}
		else if($_POST['date'] == 'last_month'){
			$date_start =  date('Y-m-01', strtotime('now -1 month'));
			$date_end =  date('Y-m-t', strtotime('now -1 month'));
		}
		else if($_POST['date'] == 'this_year'){
			$date_start = date('Y-m-01', strtotime('now -2 month'));
			$date_end = date('Y-m-t', strtotime('now -2 month'));
		}	
	}
		$_SESSION['income_qry'] = 
		"SELECT income AS category, SUM(amount) AS total_amount
		FROM incomes WHERE userId='$userId'
		AND date BETWEEN '$date_start' AND '$date_end'
		GROUP BY category DESC
		ORDER BY total_amount DESC";

		$_SESSION['expense_qry'] = 
		"SELECT category, SUM(amount) AS total_amount
		FROM expenses WHERE userId='$userId'
		AND date BETWEEN '$date_start' AND '$date_end'
		GROUP BY category 
		ORDER BY total_amount DESC";

	header('Location: billansDisplay.php'); 

} else {
	header('Location: Index.php');
	exit();
}
?>





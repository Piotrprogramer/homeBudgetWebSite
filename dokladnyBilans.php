<?php

session_start();

if (isset($_SESSION['id'])) {
	
	$userId	= $_SESSION['id'];
	$date = $_POST['date'];
	$income = $_POST['income'];
	$amount	= $_POST['amount'];
	$comment = $_POST['comment'];

	require_once 'database.php';
			$query = $db->prepare('INSERT INTO incomes VALUES (:userId, :date, :income, :amount, :comment)');

			$query->bindValue(':userId', $userId, PDO::PARAM_INT);
			$query->bindValue(':date', $date, PDO::PARAM_STR);
			$query->bindValue(':income', $income, PDO::PARAM_STR);
			$query->bindValue(':amount', $amount, PDO::PARAM_INT);
			$query->bindValue(':comment', $comment, PDO::PARAM_STR);
			$query->execute();
			
			$_SESSION['display_info'] = 'Przychód dodany poprawnie';
			header('Location: przegladajBilans.php'); 

} else {
	$_SESSION['display_info'] = 'Coś poszło nie tak :/';
	header('Location: przegladajBilans.php');
	exit();
}

?>

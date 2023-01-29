<?php

session_start();

if (isset($_SESSION['id'])) {
	
	$userId	= $_SESSION['id'];
	$date = $_POST['date'];
	$category = $_POST['category'];
	$payment_method	= $_POST['payment_method'];
	$amount	= $_POST['amount'];
	$comment = $_POST['comment'];

	require_once 'database.php';
			$query = $db->prepare('INSERT INTO expenses VALUES (:userId, :date, :category, :payment_method, :amount, :comment)');

			$query->bindValue(':userId', $userId, PDO::PARAM_INT);
			$query->bindValue(':date', $date, PDO::PARAM_STR);
			$query->bindValue(':category', $category, PDO::PARAM_STR);
			$query->bindValue(':payment_method', $payment_method, PDO::PARAM_STR);
			$query->bindValue(':amount', $amount, PDO::PARAM_INT);
			$query->bindValue(':comment', $comment, PDO::PARAM_STR);
			$query->execute();
			
			$_SESSION['display_info'] = 'Wydatek dodany poprawnie';
			header('Location: MenuGlowne.php'); 

} else {
	$_SESSION['display_info'] = 'Dodawanie wydatku nie powiodło się';
	header('Location: MenuGlowne.php');
	exit();
}

?>

<?php

session_start();

if (isset($_POST['login']) && isset($_POST['password'])) {
	$_SESSION['given_login'] = $_POST['login'];
	$_SESSION['given_password'] = $_POST['password'];
	
	
	require_once 'database.php';

	$correctPass = true;
	$sql = 'SELECT id, login, password FROM uzytkownicy';
	$stmt = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	while ($row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT)) {
		$checkingId = $row[0];
		 $checkingLogin = $row[1];
		 $checkingPassword = $row[2];

		if( $_SESSION['given_login'] == $checkingLogin){
			if($checkingPassword == $_SESSION['given_password']){
			$_SESSION['id'] = $checkingId;
			unset($_SESSION['given_login']);
			unset($_SESSION['given_password']);
			
			 break;
			}
			else break;
		}
	}
}
if(isset($_SESSION['id'])) {
	header('Location: MenuGlowne.php');
	exit();
}
else {
	$_SESSION['wrong_pass'] = true;
	header('Location: Logowanie.php');
}
?>
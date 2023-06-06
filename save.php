<?php

session_start();

unset($_SESSION['existing_login']);

if (isset($_POST['login']) && isset($_POST['password']) && isset($_POST['repeatPassword'])) {
	$login = $_POST['login'];
	$password = $_POST['password'];
	$repeatPassword = $_POST['repeatPassword'];

	require_once 'database.php';

	$newLogin = true;
	$sql = 'SELECT login FROM uzytkownicy';
	$stmt = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	while ($row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT)) {
		$checkingLogin = $row[0];
		if($_POST['login']==$checkingLogin){
			$newLogin = false;
			$_SESSION['existing_login'] = true;
			break;
		}
	}

	if($password == $repeatPassword){

		if($newLogin){
			$query = $db->prepare('INSERT INTO uzytkownicy VALUES (NULL, :login, :password)');
			$query->bindValue(':login', $login, PDO::PARAM_STR);
			$query->bindValue(':password', $password, PDO::PARAM_STR);
			$query->execute();

		} else{
			$_SESSION['given_login'] = $_POST['login'];
			$_SESSION['existing_login'] = true;
			header('Location: registration.php');
			exit();
		}
	}
	else {
		$_SESSION['given_login'] = $_POST['login'];
		$_SESSION['difrent_password'] = true;
		header('Location: registration.php');
		exit();
	}

} else {
	
	header('Location: registration.php');
	exit();
}

?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" >
	<title>Rejestracja do budżetu</title>
	<meta name="description" content="Rejestracja" >
	<meta name="keywords" content="css, odcinek" >
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="Piotr Wasilewski">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" >
		
	<link rel="stylesheet" href="css_bootstrap/bootstrap.min.css">
	<link rel="stylesheet" href="style.css" type="text/css" >
	<link href='http://fonts.googleapis.com/css?family=Lato:400,900&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
	<link href="css/fontello.css" rel="stylesheet" type="text/css" >
	
</head>

<body>

	<div id="container">
		<div class="row justify-content-center">
			<i class="icon-money"></i> 
		</div>
		<div class="row justify-content-center" >
			<div class="col-10 col-md-8 col-xl-6 title">
				Zadbaj o swój portfel
			</div>
		</div>
		<div class="row justify-content-center" id="content">
			<div >
				<div >Gratulacje, konto założone pomyślnie!</div>
				<a href="Index.php"><div class="log_button" style="margin-top:10px">Powrót</div> </a>
			</div>	
		</div>
	</div>

	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	
	<script src="js/bootstrap.min.js"></script>
	
</body>
</html>

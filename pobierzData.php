<?php

session_start();


if (isset($_POST['login']) && isset($_POST['password']) && isset($_POST['repeatPassword'])) {
	
		/*
		require_once 'database.php';
		$query = $db->prepare("SELECT id, login, password FROM uzytkownicy");
		$query->execute();

		$result = $query->fetch(PDO::FETCH_OBJ);
		
		echo 'id: ';
		print $result->id;
		echo "<br>";
		echo 'login: ';
		print $result->login;
		echo "<br>";
		echo ' haslo: ';
		print $result->password;
		echo "<br>";
		echo "-----------";
		echo "<br>";
	*/
	require_once 'database.php';
	$sql = 'SELECT login FROM uzytkownicy';
    $stmt = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT)) {
		
        $data = $row[0];
		/*$data = $row[0] . "\t" . $row[1] ;*/
		if($_POST['login']==$data){
			echo "<br>";
			print $data;
			break;
		}
		
    }
	
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

	
</head>

<body>


	
</body>
</html>

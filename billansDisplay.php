<?php
session_start();

if(!isset($_SESSION['id'])) {
	header('Location: Index.html');
	exit();
}
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" >
	<title>Przeglądaj bilans</title>
	<meta name="description" content="Zadbaj o swój budżet" >
	<meta name="keywords" content="Budżet" >
	<meta name="author" content="Piotr Wasilewski">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" >
	
	<link rel="stylesheet" href="css_bootstrap/bootstrap.min.css">
	<link rel="stylesheet" href="style.css" type="text/css" >
	<link href='http://fonts.googleapis.com/css?family=Lato:400,900&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
	<link href="css/fontello.css" rel="stylesheet" type="text/css" >
	<script src="js/swapDiv.js"></script>

	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>				
	<div id="container">		
		<div class="row justify-content-center">
			<i class="icon-money"></i> 
		</div>
			
		<div class="row justify-content-center ">
			<nav class="navbar navbar-light navbar-expand-lg col-10  col-xl-8" id="menu_bar">
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainmenu" aria-controls="mainmenu" aria-expanded="false" aria-label="Przełącznik nawigacji">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse " id="mainmenu">
					<ul class="navbar-nav mr-auto nav justify-content-center" style="margin:auto">
						<li><a href="mainMenu.php"><div class="option active">Menu główne</div> </a></li>
						<li><a href="addIncome.php"><div class="option">Dodaj przychód</div> </a></li>
						<li><a href="addExpense.php"><div class="option">Dodaj wydatek</div> </a></li>
						<li><a href="seeBills.php"><div class="option">Przeglądaj bilans</div></a></li>
						<li><a href="#"><div class="option">Ustawienia</div> </a></li>
						<li><a href="logOut.php"><div class="option">Wyloguj się</div> </a></li>
					</ul>
				</div>
			</nav>
		</div>
		<div  class="row justify-content-center ">
			<div class="col-10  col-xl-8" id="content" style="text-align:center" >
				<h3>Wydatki</h3>
				<canvas id="expenses"></canvas>
				<h3>Przychody</h3>
				<canvas id="incomes"></canvas>
				<script src="chartPie.js"></script>
			
				<div  style="margin-top:20px; text-align:center">
					<a href="seeBills.php"><div class="log_button">Nowy Bilans</div> </a>
				</div>
			</div>
		</div>	
	</div>
	
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>	
	<script src="js/bootstrap.min.js"></script>	
</body>
</html>





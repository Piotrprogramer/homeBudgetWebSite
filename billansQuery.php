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
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
	<script src="chartPie.js"></script>
	


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
						<li><a href="MenuGlowne.php"><div class="option">Menu główne</div> </a>			</li>
						<li><a href="DodajPrzychod.php"><div class="option">Dodaj przychód</div> </a>						</li>
						<li><a href="dodajWydatek.php"><div class="option">Dodaj wydatek</div> </a>		</li>
						<li><a href="przegladajBilans.php"><div class="option active">Przeglądaj bilans</div> </a></li>
						<li><a href="#"><div class="option">Ustawienia</div> </a>							</li>
						<li><a href="logOut.php"><div class="option">Wyloguj się</div> </a></li>
					</ul>
				</div>
			</nav>
		</div>


		<div  class="col-10  col-xl-8 row justify-content-center" id="content"  style="text-align:center">
			<?php
				if (isset($_SESSION['id'])) {
					$userId	= $_SESSION['id'];

					$date_start ;
					$date_end ;
					
					if( isset($_POST['date_start']) && isset($_POST['date_end'] )){
						$date_start = $_SESSION['date_start'];
						$date_end = $_SESSION['date_end'];
					}
					else{
						if($_POST['date'] == 'this_month'){
							$date_start = date("Y-m-01");
							$date_end = date("Y-m-d");
						}
						else if($_POST['date'] == 'last_month'){
							$date_start = date('Y-m-01', strtotime('last month'));
							$date_end = date("Y-m-01");
						}
						else if($_POST['date'] == 'this_year'){
							$date_start = date('Y-01-01', strtotime('last month'));
							$date_end = date("Y-12-31");	
						}
					}
					require_once 'database.php';
					
						$stmt = $db->prepare("SELECT category, SUM(amount)  FROM expenses WHERE userId=:userIdd AND date BETWEEN :start AND :end GROUP BY amount ORDER BY SUM(amount) DESC");
						
						$stmt->bindParam(':userIdd', $_SESSION['id']);
						$stmt->bindParam(':start', $date_start);
						$stmt->bindParam(':end', $date_end);
						$stmt->execute();
							
			?>

					
<!--				<?php	
							while ($row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT)) {
								$category = $row[0];
								$amount = $row[1];
								echo "<br>";
								echo $category;
								echo " -> ";
								echo $amount." zł";
							
							}	
			?>
-->

						
			<?php		
							$stmt = $db->prepare("SELECT income, SUM(amount)  FROM incomes WHERE userId=:userIdd AND date BETWEEN :start AND :end GROUP BY income ORDER BY SUM(amount) DESC");
							$stmt->bindParam(':userIdd', $_SESSION['id']);
							$stmt->bindParam(':start', $date_start);
							$stmt->bindParam(':end', $date_end);
							$stmt->execute();
											
							while ($row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT)) {
								$category = $row[0];
								$amount = $row[1];
								echo "<br>";
								echo $category;
								echo " -> ";
								echo $amount." zł";

							}
				}
			?>
						

				<div>
					<canvas id="myChart" style="width:100%"></canvas>
					<script type="text/javascript">jsFunction();</script>
				</div>
				<div>
					<canvas id="myChart1" style="width:100%"></canvas>
					<script type="text/javascript">jsFunction1();</script>
				</div>



					<div class="col-5  col-xl-5" style="margin-top:20px">
							<a href="przegladajBilans.php"><div class="log_button">Nowy Bilans</div> </a>
					</div>
		</div>
	</div>
	
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	


	<script src="js/bootstrap.min.js"></script>	
</body>
</html>





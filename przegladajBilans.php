<?php
session_start();
require_once 'database.php';

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

		<div class="row justify-content-center ">
			<div  class="col-10  col-xl-8" id="content" style="text-align:center">
				<form method="post" action="bilans.php">
					<div id="standard" class="choose_bill">
						<div>Wybierz datę</div>
						<div >
							<select  style="margin-left:10px" name="date" >
							  <option value="this_month" selected >	bieżący miesiąc		</option>
							  <option value="last_month">	poprzedni miesiąc	</option>
							  <option value="this_year">	bieżący rok			</option>
							</select>
						</div>
						
						<div  >
							<a href="javascript:SwapDivsWithClick('standard','precisely')">Wybierz dokłądną datę</a>
						</div>
						
						<div class="choose_bill" style="font-size: 10px">
							<input class="subbmit_button"  type="submit" style="font-size:12px; width:80%; max-width:300px" value="Pokaż bilans">
						</div>
						
					</div>	
				</form>	
				<form method="post" action="dokladnyBilans.php">
					<div id="precisely" class="choose_bill" style="display:none">
						<div>Wybierz datę</div>
						
						<div>
							<input name="date_start" type="date" value="<?php echo date("Y-m-01");?>">
							<input name="date_end" 	 type="date" value="<?php echo date("Y-m-d"); ?>">
						</div>
						
						<div  >
							<a href="javascript:SwapDivsWithClick('standard','precisely')"> Wybierz datę z listy</a>
						</div>
						
						<div class="choose_bill" style="font-size: 10px">
							<input class="subbmit_button"  type="submit" style="font-size:12px; width:80%; max-width:300px" value="Pokaż bilans">
						</div>
						
					</div>	
					
				</form>
				<div class="row justify-content-center choose_bill" style="<?php if($_SESSION['display_balance'] == false )echo "display:none"; else {"display:block"; $_SESSION['display_balance'] = false;}?>" >
				
				
					<div class="col-12  col-sm-6 statistics" >Przychody
					<?php	
						$sql = 'SELECT category, SUM(amount)  FROM expenses WHERE userId=75 GROUP BY category ORDER BY SUM(amount) DESC';
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
					?>
					</div>
					
					<div class="col-12  col-sm-6 statistics" >Wydatki
						<?php	
						$sql = 'SELECT income, SUM(amount)  FROM incomes WHERE userId=75 GROUP BY income ORDER BY SUM(amount) DESC';
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
					?>
					</div>
				</div>					
			</div>
		</div>	
	</div>
	
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	
	<script src="js/bootstrap.min.js"></script>	
</body>
</html>
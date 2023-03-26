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
	<title>Dodaj przychód</title>
	<meta name="description" content="Zadbaj o swój budżet" >
	<meta name="keywords" content="Budżet" >
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
		
		<div class="row justify-content-center ">
			<nav class="navbar navbar-light navbar-expand-lg col-10  col-xl-8" id="menu_bar">
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainmenu" aria-controls="mainmenu" aria-expanded="false" aria-label="Przełącznik nawigacji">
					<span class="navbar-toggler-icon"></span>
				</button>
			
				<div class="collapse navbar-collapse " id="mainmenu">
					<ul class="navbar-nav mr-auto nav justify-content-center" style="margin:auto">
						<li><a href="MenuGlowne.php"><div class="option">Menu główne</div> </a>			</li>
						<li><div class="option active">Dodaj przychód</div> 								</li>
						<li><a href="dodajWydatek.php"><div class="option">Dodaj wydatek</div> </a>		</li>
						<li><a href="przegladajBilans.php"><div class="option">Przeglądaj bilans</div> </a></li>
						<li><a href="#"><div class="option">Ustawienia</div> </a>							</li>
						<li><a href="logOut.php"><div class="option">Wyloguj się</div> </a>					</li>
					</ul>
				</div>
			</nav>
		</div>

		<div class="row justify-content-center ">
			<div  class="col-10 col-xl-8" id="content" style="text-align:center">
				<form method="post" action="saveIncome.php">
					<div class="choose_bill">	
						<div > Data przychodu <input style="margin-left:10px" type="date" name="date" value="<?php echo date("Y-m-d"); ?>" min="2020-01-01"></div>
					</div>
					
					<div class="row justify-content-center choose_bill">
						<label for="kwota" >Kwota: </label>
						<div style="margin-left:10px">
							<input type="number" name="amount" value="0.01" step="0.01" min="0.01">
						</div>
					</div>
					
					<div class="choose_bill">	Przychód:
						<select style="margin-left:10px" name="income">
							<option value="wynagrodzenie"   selected> Wynagrodzenie 	</option>
							<option value="odsetki"  > Odsetki bankowe 			</option>
							<option value="allegro"  > Sprzedaż na allegro 		</option>
							<option value="gielda"  > Giełda 					</option>
							<option value="Inne"  > Inne 					</option>
						</select>
					</div>
				
					<div class="choose_bill">	
						<div><label for="komentarz">Komentarz (opcjonalnie) </label></div>
							
						<div ><textarea name="comment" rows="2" cols="35" maxlength="80"  style="resize:none" ></textarea></div>
					</div>	
									
					<div class="choose_bill" style="font-size: 10px">
						<input class="subbmit_button"  type="submit" style="font-size:12px; width:80%; max-width:300px" value="Dodaj przychód">
					</div>
				</form>	
			</div>
		</div>	
	</div>

	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	
	<script src="js/bootstrap.min.js"></script>
</body>
</html>
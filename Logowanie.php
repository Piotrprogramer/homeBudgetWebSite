<?php
	session_start();
?>	
	
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" >
	<title>Menu główne</title>
	<meta name="description" content="Zadbaj o swój budżet" >
	<meta name="keywords" content="Budżet" >
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
		
		<div class="row justify-content-center">
			<div id="register_frame" >
					<form method="post" action="load.php">
						<input type="text" name="login" placeholder="login" onfocus="this.placeholder=''" onblur="this.placeholder='login'" required 
						value=
						"<?php
							if (isset($_SESSION['given_login'])){
							echo $_SESSION['given_login'];
							unset($_SESSION['given_login']);}
						?>" >
						
						<input type="password" name="password" placeholder="hasło" onfocus="this.placeholder=''" onblur="this.placeholder='hasło'" required>
						
						<?php
							if (isset($_SESSION['wrong_pass'])){
							echo '<div style="color: red">Podany użytkownik nie istnieje</div>';
							unset($_SESSION['wrong_pass']);
							}
						?>
						
						<input type="submit" value="Zaloguj się">
					
						<a href="Index.php"><div class="log_button" style="margin-top:10px">Powrót</div> </a>
						
					</form>
			</div>
		</div>
		
	</div>
	
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	
	<script src="js/bootstrap.min.js"></script>
</body>
</html>
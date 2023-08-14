<?php


require 'includes/header.php';
//require 'classes/User.php';



if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $error = array();

    $conn = require 'db.php';

    if (User::authenticate($conn, $_POST['username'], $_POST['password'])) {
        
        Auth::login();

        $_SESSION['userId'] = User::getUserIdOrNull($conn, $_POST['username'], $_POST['password']);

        Url::redirect('/index.php');

    } else {
        array_push($error,"login incorrect");
    }
}
if(isset($error)){
    ?><div style="color:red">  <?php foreach($error as $er) {echo $er. "<br>"; } ?>  </div>  <?php
}
?>

<form method="post">

    <div class="form-group">
        <label for="username">Username</label>
        <input name="username" id="username" class="form-control">
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" class="form-control">
    </div>

    <button class="btn">Log in</button>

</form>

<?php require 'includes/footer.php'; ?>
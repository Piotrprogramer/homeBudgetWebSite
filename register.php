<?php

require 'includes/header.php';
//require 'classes/User.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $errors = array();

    $conn = require 'db.php';

    if (User::isUserNameAvailable($conn, $_POST['username'])) {

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "email is not valid");
        } else {
            if(isset($_POST['password']) && $_POST['password'] != ''){
                if ($_POST['password'] != $_POST['password_repeat']) {
                    array_push($errors, "Passwords not match");
                } else {
                    $hashPassword = Auth::hashPassword($_POST['password']);

                    User::registerNewUser($conn, $_POST['username'], $hashPassword, $_POST['email']);

                    $userId = User::getUserIdOrNull($conn, $_POST['username'], $hashPassword);

                    User::copyDefaultIncomes($conn, $userId);

                    Url::redirect('/index.php');
                }
            } 
            else array_push($errors, "Password is not set");
        }
    } else
        array_push($errors, "Username is already used");
}

if (isset($errors)) {
    ?>
    <div style="color:red">
        <?php foreach ($errors as $er) {
            echo $er . "<br>";
        } ?>
    </div>
    <?php
}
?>

<form method="post">

    <div class="form-group">
        <label for="username">Username</label>
        <input name="username" id="username" class="form-control" value="<?php if (isset($_POST["username"]))
            echo $_POST["username"]; ?>">
    </div>

    <div class="form-group">
        <label for="email">e-mail</label>
        <input type="email" name="email" id="email" class="form-control" value="<?php if (isset($_POST["email"]))
            echo $_POST["email"]; ?>">
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" class="form-control">
    </div>

    <div class="form-group">
        <label for="password">Confirm password</label>
        <input type="password" name="password_repeat" id="password_repeat" class="form-control">
    </div>

    <button class="btn">Create account</button>

</form>

<?php require 'includes/footer.php'; ?>
<?php require 'includes/header.php'; ?>



<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $error = array();

    $conn = require 'db.php';
    if (!$_POST["date_start"] != null)
        array_push($error, "Wybierz datę rozpoczęcia okresu");
    if (!$_POST["date_end"] != null)
        array_push($error, "Wybierz datę zakończenia okresu");

    if (empty($error)) {
        $income_bills = User::getIncomeBills($conn, $_SESSION['userId'], $_POST["date_start"], $_POST["date_end"]);
        foreach ($income_bills as $income) {
            print_r($income);
        }
        if($income_bills == null)  array_push($error, "Brak danych do wyświetlenia");
    } 
    if(!empty($error)) {
        ?>
        <div style="color:red">
            <?php foreach ($error as $er) {
                echo $er . "<br>";
            } ?>
        </div>
    <?php
    }
}

?>

<div class="d-flex justify-content-center">
    <form method="post">
        <label for="published_at">Wybierz okres</label>
        <div class="form-group ">
            <label for="date_start">Początek</label>
            <input type="datetime-local" name="date_start" class="form-control"
                value="<?php if (isset($_POST["date_start"]))
                    echo $_POST["date_start"]; ?>">
        </div>

        <div class="form-group">
            <label for="date_end">Koniec</label>
            <input type="datetime-local" name="date_end" class="form-control"
                value="<?php if (isset($_POST["date_end"]))
                    echo $_POST["date_end"]; ?>">
        </div>

        <button class="btn">Pokaż bilans</button>

    </form>
</div>

<?php require 'includes/footer.php'; ?>
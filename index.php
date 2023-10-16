<?php @include('data.php');
    if($_SESSION) {
        $bill = $_SESSION['bill'];
        if(empty($bill)) {
            $bill = 0;
        }
        if ($_SESSION['valid'] != null && $_SESSION['valid'] != "true"){
            $errMsg = "Invalid Input";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Electricity bill</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="container">
            <header class="flex">
                <img src="lightning_icon.png" alt="lightning">
                <h1>Electricity Bill Calculator</h1>
                <img src="lightning_icon.png" alt="lightning">
            </header>
            <div class="main-content">
                <form action="data.php" method="POST">
                    <div class="form-group">
                        <label for="usage">Usage:</label>
                        <input type="text" name="usage">
                    </div>
                    <input type="submit" name="calculate" class="calculate">
                    <button name="reset" class="reset">Reset</button>
                </form>
                <?php if(isset($errMsg)) {
                    echo '<h3 class="error">' . $errMsg . '</h3>';
                } ?>
                <h3>Your electricity bill is <?= $bill; ?></h3>
            </div>
        </div>
    </body>
</html>
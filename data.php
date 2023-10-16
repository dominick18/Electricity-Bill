<?php

    //Start sessie
    session_start();

    //Sanitize de waarde van $_POST['usage'] en check of geldig,
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $input = sanitizeInput($_POST['usage']);
        if (isInputValid($input)){
            $units = $input;
            $_SESSION['valid'] = "true";
        }
        else{
            $units = null;
            $_SESSION['valid'] = "false";
        }
        $_SESSION['bill'] = $bill;

        header('location: index.php');
    }

    $bill = 0;
    $limits = [50, 100, 100, 0];
    $tariffs = [3.5, 4.0, 5.2, 6.5];

    function calculateCostsForTier($tier, $usage, $limits, $tariffs){
        $internal_tier = $tier - 1;
        $cost = 0;
    
        if ($usage >= $limits[$internal_tier] && $tier <= 3){
            $cost += $limits[$internal_tier] * $tariffs[$internal_tier]; //Als het gebruik groter is of gelijk aan de tier, zet dan de kosten ($cost) gelijk aan de grootte van de tier($lim) vermenigvuldigd met het tarief($tar)
        }
        else{
            $cost += $usage * $tariffs[$internal_tier]; //Als het gebruik kleiner is dan die tier, geef dan gewoon het gebruik ($usage) vermenigvuldigd met het tarief ($tar)
        }
        return $cost;
    }
    
    function isInputValid($input){
        if (is_numeric($input) && $input >= 0){
            return true;
        }
        else{
            return false;
        }
    }

    function sanitizeInput($input){
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }


    if (isset($units)){        
        $tier = 1;
        
        while ($tier <= 4 && $units > 0){
            $tiercost = calculateCostsForTier($tier, $units, $limits, $tariffs);
            $bill += $tiercost;
            $units -= $limits[$tier - 1];
            $tier++;
        }
    
        $_SESSION['bill'] = $bill;
        header('location: index.php');
    }

    if(isset($_POST['reset'])) {
        session_destroy();
        unset($bill);
        header('location: index.php');
    }
?>
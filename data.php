<?php

    #Start sessie
    session_start();
    //$_SESSION['valid'] = "c";

    #Sanitize de waarde van $_POST['usage'] en check of geldig,
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

    function calculateCostsForTier($tier, $usage, $lim, $tar){
        $internal_tier = $tier - 1;
        $cost = 0;
    
        if ($usage >= $lim[$internal_tier]){
            $cost += $lim[$internal_tier] * $tar[$internal_tier];
        }
        else{
            $cost += $usage * $tar[$internal_tier];
        }
        $usage -= $lim[$internal_tier];
    
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
        //Main Loop
        $tier = 1; //Begin bij de eerste tier
        
        while ($tier <= 4 && $units > 0){
            $tiercost = calculateCostsForTier($tier, $units, $limits, $tariffs);
            $bill += $tiercost; //Voeg de kosten van desbetreffende tier toe aan de $bill variabele
            $units -= $limits[$tier - 1];  //Haal de limiet van de energieunits af, dan kan het programma bepalen of deze naar een volgende tier moeten
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
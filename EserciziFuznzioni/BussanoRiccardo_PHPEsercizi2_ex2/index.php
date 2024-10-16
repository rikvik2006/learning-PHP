<?php

declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">

    <style>
        .font-size {
            font-size: 2rem;
        }

        .error {
            color: red;
        }
    </style>

    <title>ALU</title>
</head>

<body>

    <?php
    function alu(float $number1, float $number2, string $operation): float | string
    {
        $result = 0;
        switch ($operation) {
            case "+":
                $result = $number1 + $number2;
                break;
            case "-":
                $result = $number1 - $number2;
                break;
            case "*":
                $result = $number1 * $number2;
                break;
            case "/":
                if ($number2 == 0) {
                    $result = "Error Division by zero";
                } else {
                    $result = $number1 / $number2;
                }
                break;
        }

        return $result;
    }
    ?>
    <div class="container column font-size">
        <?php
        $result = alu(5, -3, "/");

        if ($result == "Error Division by zero") {
            echo "<div class='error'>Erorr: Division by zero</div>";
        } else {
            echo "<div>Il risultato è pari a: $result</div>";
        }
        ?>
    </div>
</body>

</html>
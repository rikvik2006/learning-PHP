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

    <title>Somma</title>
</head>

<body>
    <?php
    function number_sum(float $number1, float $number2): float
    {
        $sum = $number1 + $number2;
        return $sum;
    }
    ?>
    <div class="container row font-size">
        <?php
        $sum = number_sum(-5, 7.2);
        echo "La somma è pari a $sum";
        ?>
    </div>
</body>

</html>
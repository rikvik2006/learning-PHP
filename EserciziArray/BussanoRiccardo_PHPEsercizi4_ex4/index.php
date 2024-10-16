<?php

declare(strict_types=1);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">

    <!-- Stile in caso mancasse il file CSS -->
    <style>
        .font-size {
            font-size: 2rem;
        }

        .error {
            color: red;
        }

        .image {
            max-height: 50rem;
        }

        .text {
            mix-blend-mode: difference;
        }
    </style>

    <title>Lunedì nero</title>
    <?php
    // 1. Restituisce il il nome del giorno sotto forma di stringa es: Monday
    // 2. Avrei potuto utilizzare il carattere "w" come argomento della funziona e mi avrebbe restituito una associazione numerica tra giorni della settimana e numeri es: 0 => Monday ...
    // però volevo utilizzare gli array associativi quindi ho utilizzato il modo 1

    $day_colors = array(
        "Monday" => "black",
        "Tuesday" => "#2d2d2d",
        "Wednesday" => "#4d4d4d",
        "Thursday" => "#6d6d6d",
        "Friday" => "white",
        "Saturday" => "yellow",
        "Sunday" => "#8d8d8d"
    )
    ?>

    <style>
        body {
            background-color: <?php echo $day_colors[date("l")] ?>;
        }
    </style>
</head>

<body>
    <div class="container column font-size">
        <p class="text">Oggi è <b><?php echo date("l") ?></b></p>
    </div>
</body>

</html>
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
    </style>

    <title>Tre Parametri</title>
</head>

<body>
    <?php
    function tre_parametri(float ...$x): float | string
    {
        // Controlliamo la lunghezza del array di float
        $length = count($x);
        $result = 0;
        // Se è maggiore o uguale a 3 operiamo sul 3° argomento
        if ($length >= 3) {
            $result = $x[2] ** 3;
        } elseif ($length == 2) {
            $result = $x[1] ** 4;
        } elseif ($length == 1) {
            $result = log10($x[0]);
        } else {
            // Altrimenti in questo caso non sono stati inseriti parametri, quindi restituiamo una stringa con un errore
            $result = "Insert at least one parameter";
        }

        return $result;
    }
    ?>

    <div class="container column font-size">
        <?php
        $result = tre_parametri(100, 2, 3);
        // Controlliamo se è presente un errore
        if ($result == "Insert at least one parameter") {
            echo "<div class='error'>ERORR: Insert at least one parameter</div>";
        } else {
            echo "<div>Il risultato è pari a: $result</div>";
        }
        ?>
    </div>
</body>

</html>
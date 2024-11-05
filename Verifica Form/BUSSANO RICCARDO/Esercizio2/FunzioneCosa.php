<?php

declare(strict_types=1);

function sanitize_input(string $data): string
{
    return htmlspecialchars(stripslashes(trim($data)));
}

function FunzioneCosa(float $peso, float $altezza)
{
    // Controlliamo che l'utente inserisca dei parametri numeric
    if (!is_numeric($peso) || !is_numeric($altezza)) {
        throw new Exception("Inserisci dei parametri numerici");
    }

    $ARR_peso = [];
    // Inseriamo 10 elementi al interno del array
    for ($i = 0; $i < 10; $i++) {
        $random_number = random_int(0, 100);
        // ******* Test *********
        // $random_number = 0;

        // if ($i > 4) {
        //     $random_number = random_int(0, 100);
        // }
        // ******* Fine test *********

        // Aggiungiamo l'elemento
        $ARR_peso[$i] = $peso * 10 + $random_number;
    }

    // print_r($ARR_peso);

    $altezza_found_times = 0;
    // Cerchiamo l'altezza al interno del array
    foreach ($ARR_peso as $number) {
        if ($number == $altezza) {
            $altezza_found_times++;
        }
    }

    // Restituiamo i valori in base alla richista
    if ($altezza_found_times == 0) {
        return "NON TROVATO";
    } else {
        return $altezza_found_times;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funzione Cosa</title>
</head>

<body>
    <div>
        <?php
        // Chiamiamo la funzione cosa
        $returned_value = FunzioneCosa(1, 10);
        echo "<div>$returned_value</div>";
        ?>
    </div>
</body>

</html>
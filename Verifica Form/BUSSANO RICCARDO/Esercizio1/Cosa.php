<?php

declare(strict_types=1);

// Se non si raggiunge il file attraverso un post request redirectiamo verso il file Cosa_form.html
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: Cosa_form.html");
    exit;
}

// Funzione per sanitizzare gli input
function sanitize_input(string $data): string
{
    return htmlspecialchars(stripslashes(trim($data)));
}

// Se non sono impostati i valori riportiamo l'utente sul file Cosa_form.html
if (!isset($_POST["a"]) || !isset($_POST["b"])) {
    header("Location: Cosa_form.html");
    exit;
}

if (empty($_POST["a"]) || empty($_POST["b"])) {
    echo "<div>Inserisci dei valori</div>";
} else {

    // Sanitizziamo e facciamo il casting dei dati
    $altezza = (float)sanitize_input($_POST["a"]);
    $base = (float)sanitize_input($_POST["b"]);

    // Controalliamo se i valori sono uguali a zero e prediamo provvedimenti
    if ($altezza == 0 || $base == 0) {
        if ($altezza == 0) {
            $altezza_error = "<div>L'altezza deve essere diversa da zero</div>";
        }

        if ($base == 0) {
            $base_error = "<div>La base deve essere diversa da zero</div>";
        }
    } else {
        // Calcoliamo l'ouput
        if ($altezza / $base > $base / $altezza) {
            $output = "<div>peso</div>";
        } else {
            $output = "<div>altezza</div>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cosa.php</title>
</head>

<body>
    <div>
        <!-- Stampaimo i relativi errori o l'output -->
        <?php
        if (isset($altezza_error)) {
            echo $altezza_error;
        }

        if (isset($base_error)) {
            echo $base_error;
        }

        if (isset($output)) {
            echo $output;
        }
        ?>
    </div>
</body>

</html>
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
    </style>

    <title>Provincie form</title>
</head>

<body>
    <?php
    $array_provincie = array(
        "TO" => "Torino",
        "VC" => "Vercelli",
        "NO" => "Novara",
        "CN" => "Cuneo",
        "AT" => "Asti",
        "AL" => "Alessandria",
        "BI" => "Biella",
        "VB" => "Verbano-Cusio-Ossola"
    );

    function stampa_provincie(array $array_provincie): string
    {
        $provincie_options = "";
        foreach ($array_provincie as $sigla => $nome) {
            $provincie_options .= "<option value='$sigla'>$nome</option>";
        }

        return $provincie_options;
    }
    ?>

    <div class="container column font-size">
        <form>
            <label for="email">Email</label>
            <input type="email" name="email" id="email">
            <label for="password">Password</label>
            <input type="password" name="password" id="password">
            <label for="regione">Regione</label>
            <select name="regione" id="select">
                <option value="piemonte">Piemonte</option>
            </select>
            <label for="provincia">Seleziona Provincia</label>
            <select name="provincia" id="provincia">
                <?php
                echo stampa_provincie($array_provincie);
                ?>
            </select>
            <input type="submit">
        </form>
    </div>
</body>

</html>
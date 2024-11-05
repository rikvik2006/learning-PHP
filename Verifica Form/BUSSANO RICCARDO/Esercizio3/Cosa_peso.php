<?php

// Crezione array associativo
$ARR_Cosa_peso = ["b" => 3, "c" => 4, "f" => 2, "a" => 1, "d" => 5, "j" => 9, "z" => 0, "y" => 3, "p" => 4, "l" => 6];
// Numero elementi array associativo
// echo count($ARR_Cosa_peso);
// echo "<br>";

ksort($ARR_Cosa_peso);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cosa Peso!</title>
</head>

<body>
    <!-- Creazione tabella -->
    <table>
        <thead>
            <tr>
                <th>Chiave</th>
                <th>Valore</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
            </tr>
            <?php
            // Per ogni elmento del array associativo prendiamo la chiave e il valore e stapiamole in modo che siano incolonnate come chiave e valore
            foreach ($ARR_Cosa_peso as $key => $value) {
                echo "<tr>";
                echo "<td>$key</td>";
                echo "<td>$value</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</body>

</html>
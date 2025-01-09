<?php

declare(strict_types=1);

require_once "../php/databaseConnection.php";

function sanitize_input(string $data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function display_row(array $row)
{
    $output = "";
    foreach ($row as $data) {
        $output .= "<td>$data</td>";
    }

    return $output;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] != "GET") {
    header("Location: ../filtroRappresentanti.html");
    exit;
}

// Input: minSales: type float,  maxSales: type float
$minSales = sanitize_input($_GET["minSales"]);
$maxSales = sanitize_input($_GET["maxSales"]);

if (!isset($minSales) || !isset($maxSales)) {
    header("Location: ../filtroRappresentanti.html");
    exit;
}
// } else {
// Tests
//     echo "<h3>Valori sanitizati (di tipo stringa dato che arrivano da un query parameters)</h3>";
//     echo var_dump($minSales);
//     echo "<br>";
//     echo var_dump($maxSales);
//     echo "<h3>Valori trasformati in float (output vardump)</h3>";
//     echo var_dump((float)$minSales);
//     echo "<br>";
//     echo var_dump((float)$maxSales);
//     echo "<h3>Echo valori float</h3>";
//     echo $minSales;
//     echo "<br>";
//     echo $maxSales;
//     echo "<br>";
// }

if (!is_numeric($minSales) || !is_numeric($maxSales)) {
    $error = "Inserisci dei valori numerici";
} else {
    $minSales = (float)$minSales;
    $maxSales = (float)$maxSales;

    if ($minSales < 0 || $maxSales < 0) {
        $error = "Inserisci un range positivo";
    } else {
        if ($minSales > $maxSales) {
            $error = "Inserisci il valore minimo minore del valore massimo";
        } else {
            $sqlQuery = "SELECT * FROM Rappresentante WHERE UltimoFatturato BETWEEN $minSales AND $maxSales";
            $result = $conn->query($sqlQuery);

            if ($result->num_rows == 0) {
                $error =  "Nessun risultato";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="../style/style.css">
</head>

<body>
    <?php if (!empty($error)): ?>
        <div class="errorContainer">
            <?= $error ?>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <th>Id</th>
                <th>Nome</th>
                <th>Cognome</th>
                <th>UltimoFatturato</th>
                <th>Regione</th>
                <th>Provincia</th>
                <th>PercentualeProvvigione</th>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <?php echo display_row($row); ?>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
    <div>
        <a href="../filtroRappresentanti.html">Indietro</a>
        <a href="../index.html">Home page</a>
    </div>
</body>

</html>
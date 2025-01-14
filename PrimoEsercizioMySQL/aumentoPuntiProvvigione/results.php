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

$error = "";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: ./index.php");
    exit;
}

if (isset($_POST["region"])) {
    $region = $_POST["region"];
    $sanitizedRegion = sanitize_input($region);

    $getRegionSqlQuery = "SELECT Regione FROM Rappresentante GROUP BY Regione";
    $currentRegionResult = $conn->query($getRegionSqlQuery);

    if ($currentRegionResult->num_rows != 0) {
        $region_found = false;
        while ($row = $currentRegionResult->fetch_assoc()) {
            if ($row["Regione"] == $region) {
                $region_found = true;
                break;
            }
        }

        if ($region_found) {
            $incresePointsSqlQuery = "UPDATE Rappresentante SET PercentualeProvvigione = PercentualeProvvigione + 2 WHERE UltimoFatturato > 1000 AND Regione = '$sanitizedRegion' AND PercentualeProvvigione <= 98";

            $conn->query($incresePointsSqlQuery);
        } else {
            $error = "Non c'è nessun rappresentante in quella regione, inserisci una regione valida";
        }
    } else {
        $error = "Non c'è nessun rappresentante in delle regioni";
    }
} else {
    $error = "Specifica una regione";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aumento provvigione</title>

    <link rel="stylesheet" href="../style/style.css">
</head>

<body>
    <?php if (empty($error)): ?>
        <div>Provvigione aumentanta per tutti i rappresentanti con ultima vendità superiore a 1000 euro provenienti dalla regione <?= sanitize_input($region) ?> e con una percentuale provvigione minore o uguale a 98</div>
    <?php else: ?>
        <div class="errorContainer"><?= $error ?></div>
    <?php endif ?>
    <div>
        <a href="./index.php">Indietro</a>
        <a href="../index.html">Home page</a>
    </div>
</body>

</html>
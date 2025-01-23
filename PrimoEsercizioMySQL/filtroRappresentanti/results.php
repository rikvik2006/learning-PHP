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
    header("Location: ./index.html");
    exit;
}

if (!isset($_GET["minSales"]) || !isset($_GET["maxSales"])) {
    header("Location: ./index.html");
    exit;
}

$minSales = sanitize_input($_GET["minSales"]);
$maxSales = sanitize_input($_GET["maxSales"]);

if (!isset($minSales) || !isset($maxSales)) {
    header("Location: ./index.html");
    exit;
}

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
    <title>Risultati Filtro Rappresentanti</title>
    <link rel="stylesheet" href="../style/style.css">
</head>

<body>
    <div class="pageContainer">
        <div class="sideBar">
            <div class="header">
                <div class="logoContaner">
                    <img class="cogIcon" src="../img/cogIcon.svg" />
                    <img src="../img/marcelmango.jpg" class="logo" />
                </div>
                <div class="greeting">
                    <div class="salut">Ciao</div>
                    <div class="name">Riccardo</div>
                </div>
            </div>
            <div class="linksContainer">
                <a class="link" href="../">
                    <div>Home</div>
                </a>
                <a class="link" href="./">
                    <div>Filtro Rappresentanti</div>
                </a>
                <a class="link" href="../aumentoPuntiProvvigione/">
                    <div>Provvigione</div>
                </a>
                <a class="link" href="../aggiungiRappresentante/">
                    <div>Aggiungi rappresentante</div>
                </a>
                <a class="link" href="../eliminaRappresentante/">
                    <div>Elimina Rappresentante</div>
                </a>
                <a class="link" href="../visualizzaTabella/">
                    <div>Visualizza Rappresentanti</div>
                </a>
            </div>
        </div>
        <div class="mainContent">
            <div class="table-container">
                <?php if (!empty($error)): ?>
                    <div class="error"><?= $error ?></div>
                <?php else: ?>
                    <table class="rappresentanti-table">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Nome</th>
                                <th>Cognome</th>
                                <th>UltimoFatturato</th>
                                <th>Regione</th>
                                <th>Provincia</th>
                                <th>PercentualeProvvigione</th>
                            </tr>
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
            </div>
        </div>
    </div>
</body>

</html>
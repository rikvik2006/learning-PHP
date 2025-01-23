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
                <a class="link" href="../filtroRappresentanti/">
                    <div>Filtro Rappresentanti</div>
                </a>
                <a class="link" href="./">
                    <div>Provvigione</div>
                </a>
                <a class="link" href="../aggiungiRappresentante/">
                    <div>Aggiungi rappresentante</div>
                </a>
                <a class="link" href="../eliminaRappresentante/">
                    <div>Elimina Rappresentante</div>
                </a>
                <a class="link" href="../visualizzaRappresentanti/">
                    <div>Visualizza Rappresentanti</div>
                </a>
            </div>
        </div>
        <div class="mainContent">
            <div class="centerContent column">
                <div class="formContainer">
                    <div class="formHeader">
                        <h1>Risultati Aumento Provvigione</h1>
                    </div>
                    <?php if (empty($error)): ?>
                        <div class="success" style="padding: 1rem 0;">Provvigione aumentata per tutti i rappresentanti con ultima vendità superiore a 1000 euro provenienti dalla regione <?= sanitize_input($region) ?> e con una percentuale provvigione minore o uguale a 98</div>
                    <?php else: ?>
                        <div class="error" style="padding: 1rem 0;"><?= $error ?></div>
                    <?php endif ?>
                    <div class="navigationLinks">
                        <a href="./index.php" class="button">Indietro</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
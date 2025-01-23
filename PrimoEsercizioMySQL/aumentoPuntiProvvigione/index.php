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

$getRegionSqlQuery = "SELECT Regione FROM Rappresentante GROUP BY Regione";
$result = $conn->query($getRegionSqlQuery);

if ($result->num_rows == 0) {
    $error = "Non ci sono rappresentanti da nessuna regione";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Seconda Funzionalità - Aumento punti provvigione</title>

    <link rel="stylesheet" href="../style/style.css" />
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
                        <h1>Aumento Punti Provvigione</h1>
                    </div>
                    <?php if (empty($error)): ?>
                        <form action="./results.php" method="post">
                            <div class="inputContainer">
                                <label for="region_selector">Seleziona una regione</label>
                                <select name="region" id="region_selector" required>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <option value="<?= sanitize_input($row["Regione"]) ?>"><?= sanitize_input($row["Regione"]) ?></option>
                                    <?php endwhile ?>
                                </select>
                            </div>
                            <div>
                                <button class="button submit" type="submit">Aumenta punti provvigione</button>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="error"><?= $error ?></div>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
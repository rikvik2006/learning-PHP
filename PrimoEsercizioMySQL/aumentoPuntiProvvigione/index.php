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
    <div>
        <?php if (empty($error)): ?>
            <form action="./results.php" method="post">
                <label for="region_selector">Seleziona una regione</label>
                <select name="region" id="region_selector">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <option value="<?= sanitize_input($row["Regione"]) ?>"><?= sanitize_input($row["Regione"]) ?></option>
                    <?php endwhile ?>
                </select>
                <button type="submit">Aumenta punti provvigione</button>
            </form>
            <div><a href="../index.html">Home page</a></div>
        <?php else: ?>
            <div class="errorContainer"><?= $error ?></div>
        <?php endif ?>
    </div>
</body>

</html>
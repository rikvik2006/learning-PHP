<?php

declare(strict_types=1);

require_once __DIR__ . "/../php/databaseConnection.php";
require_once __DIR__ . "/../utils/functions.php";

// Fetch all representatives
$queryRappresentanti = "SELECT * FROM Rappresentante";
$resultRappresentanti = $conn->query($queryRappresentanti);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="stylesheet" href="../style/style.css" />

    <title>Visualizza Rappresentanti</title>
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
                <a class="link" href="../aumentoPuntiProvvigione/">
                    <div>Provvigione</div>
                </a>
                <a class="link" href="../aggiungiRappresentante/">
                    <div>Aggiungi rappresentante</div>
                </a>
                <a class="link" href="../eliminaRappresentante/">
                    <div>Elimina Rappresentante</div>
                </a>
                <a class="link" href="./">
                    <div>Visualizza Rappresentanti</div>
                </a>
            </div>
        </div>
        <div class="mainContent">
            <div class="table-container">
                <?php if ($resultRappresentanti->num_rows > 0): ?>
                    <table class="rappresentanti-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Cognome</th>
                                <th>Ultimo Fatturato</th>
                                <th>Regione</th>
                                <th>Provincia</th>
                                <th>Percentuale Provvigione</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $resultRappresentanti->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['ID']) ?></td>
                                    <td><?= htmlspecialchars($row['Nome']) ?></td>
                                    <td><?= htmlspecialchars($row['Cognome']) ?></td>
                                    <td><?= htmlspecialchars($row['UltimoFatturato'] ?? 'null') ?></td>
                                    <td><?= htmlspecialchars($row['Regione']) ?></td>
                                    <td><?= htmlspecialchars($row['Provincia']) ?></td>
                                    <td><?= htmlspecialchars($row['PercentualeProvvigione']) ?>%</td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-table">
                        Nessun rappresentante trovato nel database.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>
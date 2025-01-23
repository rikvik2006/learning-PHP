<?php

declare(strict_types=1);

require_once __DIR__ . "/../php/databaseConnection.php";
require_once __DIR__ . "/../utils/functions.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];
    $success = "";

    if (isset($_POST["id_rappresentante"])) {
        $id_rappresentante = $_POST["id_rappresentante"];

        if (!is_numeric($id_rappresentante)) {
            $errors["id_rappresentante"] = "Inserisci un ID numerico valido";
        } elseif ($id_rappresentante <= 0) {
            $errors["id_rappresentante"] = "L'ID deve essere un numero positivo";
        }

        if (empty($errors)) {
            $id_rappresentante = sanitize_input($_POST["id_rappresentante"]);

            // Check if the representative exists
            $checkRappresentanteQuery = "SELECT * FROM Rappresentante WHERE ID = $id_rappresentante";
            $checkRappresentanteResult = $conn->query($checkRappresentanteQuery);

            if ($checkRappresentanteResult->num_rows > 0) {
                $rappresentanteData = $checkRappresentanteResult->fetch_assoc();
                $nome = $rappresentanteData['Nome'];
                $cognome = $rappresentanteData['Cognome'];

                // Delete the representative
                $deleteQuery = "DELETE FROM Rappresentante WHERE ID = $id_rappresentante";
                $conn->query($deleteQuery);

                $success = "Il rappresentante $nome $cognome (ID: $id_rappresentante) è stato eliminato";
            } else {
                $errors["general_error"] = "Nessun rappresentante trovato con questo ID";
            }
        }
    } else {
        $errors["general_error"] = "Inserisci l'ID del rappresentante";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="stylesheet" href="../style/style.css" />

    <title>Elimina Rappresentante</title>
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
                <a class="link" href="./">
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
                        <h1>Elimina Rappresentante</h1>
                    </div>
                    <form action="<?= sanitize_input($_SERVER["PHP_SELF"]) ?>" method="post">
                        <div class="inputContainer">
                            <label for="id_rappresentante">ID Rappresentante</label>
                            <input type="number" id="id_rappresentante" name="id_rappresentante" placeholder="1" min="1" required>
                            <?php if (isset($errors["id_rappresentante"])): ?>
                                <div class="error"><?= sanitize_input($errors["id_rappresentante"]) ?></div>
                            <?php endif ?>
                        </div>
                        <?php if (isset($errors["general_error"])): ?>
                            <div class="error"><?= sanitize_input($errors["general_error"]) ?></div>
                        <?php elseif (!empty($success)): ?>
                            <div class="success"><?= sanitize_input($success) ?></div>
                        <?php endif ?>

                        <div>
                            <button class="button submit" type="submit">Elimina</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
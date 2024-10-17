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

    <title>Registrati</title>
</head>

<body>
    <?php
    $output = "";
    $render_error = "";
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (isset($_GET["error"])) {
            $error = $_GET["error"];
            $error = htmlspecialchars($error);
            if ($error == "passswordNotMatch") {
                $render_error = "<p class='error'>Le password non corrispondono</p>";
            } else if ($error == "policyNotAccepted") {
                $render_error = "<p class='error'>Devi accettare le condizioni di utilizzo del sito web</p>";
            }
        }
    }
    ?>

    <div class="container column">
        <form action="<?php echo htmlspecialchars("./account.php"); ?>" method="POST">
            <div class="inputContainer">
                <label for="username">Username</label>
                <input class="text" type="text" name="username" id="username">
            </div>

            <div class="inputContainer">
                <label for="password">Password</label>
                <input class="text" type="password" name="password" id="password">
            </div>

            <div class="inputContainer">
                <label for="repeatPassword">Ripeti Password</label>
                <input class="text" type="password" name="repeatPassword" id="repeatPassword">
                <?php echo $render_error; ?>
            </div>

            <div class="inputContainer">
                <div>Condizioni</div>
                <div class="policy">
                    <div>
                        <p>Benvenuti sul nostro sito web. Utilizzando questo sito, accetti di rispettare le seguenti condizioni:</p>
                        <ul>
                            <li>Non utilizzare il sito per scopi illegali o non autorizzati.</li>
                            <li>Non condividere contenuti offensivi, diffamatori o inappropriati.</li>
                            <li>Rispetta la privacy degli altri utenti e non raccogliere informazioni personali senza consenso.</li>
                            <li>Non tentare di compromettere la sicurezza del sito o di accedere a dati riservati.</li>
                            <li>Il contenuto del sito è protetto da copyright e non può essere riprodotto senza autorizzazione.</li>
                        </ul>
                        <p>Ci riserviamo il diritto di modificare queste condizioni in qualsiasi momento. Continuando a utilizzare il sito, accetti eventuali modifiche.</p>
                    </div>
                </div>
            </div>
            <div class="policy-allow">
                <input type="checkbox" name="policy" id="policy">
                <p>Accetto le condizioni di utilizzo del sito web</p>
            </div>

            <button type="submit">Registrati</button>
        </form>
    </div>
</body>

</html>
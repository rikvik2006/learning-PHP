<?php

declare(strict_types=1);

require_once __DIR__ . "/../php/databaseConnection.php";
require_once __DIR__ . "/../utils/functions.php";

$regions = array(
    'Abruzzo',
    'Basilicata',
    'Calabria',
    'Campania',
    'Emilia Romagna',
    'Friuli Venezia Giulia',
    'Lazio',
    'Liguria',
    'Lombardia',
    'Marche',
    'Molise',
    'Piemonte',
    'Puglia',
    'Sardegna',
    'Sicilia',
    'Toscana',
    'Trentino Alto Adige',
    'Umbria',
    'Valle d\'Aosta',
    'Veneto'
);

$provinces = array(
    'AG' => 'Agrigento',
    'AL' => 'Alessandria',
    'AN' => 'Ancona',
    'AO' => 'Aosta',
    'AR' => 'Arezzo',
    'AP' => 'Ascoli Piceno',
    'AT' => 'Asti',
    'AV' => 'Avellino',
    'BA' => 'Bari',
    'BT' => 'Barletta-Andria-Trani',
    'BL' => 'Belluno',
    'BN' => 'Benevento',
    'BG' => 'Bergamo',
    'BI' => 'Biella',
    'BO' => 'Bologna',
    'BZ' => 'Bolzano',
    'BS' => 'Brescia',
    'BR' => 'Brindisi',
    'CA' => 'Cagliari',
    'CL' => 'Caltanissetta',
    'CB' => 'Campobasso',
    'CI' => 'Carbonia-Iglesias',
    'CE' => 'Caserta',
    'CT' => 'Catania',
    'CZ' => 'Catanzaro',
    'CH' => 'Chieti',
    'CO' => 'Como',
    'CS' => 'Cosenza',
    'CR' => 'Cremona',
    'KR' => 'Crotone',
    'CN' => 'Cuneo',
    'EN' => 'Enna',
    'FM' => 'Fermo',
    'FE' => 'Ferrara',
    'FI' => 'Firenze',
    'FG' => 'Foggia',
    'FC' => 'ForlÃ¬-Cesena',
    'FR' => 'Frosinone',
    'GE' => 'Genova',
    'GO' => 'Gorizia',
    'GR' => 'Grosseto',
    'IM' => 'Imperia',
    'IS' => 'Isernia',
    'SP' => 'La Spezia',
    'AQ' => 'L\'Aquila',
    'LT' => 'Latina',
    'LE' => 'Lecce',
    'LC' => 'Lecco',
    'LI' => 'Livorno',
    'LO' => 'Lodi',
    'LU' => 'Lucca',
    'MC' => 'Macerata',
    'MN' => 'Mantova',
    'MS' => 'Massa-Carrara',
    'MT' => 'Matera',
    'ME' => 'Messina',
    'MI' => 'Milano',
    'MO' => 'Modena',
    'MB' => 'Monza e della Brianza',
    'NA' => 'Napoli',
    'NO' => 'Novara',
    'NU' => 'Nuoro',
    'OT' => 'Olbia-Tempio',
    'OR' => 'Oristano',
    'PD' => 'Padova',
    'PA' => 'Palermo',
    'PR' => 'Parma',
    'PV' => 'Pavia',
    'PG' => 'Perugia',
    'PU' => 'Pesaro e Urbino',
    'PE' => 'Pescara',
    'PC' => 'Piacenza',
    'PI' => 'Pisa',
    'PT' => 'Pistoia',
    'PN' => 'Pordenone',
    'PZ' => 'Potenza',
    'PO' => 'Prato',
    'RG' => 'Ragusa',
    'RA' => 'Ravenna',
    'RC' => 'Reggio Calabria',
    'RE' => 'Reggio Emilia',
    'RI' => 'Rieti',
    'RN' => 'Rimini',
    'RM' => 'Roma',
    'RO' => 'Rovigo',
    'SA' => 'Salerno',
    'VS' => 'Medio Campidano',
    'SS' => 'Sassari',
    'SV' => 'Savona',
    'SI' => 'Siena',
    'SR' => 'Siracusa',
    'SO' => 'Sondrio',
    'TA' => 'Taranto',
    'TE' => 'Teramo',
    'TR' => 'Terni',
    'TO' => 'Torino',
    'OG' => 'Ogliastra',
    'TP' => 'Trapani',
    'TN' => 'Trento',
    'TV' => 'Treviso',
    'TS' => 'Trieste',
    'UD' => 'Udine',
    'VA' => 'Varese',
    'VE' => 'Venezia',
    'VB' => 'Verbano-Cusio-Ossola',
    'VC' => 'Vercelli',
    'VR' => 'Verona',
    'VV' => 'Vibo Valentia',
    'VI' => 'Vicenza',
    'VT' => 'Viterbo',
);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];
    $success = "";

    if (isset($_POST["nome"]) && isset($_POST["cognome"]) && isset($_POST["ultimo_fatturato"]) && isset($_POST["regione"]) && isset($_POST["provincia"]) && isset($_POST["percentuale_provvigione"])) {
        $nome = $_POST["nome"];
        $cognome = $_POST["cognome"];
        $ultimo_fatturato = $_POST["ultimo_fatturato"];
        $regione = $_POST["regione"];
        $provincia = $_POST["provincia"];
        $percentuale_provvigione = $_POST["percentuale_provvigione"];

        if (is_numeric($nome)) {
            $errors["nome"] = "Inserisci un nome di tipo stringa";
        }
        if (is_numeric($cognome)) {
            $errors["cognome"] = "Inserisci un cognome di tipo stringa";
        }
        if (!is_numeric($ultimo_fatturato)) {
            $errors["ultimo_fatturato"] = "Iserisci un ultimo fatturato di tipo numerico";
        } elseif ($ultimo_fatturato < 0) {
            $errors["ultimo_fatturato"] = "Il valore del ultimo fatturato deve essere positivo";
        }
        if (!is_string($regione)) {
            $errors["regione"] = "Inserisci una regione di tipo stringa";
        } elseif (!in_array($regione, $regions)) {
            $errors["regione"] = "Insersici una regione Italiana valida";
        }
        if (!is_string($provincia)) {
            $errors["provincia"] = "Inserisci una provincia di tipo stringa";
        } elseif (!in_array($provincia, $provinces, true)) {
            // Usiamo la ricerca strict per controllare solo i valori del array associativo e non le chiavi
            $errors["provincia"] = "Inserisci una provincia valida";
        }
        if (!is_numeric($percentuale_provvigione)) {
            $errors["percentuale_provvigione"] = "Inserisci una percentuale numerica";
        } elseif ($percentuale_provvigione < 0 || $percentuale_provvigione > 100) {
            $errors["percentuale_provvigione"] = "Inserisci una percentuale compresa tra 0 e 100";
        }


        if (empty($errors)) {
            $nome = sanitize_input($_POST["nome"]);
            $cognome = sanitize_input($_POST["cognome"]);
            $regione = sanitize_input($_POST["regione"]);
            $provincia = sanitize_input($_POST["provincia"]);

            $checkAlreadyIsertRappresentante = "SELECT * FROM Rappresentante WHERE Nome = '$nome' AND Cognome = '$cognome' AND Regione = '$regione' AND Provincia = '$provincia'";
            $checkAlreadyIsertRappresentanteResult = $conn->query($checkAlreadyIsertRappresentante);

            if ($checkAlreadyIsertRappresentanteResult->num_rows == 0) {
                $querySQL = "INSERT INTO Rappresentante (Nome, Cognome, UltimoFatturato, Regione, Provincia, PercentualeProvvigione) VALUES ('$nome', '$cognome', $ultimo_fatturato, '$regione', '$provincia', $percentuale_provvigione)";
                // echo $querySQL;
                $conn->query($querySQL);

                $success = "Il rappresentante $nome $cognome è stato creato";
            } else {
                $errors["general_error"] = "Questo rappresentante esiste già nel database";
            }
        }
    } else {
        $errors["general_error"] = "Inserisci i valori richiesti";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="stylesheet" href="../style/style.css" />

    <title>Home page gestione dei rappresentanti</title>
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
                <a class="link" href="./">
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
                        <h1>Crea Rappresentante</h1>
                    </div>
                    <form action="<?= sanitize_input($_SERVER["PHP_SELF"]) ?>" method="post">
                        <div class="inputContainer">
                            <label for="nome">Nome</label>
                            <input type="text" id="nome" name="nome" placeholder="Giovanni" required>
                            <?php if (isset($errors["nome"])): ?>
                                <div class="error"><?= sanitize_input($errors["nome"]) ?></div>
                            <?php endif ?>
                        </div>
                        <div class="inputContainer">
                            <label for="cognome">Cognome</label>
                            <input type="text" id="cognome" name="cognome" placeholder="Lattuga" required>
                            <?php if (isset($errors["cognome"])): ?>
                                <div class="error"><?= sanitize_input($errors["cognome"]) ?></div>
                            <?php endif ?>
                        </div>
                        <div class="inputContainer">
                            <label for="ultimo_fatturato">Ultimo Fatturato</label>
                            <input type="number" id="ultimo_fatturato" name="ultimo_fatturato" placeholder="1300" min="0" required>
                            <?php if (isset($errors["ultimo_fatturato"])): ?>
                                <div class="error"><?= sanitize_input($errors["ultimo_fatturato"]) ?></div>
                            <?php endif ?>
                        </div>
                        <div class="inputContainer">
                            <label for="regione">Regione</label>
                            <select id="regione" name="regione" placeholder="Piemonte" required>
                                <?php foreach ($regions as $region) : ?>
                                    <option value="<?= $region ?>"><?= $region ?></option>
                                <?php endforeach ?>
                            </select>
                            <?php if (isset($errors["regione"])): ?>
                                <div class="error"><?= sanitize_input($errors["regione"]) ?></div>
                            <?php endif ?>
                        </div>
                        <div class="inputContainer">
                            <label for="provincia">Provincia</label>
                            <select id="provincia" name="provincia" placeholder="Piemonte" required>
                                <?php foreach ($provinces as $_ => $province): ?>
                                    <option value="<?= $province ?>"><?= $province ?></option>
                                <?php endforeach ?>
                            </select>
                            <?php if (isset($errors["provincia"])): ?>
                                <div class="error"><?= sanitize_input($errors["provincia"]) ?></div>
                            <?php endif ?>
                        </div>
                        <div class="inputContainer">
                            <label for="percentuale_provvigione">Percentuale Provvigione</label>
                            <input type="number" id="percentuale_provvigione" name="percentuale_provvigione" placeholder="50" min="0" max="100" required>
                            <?php if (isset($errors["percentuale_provvigione"])): ?>
                                <div class="error"><?= sanitize_input($errors["percentuale_provvigione"]) ?></div>
                            <?php endif ?>
                        </div>
                        <?php if (isset($errors["general_error"])): ?>
                            <div class="error"><?= sanitize_input($errors["general_error"]) ?></div>
                        <?php elseif (!empty($success)): ?>
                            <div class="success"><?= sanitize_input($success) ?></div>
                        <?php endif ?>

                        <div>
                            <button class=" button submit" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
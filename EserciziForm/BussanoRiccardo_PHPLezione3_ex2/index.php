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

    <title>Menu Ristorante Online</title>
</head>

<body>
    <?php
    $output = "";
    $menu = [
        "fritto_misto" => 15,
        "insalata_di_mare" => 20,
        "prosciutto_e_melone" => 10,
        "carbonara" => 12,
        "amatriciana" => 10,
        "cacio_e_pepe" => 15,
        "bistecca" => 25,
        "cotoletta" => 20,
        "pesce" => 30,
        "patate" => 5,
        "insalata" => 3,
        "verdure" => 4,
        "acqua" => 1,
        "cocacola" => 2,
        "vino" => 10,
        "birra" => 5
    ];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $antipasto = $_POST["antipasti"];
        $primo = $_POST["primi"];
        $secondo = $_POST["secondi"];
        $contorno = $_POST["contorni"];
        $bevanda = $_POST["bevande"];

        $prezzo = 0;

        if (
            array_key_exists($antipasto, $menu) or
            array_key_exists($primo, $menu) or
            array_key_exists($secondo, $menu) or
            array_key_exists($contorno, $menu) or
            array_key_exists($bevanda, $menu)
        ) {
            $output = "Inserisci una vivanda valida";
        }

        $prezzo += $menu[$antipasto];
        $prezzo += $menu[$primo];
        $prezzo += $menu[$secondo];
        $prezzo += $menu[$contorno];
        $prezzo += $menu[$bevanda];

        $output = "Il prezzo è pari a: $prezzo €";
    }
    ?>

    <div class="container column">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="inputContainer">
                <label for="antipasti">Antipasti</label>
                <select name="antipasti" id="antipasti">
                    <option value="fritto_misto">Fritto Misto</option>
                    <option value="insalata_di_mare">Insalata di Mare</option>
                    <option value="prosciutto_e_melone">Prosciutto e Melone</option>
                </select>
            </div>

            <div class="inputContainer">
                <label for="primi">Primi</label>
                <select name="primi" id="primi">
                    <option value="carbonara">Carbonara</option>
                    <option value="amatriciana">Amatriciana</option>
                    <option value="cacio_e_pepe">Cacio e Pepe</option>
                </select>
            </div>

            <div class="inputContainer">
                <label for="secondi">Secondi</label>
                <select name="secondi" id="secondi">
                    <option value="bistecca">Bistecca</option>
                    <option value="cotoletta">Cotoletta</option>
                    <option value="pesce">Pesce</option>
                </select>
            </div>

            <div class="inputContainer">
                <label for="contorni">Contorni</label>
                <select name="contorni" id="contorni">
                    <option value="patate">Patate</option>
                    <option value="insalata">Insalata</option>
                    <option value="verdure">Verdure</option>
                </select>
            </div>

            <div class="inputContainer">
                <label for="bevande">Bevande</label>
                <select name="bevande" id="bevande">
                    <option value="acqua">Acqua</option>
                    <option value="cocacola">Coca Cola</option>
                    <option value="vino">Vino</option>
                    <option value="birra">Birra</option>
                </select>
            </div>

            <button type="submit">Invia</button>
        </form>
        <div class="inputContainer">
            <h3>Prezzo Complessivo</h3>
            <div><?php echo $output ?></div>
        </div>
    </div>
</body>

</html>
<?php

declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>

    <title>Convertitore</title>
</head>

<body class="bg-zinc-950 text-neutral-50">
    <?php
    class ConvertitoreValute
    {
        private array $mercati = [
            "EUR/USD" => 1.08,
            "USD/EUR" => 0.92,
        ];

        private array $simboli_valute = [
            "EUR" => "€",
            "USD" => "$",
        ];

        function get_mercati(): array
        {
            return $this->mercati;
        }

        function set_mercati(array $array)
        {
            if (!is_array($array)) {
                throw new Exception("Argomento inserito deve essere un array");
            }

            $this->mercati = $array;
        }

        function get_siboli(): array
        {
            return $this->simboli_valute;
        }

        function set_siboli(array $simboli_valute)
        {
            if (!is_array($simboli_valute)) {
                throw new Exception("Devi inserire un array valido");
            }

            $this->simboli_valute = $simboli_valute;
        }

        function get_sibolo_valuta(string $mercato): string
        {
            if (!array_key_exists($mercato, $this->get_mercati())) {
                throw new Exception("Il mercato inserito non esisiste");
            }

            $valuta_importo = explode("/", $mercato)[1];
            $simbolo_valuta = $this->get_siboli()[$valuta_importo];
            return $simbolo_valuta;
        }

        function converti(string $mercato, float $importo): float
        {
            if (!array_key_exists($mercato, $this->get_mercati())) {
                throw new Exception("Il mercato inserito non esisiste");
            }
            if ($importo <= 0) {
                throw new Exception("Devi inserire un importo maggiore di zero");
            }

            $tasso_cambio = $this->get_mercati()[$mercato];
            return $importo * $tasso_cambio;
        }

        function format_ouput(string $mercato, float $importo): string
        {
            if (!array_key_exists($mercato, $this->get_mercati())) {
                throw new Exception("Il mercato inserito non esisiste");
            }

            $sibolo_valuta = $this->get_sibolo_valuta($mercato);

            return "$importo $sibolo_valuta";
        }
    }
    ?>

    <div class="h-screen flex flex-col items-center justify-center">
        <?php
        $convertitore1 = new ConvertitoreValute();
        $convertitore2 = new ConvertitoreValute();

        $conversione1 = $convertitore1->converti("USD/EUR", 100);
        $conversione2 = $convertitore1->converti("EUR/USD", 100);
        $conversione3 = $convertitore2->converti("USD/EUR", 100);
        $conversione4 = $convertitore2->converti("EUR/USD", 100);
        ?>
        <h1 class="text-4xl font-bold mb-4">Convertitore 1</h1>
        <p>
            100 dollari convertiti in euro:
            <?php
            echo $conversione1;
            echo $convertitore1->get_sibolo_valuta("USD/EUR");
            ?>
        </p>
        <p>
            100 euro convertiti in dollari:
            <?php
            echo $conversione2;
            echo $convertitore1->get_sibolo_valuta("EUR/USD");
            ?>
        </p>
        <h1 class="text-4xl font-bold my-4">Convertitore 2</h1>
        <p>
            100 dollari convertiti in euro:
            <?php
            echo $conversione3;
            echo $convertitore2->get_sibolo_valuta("USD/EUR");
            ?>
        </p>
        <p>
            100 euro convertiti in dollari:
            <?php
            echo $conversione4;
            echo $convertitore2->get_sibolo_valuta("EUR/USD");
            ?>
        </p>
    </div>
</body>

</html>
<?php

declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap");

        * {
            font-family: "Inter", sans-serif;
        }
    </style>

    <title>Figura Geometrica</title>
</head>

<body class="bg-zinc-950 text-neutral-50">
    <?php
    class FiguraGeometrica
    {
        const DESCRIZIONE = "Figura geometrica generica";
        const NUMERO_LATI =  0;

        public function getDescrizione(): string
        {
            // IMPORTATE: self::DESCRIZIONE non ottiene i risultati desiderati, questo perché noi vogliamo ottenre il valore della costante DESCRIZIONE della classe figlia e non della classe padre. O meglio voglio ottenre il valore della costante DESCRIZIONE dalla classe in cui è stato chiamato il metodo getDescrizione() e non dalla classe padre, per cui la costante DESCRIZIONE sarebbe sempre uguale. Quindi per ottenere questo risultato dobbiamo usare static::DESCRIZIONE che fa esattamente questo.
            return static::DESCRIZIONE;
        }

        public function calcolaArea(): float
        {
            return 0;
        }
    }

    class Quadrato extends FiguraGeometrica
    {
        const DESCRIZIONE = "Quadrato";
        const NUMERO_LATI = 4;
        private float $lato;

        public function __construct(int $lato)
        {
            $this->setLato($lato);
        }

        public function getLato(): float
        {
            return $this->lato;
        }

        public function setLato(float $lato): void
        {
            if (!is_numeric($lato)) {
                throw new Exception("Il lato deve essere un numero");
            }

            $this->lato = $lato;
        }

        public function calcolaArea(): float
        {
            return $this->lato * $this->lato;
        }
    }

    class Triangolo extends FiguraGeometrica
    {
        const DESCRIZIONE = "Triangolo";
        const NUMERO_LATI = 3;
        private float $base;
        private float $altezza;

        public function __construct(int $base, int $altezza)
        {
            $this->setBase($base);
            $this->setAltezza($altezza);
        }

        public function getBase(): float
        {
            return $this->base;
        }

        public function setBase(float $base): void
        {
            if (!is_numeric($base)) {
                throw new Exception("La base deve essere un numero");
            }

            $this->base = $base;
        }

        public function getAltezza(): float
        {
            return $this->altezza;
        }

        public function setAltezza(float $altezza): void
        {
            if (!is_numeric($altezza)) {
                throw new Exception("L'altezza deve essere un numero");
            }

            $this->altezza = $altezza;
        }

        public function calcolaArea(): float
        {
            return ($this->base * $this->altezza) / 2;
        }
    }

    function validate_data(string $data): string
    {
        $data = trim($data);
        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        return $data;
    }

    $quadrato = new Quadrato(5);
    $triangolo = new Triangolo(5, 3);
    ?>
    <div class="w-full h-screen flex flex-col justify-center items-center gap-4">
        <form class="flex gap-4" method="get" <?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
            <button type="submit" name="type" value="quadrato" class="px-4 py-2 bg-neutral-50 text-zinc-950 rounded-md">Calcola Quadrato</button>
            <button type="submit" name="type" value="triangolo" class=" px-4 py-2 bg-neutral-50 text-zinc-950 rounded-md">Calcola Triangolo</button>
        </form>
        <div>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["type"])) {
                $type = validate_data($_GET["type"]);

                if ($type == "quadrato") {
                    echo $quadrato->getDescrizione() . ":<br>";
                    echo "Area: " . $quadrato->calcolaArea();
                } else if ($type == "triangolo") {
                    echo $triangolo->getDescrizione() . ":<br>";
                    echo "Area: " . $triangolo->calcolaArea();
                } else {
                    $render_error = "<div class='text-red-400'>Inserisci una figura geometrica accettata</div>";
                    echo $render_error;
                }
            }
            ?>
        </div>
    </div>
</body>

</html>
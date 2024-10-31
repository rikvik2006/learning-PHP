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

    <title>Veicoli</title>
</head>

<body class="bg-zinc-950 text-neutral-50">
    <?php
    class Veicolo
    {
        protected string $marca;
        protected int $anno;

        public function __construct(string $marca, int $anno)
        {
            $this->setAnno($anno);
            $this->setMarca($marca);
        }

        public function getMarca(): string
        {
            return $this->marca;
        }

        public function getAnno(): int
        {
            return $this->anno;
        }

        public function setMarca(string $marca): void
        {
            if (!is_string($marca)) {
                throw new Exception("Marca non valida");
            }
            if (empty($marca)) {
                throw new Exception("Marca non valida");
            }

            $this->marca = $marca;
        }

        public function setAnno(int $anno): void
        {
            if (!is_int($anno)) {
                throw new Exception("Anno non valido");
            }

            $this->anno = $anno;
        }

        // Metodi richisti
        public function getInfo(): string
        {
            return "Veicolo(Marca: " . $this->getMarca() . ", Anno: " . $this->getAnno() . ")";
        }

        public static function categoria(): string
        {
            return "Generale";
        }
    }

    class Auto extends Veicolo
    {
        private string $modello;

        public function __construct(string $marca, int $anno, string $modello)
        {
            // Accediamo al costruttore della classe padre
            // la sintassi è sibile a quella per chiamare un metodo statico, sarà il costruttore un metodo statico?
            parent::__construct($marca, $anno);
            $this->setModello($modello);
        }

        public function getModello(): string
        {
            return $this->modello;
        }

        public function setModello(string $modello): void
        {
            if (!is_string($modello)) {
                throw new Exception("Modello non valido");
            }
            if (empty($modello)) {
                throw new Exception("Modello non valido");
            }

            $this->modello = $modello;
        }

        public function getInfo(): string
        {
            return "Auto(Marca: " . $this->getMarca() . ", Anno: " . $this->getAnno() . ", Modello: " . $this->getModello() . ")";
        }

        public static function categoria(): string
        {
            return "Automobile";
        }
    }

    function validate_data(string $data): string
    {
        $data = trim($data);
        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        return $data;
    }

    $veicolo = new Veicolo("Goofy Car", 2021);
    $auto = new Auto("BMW", 2021, "X5");
    ?>
    <div class="w-full h-screen flex flex-col justify-center items-center gap-4">
        <form class="flex gap-4" method="get" <?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
            <button type="submit" name="type" value="veicolo" class="px-4 py-2 bg-neutral-50 text-zinc-950 rounded-md">Info Veicolo</button>
            <button type="submit" name="type" value="auto" class=" px-4 py-2 bg-neutral-50 text-zinc-950 rounded-md">Info Automobile</button>
        </form>
        <div>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["type"])) {
                $type = validate_data($_GET["type"]);

                if ($type == "veicolo") {
                    echo Veicolo::categoria() . "<br>";
                    echo $veicolo->getInfo();
                } else if ($type == "auto") {
                    echo Auto::categoria() . "<br>";
                    echo $auto->getInfo();
                } else {
                    $render_error = "<div class='text-red-400'>Insert a type that exist</div>";
                    echo $render_error;
                }
            }
            ?>
        </div>
    </div>
</body>

</html>
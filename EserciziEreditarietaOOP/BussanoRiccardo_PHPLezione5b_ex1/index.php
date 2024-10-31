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
        private string $marca;

        public function __construct(string $marca)
        {
            $this->setMarca($marca);
        }

        public function getMarca(): string
        {
            return $this->marca;
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

        // Metodi richisti
        public function accellera(): string
        {
            return "Veicolo in accelerazione " . $this->getMarca();
        }
    }

    final class Automobile extends Veicolo
    {
        public function accellera(): string
        {
            return "Automobile in accelerazione " . $this->getMarca();
        }
    }

    final class Moto extends Veicolo
    {
        public function accellera(): string
        {
            return "Moto in accelerazione " . $this->getMarca();
        }
    }

    function validate_data(string $data): string
    {
        $data = trim($data);
        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        return $data;
    }

    $veicolo = new Veicolo("Goofy Car");
    $automobile = new Automobile("BMW");
    $moto = new Moto("Piagio");
    ?>
    <div class="w-full h-screen flex flex-col justify-center items-center gap-4">
        <form class="flex gap-4" method="get" <?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
            <button type="submit" name="type" value="veicolo" class="px-4 py-2 bg-neutral-50 text-zinc-950 rounded-md">Accelera veicolo</button>
            <button type="submit" name="type" value="automobile" class=" px-4 py-2 bg-neutral-50 text-zinc-950 rounded-md">Acccellera automobile</button>
            <button type="submit" name="type" value="moto" class=" px-4 py-2 bg-neutral-50 text-zinc-950 rounded-md">Acccelera Moto</button>
        </form>
        <div>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["type"])) {
                $type = validate_data($_GET["type"]);

                if ($type == "veicolo") {
                    echo $veicolo->accellera();
                } else if ($type == "automobile") {
                    echo $automobile->accellera();
                } else if ($type == "moto") {
                    echo $moto->accellera();
                } else {
                    $render_error = "<div class='text-red-400'>Insert a valid type</div>";
                    echo $render_error;
                }
            }
            ?>
        </div>
    </div>
</body>

</html>
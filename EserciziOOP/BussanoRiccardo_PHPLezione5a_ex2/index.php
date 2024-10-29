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

    <title>Classe Libro</title>
</head>

<body class="bg-zinc-950 text-neutral-50">
    <?php
    class Libro
    {
        private string $titolo;
        private float $prezzo;
        private int $numero_scaffale;
        private int $numero_pagine;
        private string $casa_editrice;
        private string $copertina;

        function __construct(string $titolo, float $prezzo, int $numero_scaffale, int $numero_pagine, string $casa_editrice, string $copertina)
        {
            $this->set_titolo($titolo);
            $this->set_prezzo($prezzo);
            $this->set_numero_scaffale($numero_scaffale);
            $this->set_numero_pagine($numero_pagine);
            $this->set_casa_editrice($casa_editrice);
            $this->set_copertina($copertina);
        }

        // Getters
        function get_titolo(): string
        {
            return $this->titolo;
        }

        function get_prezzo(): float
        {
            return $this->prezzo;
        }

        function get_numero_scaffale(): int
        {
            return $this->numero_scaffale;
        }

        function get_numero_pagine(): int
        {
            return $this->numero_pagine;
        }

        function get_casa_editrice(): string
        {
            return $this->casa_editrice;
        }

        function get_copertina(): string
        {
            return $this->copertina;
        }

        // Setters

        function set_titolo(string $titolo)
        {
            // controlla che sia una stringa
            if (!is_string($titolo)) {
                throw new Exception("Il titolo deve essere una stringa");
            }
            $this->titolo = $titolo;
        }

        function set_prezzo(float $prezzo)
        {
            // controlla che sia un numero
            if (!is_numeric($prezzo)) {
                throw new Exception("Il prezzo deve essere un numero");
            }
            // controlla che sia maggiore di zero
            if ($prezzo <= 0) {
                throw new Exception("Il prezzo deve essere maggiore di zero");
            }
            $this->prezzo = $prezzo;
        }

        function set_numero_scaffale(int $numero_scaffale)
        {
            // controlla che sia un numero
            if (!is_numeric($numero_scaffale)) {
                throw new Exception("Il numero scaffale deve essere un numero");
            }
            $this->numero_scaffale = $numero_scaffale;
        }

        function set_numero_pagine(int $numero_pagine)
        {
            // controlla che sia un numero
            if (!is_numeric($numero_pagine)) {
                throw new Exception("Il numero pagine deve essere un numero");
            }
            // controlla che sia maggiore di zero
            if ($numero_pagine <= 0) {
                throw new Exception("Il numero pagine deve essere maggiore di zero");
            }
            $this->numero_pagine = $numero_pagine;
        }

        function set_casa_editrice(string $casa_editrice)
        {
            // controlla che sia una stringa
            if (!is_string($casa_editrice)) {
                throw new Exception("La casa editrice deve essere una stringa");
            }
            $this->casa_editrice = $casa_editrice;
        }

        function set_copertina(string $copertina)
        {
            // controlla che sia una stringa
            if (!is_string($copertina)) {
                throw new Exception("La copertina deve essere una stringa");
            }
            $this->copertina = $copertina;
        }

        function sconto_10_percento()
        {
            $sconto = $this->get_prezzo() * 0.1;
            return $this->get_prezzo() - $sconto;
        }

        function formatta_dati()
        {
            return "Titolo: " . $this->get_titolo() . "<br>Prezzo: " . $this->get_prezzo() . "<br>Numero Scaffale: " . $this->get_numero_scaffale() . "<br>Numero Pagine: " . $this->get_numero_pagine() . "<br>Casa Editrice: " . $this->get_casa_editrice() . "<br>Copertina: " . $this->get_copertina();
        }
    }
    ?>

    <div class="h-screen flex flex-row justify-center items-center">
        <div class="flex flex-row justify-center">
            <?php
            // Creiamo i 3 libri
            $libro1 = new Libro("The Rust Programming Language", 20.50, 1, 500, "No Starch Press", "https://imgur.com/uHh20qj.png");
            $libro2 = new Libro("Python Primi Passi", 5, 2, 275, "Independently published", "https://imgur.com/lQmYbaP.png");
            $libro3 = new Libro("Every Day Go", 15, 3, 250, "Independently published", "https://imgur.com/8YujNfW.png");

            // Insertiamo i libri in un array
            $libri = [$libro1, $libro2, $libro3];

            // Per ogni libro al intenro del array stapiamolo a schermo
            foreach ($libri as $libro) {
                echo "<div class='flex flex-col border rounded-lg border-stone-800 p-4 m-4 w-[350px] gap-4'>";
                echo "<img class='w-full' src='" . $libro->get_copertina() . "'>";
                echo "<h1 class='text-2xl text-center font-bold'>" . $libro->get_titolo() . "</h1>";
                echo "<div class=''>";
                echo "<p class='line-through'>Prezzo: " . $libro->get_prezzo() . "</p>";
                echo "<p class='text-2xl'><span class='text-red-500'>" . $libro->sconto_10_percento() . " € -10%</span></p>";
                echo "<p>Numero Scaffale: " . $libro->get_numero_scaffale() . "</p>";
                echo "<p>Numero Pagine: " . $libro->get_numero_pagine() . "</p>";
                echo "<p>Casa Editrice: " . $libro->get_casa_editrice() . "</p>";
                echo "</div>";
                echo "</div>";
            }
            ?>
        </div>

    </div>
</body>

</html>
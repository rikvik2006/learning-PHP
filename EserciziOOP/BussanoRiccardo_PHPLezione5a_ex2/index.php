<?php

declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>

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
        <!-- Usando tailwind css crea una flex row con 3 libri che sotto ogni libro hanno i loro relativi dati, quindi Titolo, prezzo (prima dello sconto con una riga sopra), prezzo scontato del 10% rosso, numero scaffale, casa editrice, e  -->
        img
        <?php
        $libro1 = new Libro("Il signore degli anelli", 20.50, 1, 500, "Mondadori", "https://imgur.com/eI3OKkX");
        $libro2 = new Libro("Il signore degli anelli", 20.50, 1, 500, "Mondadori", "Copertina rigida");
        $libro3 = new Libro("Il signore degli anelli", 20.50, 1, 500, "Mondadori", "Copertina rigida");

        $libri = [$libro1, $libro2, $libro3];

        foreach ($libri as $libro) {
            echo "<div class='flex flex-col items-center justify-center bg-zinc-800 p-4 m-4 rounded-lg'>";
            echo "<img src='" . $libro->get_copertina() . "' class='text-center'>";
            echo "<h1 class='text-2xl text-center'>" . $libro->get_titolo() . "</h1>";
            echo "<p class='text-center'>Prezzo: " . $libro->get_prezzo() . "</p>";
            echo "<p class='text-center'>Prezzo scontato: <span class='text-red-500'>" . $libro->sconto_10_percento() . "</span></p>";
            echo "<p class='text-center'>Numero Scaffale: " . $libro->get_numero_scaffale() . "</p>";
            echo "<p class='text-center'>Numero Pagine: " . $libro->get_numero_pagine() . "</p>";
            echo "<p class='text-center'>Casa Editrice: " . $libro->get_casa_editrice() . "</p>";
            echo "</div>";
        }

        ?>

    </div>
</body>

</html>
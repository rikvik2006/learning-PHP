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

    <title>Immagine Random</title>
</head>

<body class="bg-zinc-950 text-neutral-50 h-screen flex justify-center items-center gap-20">
    <?php
    class RandomImageController
    {
        private array $files;
        private string $folder_path;

        function __construct(string $folder_path)
        {
            $this->setFolderPath($folder_path);
        }

        // Getters
        public function getFiles(): array
        {
            return $this->files;
        }

        public function getFolderPath(): string
        {
            return $this->folder_path;
        }

        // Setters
        public function setFolderPath(string $folder_path): void
        {
            // Controllo se è una stringa
            if (!is_string($folder_path)) {
                throw new Exception("The folder path must be a string");
            }
            // Controllo se la stringa è la path di una directory
            if (!is_dir($folder_path)) {
                throw new Exception("The folder path must be a valid directory");
            }
            // Controllo se la stringa termina con "/" o "\" e la aggiungo se non c'è
            if (substr($folder_path, -1) !== "/" && substr($folder_path, -1) !== "\\") {
                $folder_path .= "/";
            }

            $this->folder_path = $folder_path;

            $this->loadDirFiles($folder_path);
        }

        private function loadDirFiles($folder_path): void
        {
            // Leggiamo il contenuto della directory
            $this->files = scandir($folder_path);
            // Rimuoviamo i primi due elementi che sono "." e ".."
            array_splice($this->files, 0, 2);
        }

        public function getRandomImageIndex(): int
        {
            $number_of_files = count($this->files);
            // Generiamo un numero randomico nel intervallo [0, $number_of_files -1]
            $random_index = random_int(0, $number_of_files - 1);

            return $random_index;
        }

        public function getRandomImage(): string
        {
            // Estraiamo il nome della imamgine
            $index = $this->getRandomImageIndex();
            $file_name = $this->files[$index];

            // Restituimamo il path completo
            return $this->folder_path . $file_name;
        }
    }

    $random_image_controller1 = new RandomImageController("./immagini/");
    $random_image1_path = $random_image_controller1->getRandomImage();
    $random_image_controller2 = new RandomImageController("./immagini/");
    $random_image2_path = $random_image_controller2->getRandomImage();
    ?>


    <img class="max-h-[500px]" src="<?php echo $random_image1_path ?>" alt="<?php echo $random_image1_path ?>">
    <img class="max-h-[500px]" src=" <?php echo $random_image2_path ?>" alt="<?php echo $random_image2_path ?>">
</body>

</html>
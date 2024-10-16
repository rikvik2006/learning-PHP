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

    <title>Immagine</title>
</head>

<body>
    <?php

    function get_images(): array
    {
        $files = scandir("./immagini/");
        array_splice($files, 0, 2);

        return $files;
    }

    function get_random_image_index(array $files_array): int
    {
        $number_of_files = count($files_array);

        // Generiamo un numero randomico compreso tra [0, $number_of_files - 1] es [0, 2] (intervallo chiuso incluso)
        $random_index = random_int(0, $number_of_files - 1);
        return $random_index;
    }

    function select_image(int $index): string
    {
        // $image_path = "./immagini/$index";
        $files = get_images();

        // Check index
        $number_of_files = count($files);
        if ($index < 0 or $index > $number_of_files - 1) {
            throw new Exception("insert a valid index");
        }

        $file_name = $files[$index];
        $image_path = "./immagini/$file_name";

        return $image_path;
    }
    ?>
    <div class="container column font-size">
        <?php
        try {
            $files = get_images();
            $index = get_random_image_index($files);
            $image_path = select_image($index);

            echo "<img class='image' src='$image_path' alt='random_image'>";
        } catch (Exception $e) {
            echo "<div class='error'>Error: " . $e->getMessage() . "</div>";
        }

        ?>
    </div>
</body>

</html>
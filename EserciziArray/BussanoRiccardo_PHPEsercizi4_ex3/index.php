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

    <title>Libri BR</title>
</head>

<body>
    <?php
    $libri = array("Harry Potter", "Primi passi Python", "Next JS Zero to Hero", "Bobobz", "Power", "Atomic Habbits");

    function render_libri(array $array_libri): string
    {
        // Unisce l'array $array_libri, utilizzando la stringa "<br> per concatenare un elemento al altro
        return implode("<br>", $array_libri);
    }
    ?>

    <div class="container column font-size">
        <p>
            <?php echo render_libri($libri); ?>
        </p>
    </div>
</body>

</html>
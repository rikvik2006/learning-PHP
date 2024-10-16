<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">

    <title>[Do While] Triangolo Rettangolo</title>
</head>

<body>
    <?php
    $base = 5;
    $triangolo = "";
    $i = 0;
    do {
        $linea = "";
        $k = 0;
        do {
            $linea .= "*";
            $k++;
        } while ($k <= $i);

        $triangolo .= $linea . "<br>";
        $i++;
    } while ($i < $base);

    ?>
    <div class="container column">
        <h1>Do While</h1>
        <div class="triangolo"><?php echo $triangolo ?></div>
    </div>
</body>

</html>
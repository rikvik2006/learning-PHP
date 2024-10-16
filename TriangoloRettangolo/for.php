<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">

    <title>[For] Triangolo Rettangolo</title>
</head>

<body>
    <?php
    $base = 4;
    $triangolo = "";

    for ($i = 0; $i < $base; $i++) {
        $linea = "";
        for ($k = 0; $k <= $i; $k++) {
            $linea .= "*";
        }

        $triangolo .= $linea . "<br>";
    }
    ?>
    <div class="container column">
        <h1>For</h1>
        <div class="triangolo"><?php echo $triangolo ?></div>
    </div>
</body>

</html>
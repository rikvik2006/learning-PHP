<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">

    <title>[While] Triangolo Rettangolo</title>
</head>

<body>
    <?php
    $base = 6;
    $triangolo = "";
    $i = 0;
    while ($i < $base) {
        $liena = "";
        $k = 0;
        while ($k <= $i) {
            $liena .= "*";
            $k++;
        }

        $triangolo .= $liena . "<br>";
        $i++;
    }
    ?>

    <div class="container column">
        <h1>While</h1>
        <div class="triangolo"><?php echo $triangolo ?></div>
    </div>
</body>

</html>
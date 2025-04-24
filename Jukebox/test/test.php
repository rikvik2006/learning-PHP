<?php
require_once __DIR__ . "/../models/BaseModel.php";
require_once __DIR__ . "/../models/ArtistModel.php";
require_once __DIR__ . "/../models/InterpretationModel.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test page</title>
</head>

<body>
    <?php
    $testModel = new ArtistModel();

    $all_artist = $testModel->getAllArtist();
    $artist = $testModel->getArtistById("0211306f-c841-4545-80f3-75e88cbc04bd");

    for ($i = 0; $i < count($all_artist); $i++) {
        echo $all_artist[$i]->stage_name . "<br>";
    }


    echo "<br>";

    echo $artist->stage_name . "<br>";
    ?>
</body>

</html>
<?php
require_once __DIR__ . "/../models/BaseModel.php";

class TestModel extends BaseModel
{
    public function getAllArtist()
    {
        $sql_query = "SELECT * FROM artist";
        $result = $this->connection->query($sql_query);

        return $result;
    }

    public function getArtistById(string $uuid)
    {
        $sql_query = "SELECT * FROM artist WHERE id = '$uuid'";
        $result = $this->connection->query($sql_query);

        return $result;
    }
}

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
    $testModel = new TestModel();

    $all_artist = $testModel->getAllArtist();
    $artist = $testModel->getArtistById("0211306f-c841-4545-80f3-75e88cbc04bd");

    print_r($all_artist->fetch_all());
    echo "<br>";
    echo "<br>";
    echo "<br>";
    print_r($artist->fetch_assoc());
    ?>
</body>

</html>
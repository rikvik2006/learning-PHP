<?php

declare(strict_types=1);

require_once './PDOConnection.php';

// Select from MyGuests 
echo "<table style='border: solid 1px black;'>";
echo "<tr><th>Id</th><th>Firstname</th><th>Lastname</th></tr>";

class TableRows extends RecursiveIteratorIterator
{
    function __construct($it)
    {
        parent::__construct($it, self::LEAVES_ONLY);
    }

    public function current()
    {
        return "<td style='width:150px;border:1px solid black;'>" . parent::current() . "</td>";
    }

    public function beginChildren()
    {
        echo "<tr>";
    }

    public function endChildren()
    {
        echo "</tr>" . "\n";
    }
}

// Use database PDO
echo "<h1>PDO Use Database</h1>";
try {
    $conn->exec("USE MyGuests");
    echo "Database selected successfully";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// PDO Insert data
echo "<h1>PDO Insert Data</h1>";
try {
    $sql = "INSERT INTO MyGuests (firstname, lastname, email)
    VALUES 
    ('John', 'Doe', 'john@example.com'),
    ('Riccardo', 'Bussano', 'r@r.com'),
    ('Vittorio', 'Bussano', 'v@v.com'),
    ('Giovanni', 'Lattuga', 'giovanni.lattuga@gmail.com')
    ";

    $conn->exec($sql);
    echo "New record created successfully";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}


// Select PDO
echo "<h1>PDO Select</h1>";
try {
    // La differenza tra $conn->exec() è che $conn->prepare() ritorna un oggetto PDOStatement, in questo modo possiamo usare il metodo execute() per eseguire la query e poi fetchAll() per ottenere il risultato.
    // Inoltre protegge la query da SQL injection
    $stmt = $conn->prepare("SELECT id, firstname, lastname FROM MyGuests");
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    foreach (new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k => $v) {
        echo $v;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
echo "</table>";

<?php

declare(strict_types=1);

require_once './mysqliConnection.php';

// Create database
echo "<h1>Creazione database</h1>";
$sql = "CREATE DATABASE IF NOT EXISTS MyGuests";

if ($conn->query($sql) === TRUE) {
    echo "Database creato con sucesso";
} else {
    echo "Errore durante la creazione del database: " . $conn->error;
}

// Use database
echo "<h1>Seleziono il database</h1>";
$sql = "USE MyGuests";

$resut = $conn->query($sql);

if ($resut === TRUE) {
    echo "Database selezionato con sucesso";
} else {
    echo "Errore durante la selezione del database: " . $conn->error;
}

// Create table
echo "<h1>Creazione tabella MyGuests</h1>";
$sql = "CREATE TABLE IF NOT EXISTS MyGuests (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
firstname VARCHAR(30) NOT NULL,
lastname VARCHAR(30) NOT NULL,
email VARCHAR(50),
reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table MyGuests creata con sucesso";
} else {
    echo "Errore durante la creazione della tabella: " . $conn->error;
}

// Insert data
echo "<h1>Inserimento dati tabella MyGuests</h1>";
$sql = "INSERT INTO MyGuests (firstname, lastname, email)
VALUES 
('John', 'Doe', 'john@example.com'),
('Riccardo', 'Bussano', 'r@r.com'),
('Vittorio', 'Bussano', 'v@v.com'),
('Giovanni', 'Lattuga', 'giovanni.lattuga@gmail.com')
";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Select data
echo "<h1>Select tabella MyGuests</h1>";
$sql = "SELECT id, firstname, lastname FROM MyGuests";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    echo "<h3>Var dump di result</h3>";
    var_dump($result);
    echo "<br>";
    echo "<br>";
    // print_r($row);
    while ($row = $result->fetch_assoc()) {
        // echo "<br>";
        echo "id: " . $row["id"] . " - Name: " . $row["firstname"] . " " . $row["lastname"] . "<br>";
    }
} else {
    echo "0 results";
}

// Update data
echo "<h1>Update tabella MyGuests</h1>";
$sql = "UPDATE MyGuests SET lastname='Doe' WHERE firstname='Riccardo' AND lastname='Bussano'";

if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}

echo "<h3>Select Tabella</h3>";
$sql = "SELECT id, firstname, lastname FROM MyGuests";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"] . " - Name: " . $row["firstname"] . " " . $row["lastname"] . "<br>";
    }
} else {
    echo "0 results";
}

// Delete data
echo "<h1>Delete tabella MyGuests</h1>";
$sql = "DELETE FROM MyGuests";

if ($conn->query($sql) === TRUE) {
    echo "Tabella MyGuests svuotata con sucesso <br>";
    echo "La tabella viene svuotata ma gli id continuano a incrementare dallo stesso punto di prima (dobbiamo utilizzare TRUNCATE se volessimo resettare gli id)";
} else {
    echo "Errore durante lo svuotamento di MyGuests: " . $conn->error;
}

$conn->close();

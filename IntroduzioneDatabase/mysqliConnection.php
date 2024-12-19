<?php

declare(strict_types=1);


$servername = "localhost";
$username = "root";
$password = "";

// Create connection
echo "<h1>Connessione al database</h1>";
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    echo "Errore durante la connessione al database: " . $conn->connect_error;
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully";

<?php

declare(strict_types=1);


$servername = "localhost";
$username = "root";
$password = "";
$database = "bussanoriccardo";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    echo "Errore durante la connessione al database: " . $conn->connect_error;
    die("Connection failed: " . $conn->connect_error);
}

<?php
$user = "root";
$pass = "";
$host = "localhost";
$db = "localita";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

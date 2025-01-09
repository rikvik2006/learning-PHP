<?php
// This file is the database connection, this file will be imported by other modules to connect to the database

$servername = "localhost";
$username = "root";
$password = "";
$database = "BUSSANORICCARDO";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    // Same as exit, terminate the script printing to stdout a message or status code 
    die("Connection failed:" . $conn->connect_error);
}

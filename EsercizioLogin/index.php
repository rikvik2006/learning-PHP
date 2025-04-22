<?php

declare(strict_types=1);
require_once "mysqliConnection.php";


function sanitize_input(string $data): string
{
    return htmlspecialchars(stripslashes(trim($data)));
}


$username = "rikvik2006";
$password = "NotMyRealPassword";

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
echo $hashedPassword . "<br>";

// Get the user from the database
$sqlQuery = "SELECT * FROM user WHERE username = '$username'";

$result = $conn->query($sqlQuery);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    print_r($user);
    echo "<br>";

    if (password_verify($password, $user['password'])) {
        echo "Utente trovato";
    } else {
        echo "Password errata";
    }
} else {
    echo "Utente non trovato";
}

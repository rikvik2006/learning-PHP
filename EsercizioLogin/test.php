<?php

declare(strict_types=1);
require_once "mysqliConnection.php";


function sanitize_input(string $data): string
{
    return htmlspecialchars(stripslashes(trim($data)));
}


$sqlQuery = "SELECT * FROM user";

$result = $conn->query($sqlQuery);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
</head>

<body>
    <h1>Users</h1>
    <table>
        <tr>
            <th>Username</th>
            <th>Password</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($user = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $user["username"] . "</td>";
                echo "<td>" . $user["password"] . "</td>";
                echo "</tr>";
            }
        }
        ?>
    </table>
</body>

</html>
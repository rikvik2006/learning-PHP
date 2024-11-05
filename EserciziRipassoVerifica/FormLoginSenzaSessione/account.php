<?php

declare(strict_types=1);

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: index.php");
    exit;
}

$accounts = [
    [
        "email" => "ziopera@gmail.com",
        "password" => "Banana05",
    ],
    [
        "email" => "rikvik2006@gmail.com",
        "password" => "Banana05",
    ]
];

function sanitize_input(string $data): string
{
    return htmlspecialchars(stripslashes(trim($data)));
}

if (!isset($_POST["email"]) || !isset($_POST["password"])) {
    header("Location: index.php?loginerror=Insert the required data");
    exit;
}

$email = sanitize_input($_POST["email"]);
$password = sanitize_input($_POST["password"]);

if (empty($email) || empty($password)) {
    header("Location: index.php?loginerror=Insert data");
    exit;
}

$isFound = false;
foreach ($accounts as $acc) {
    if ($acc["email"] == $email && $acc["password"] == $password) {
        $isFound = true;
        break;
    }
}

if (!$isFound) {
    header("Location: index.php?loginerror=Incorrect mail and password");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account of <?php echo $email ?></title>
</head>

<body>
    <div>Email: <?php echo $email ?></div>
    <div>Password: <?php echo $password ?></div>
</body>

</html>
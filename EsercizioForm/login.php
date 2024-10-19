<?php
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: index.php");
    exit;
}
require "loginUtils.php";
session_start();

$username = $_POST["username"];
$password = $_POST["password"];

if (isset($_POST["html"])) {
    $html = true;
} else {
    $html = false;
}

if (isset($_POST["php"])) {
    $php = true;
} else {
    $php = false;
}

if (isset($_POST["asp"])) {
    $asp = true;
} else {
    $asp = false;
}

if (isset($_POST["obj"])) {
    $obj = true;
} else {
    $obj = false;
}

$found = false;
foreach ($accounts as $acc) {
    if ($acc["username"] == $username and $acc["password"] == $password) {
        $found = true;

        // Aggiorna le preferenze dell'utente nel array $accounts
        $acc["preferences"] = [];
        if ($html) {
            $acc["preferences"][] = "html";
        }
        if ($php) {
            $acc["preferences"][] = "php";
        }
        if ($asp) {
            $acc["preferences"][] = "asp";
        }
        if ($obj) {
            $acc["preferences"][] = "oggetti multimediali";
        }
    }
}

if ($found) {
    $_SESSION["user"] = ["username" => $username];
    header("Location: dashboard.php");
    exit();
} else {
    $_SESSION["error"] = "Username e password non coincidono";
    header("Location: index.php");
    exit();
}

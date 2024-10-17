<?php

declare(strict_types=1);
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }


    $username = test_input($_POST["username"]);
    $password = test_input($_POST["password"]);
    $repeatPassword = test_input($_POST["repeatPassword"]);

    $policy = $_POST["policy"];
    if (!$policy) {
        $policy = false;
    } else {
        $policy = true;
    }

    if ($password !== $repeatPassword) {
        // Show error message on index.php
        header("Location: index.php?error=passswordNotMatch");
        exit();
    }

    if (!$policy) {
        header("Location: index.php?error=policyNotAccepted");
        exit();
    }
    ?>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Stile in caso mancasse il file CSS -->
    <style>
        .font-size {
            font-size: 2rem;
        }

        .error {
            color: red;
        }

        .image {
            max-height: 50rem;
        }
    </style>
    <link rel="stylesheet" href="style.css">
    <title><?php echo $username ?> Personal Page</title>
</head>

<body>
    <div class="container column">
        <h1>Info:</h1>
        <div>
            <p>Username: <?php echo $username ?></p>
            <p>Password: <?php echo $password ?></p>
            <p>Repeat password: <?php echo $repeatPassword ?></p>
            <p>Privacy Policy accepted: <?php echo $policy ?></p>
        </div>
    </div>
</body>

</html>
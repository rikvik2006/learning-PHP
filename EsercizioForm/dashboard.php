<?php

declare(strict_types=1);
require "loginUtils.php";

session_start();
$isLoggedIn = deserialize_user();

if (!$isLoggedIn) {
    header("Location: index.php");
    exit();
}

$user = $GLOBALS["user"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap");

        * {
            font-family: "Inter", sans-serif;
        }
    </style>

    <title><?php echo $user["username"] . " Dashboard"; ?></title>
</head>

<body class="bg-zinc-950 text-neutral-50">
    <div class="h-screen w-full p-4">
        <div class="flex flex-col gap-4">
            <h1 class="text-4xl font-bold">Dashboard di <?php echo $user["username"]; ?></h1>
            <h2 class="text-3xl font-semibold">Informazioni utente</h2>

            <table>
                <thead>
                    <tr class="border border-stone-800">
                        <th>Username</th>
                        <th>Password </th>
                        <th>Preferenze</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border border-stone-800 text-center">
                        <td>
                            <?php echo $user["username"] ?>
                        </td>
                        <td>
                            <?php echo $user["password"] ?>
                        </td>
                        <td class="flex justify-center">
                            <ul>
                                <?php foreach ($user["preferences"] as $pref): ?>
                                    <li class="list-disc"> <?php echo $pref ?></li>
                                <?php endforeach ?>
                            </ul>

                        </td>
                    </tr>
                </tbody>
            </table>
            <form action="logout.php" method="POST">
                <button class="w-24 h-9 bg-[#ffc8dd] rounded-[5px] text-zinc-950 font-semibold text-sm mt-4" submit">Logout</button>
            </form>
        </div>
    </div>
</body>

</html>
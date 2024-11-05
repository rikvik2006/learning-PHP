<?php

declare(strict_types=1);

function sanitize_input(string $data): string
{
    return htmlspecialchars(stripslashes(trim($data)));
}
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

    <title>Login senza Sessione</title>
</head>

<body class="h-screen w-full flex justify-center items-center">
    <div class="w-[400px] border rounded-[10px] border-zinc-200 p-4" action="account.php" method="post">
        <div class="flex flex-col justify-center items-center">
            <h1 class="text-4xl font-bold my-4 text-zinc-900">Login</h1>
        </div>
        <form class="flex flex-col gap-6" action="account.php" method="post">
            <div class="flex flex-col gap-1">
                <label class="text-slate-500 text-sm" for="email">Email</label>
                <input class="w-full border-zinc-200 border text-sm py-2 px-3 text-zinc-900 placeholder:text-slate-500 rounded-lg outline-none focus:ring-1 focus:ring-indigo-600 focus:border-blue-600" type="text" id="email" name="email" placeholder="riccardo@gmail.com">
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-slate-500 text-sm" for="password">Password</label>
                <input class="w-full border-zinc-200 border text-sm py-2 px-3 text-zinc-900 placeholder:text-slate-500 rounded-lg outline-none focus:ring-1 focus:ring-indigo-600 focus:border-blue-600" type="password" id="password" name="password" placeholder="**********">
            </div>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["loginerror"])) {
                echo "<div class='w-full text-center text-rose-500 text-sm'>" . sanitize_input($_GET["loginerror"]) . "</div>";
            }
            ?>
            <div>
                <button class="w-full font-semibold bg-indigo-600 text-white py-2 px-3 text-sm rounded-lg" type="submit">Submit</button>
            </div>
        </form>
    </div>
</body>

</html>
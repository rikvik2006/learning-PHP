<?php

declare(strict_types=1);
require "loginUtils.php";

session_start();
$isLoggedIn = deserialize_user();
if ($isLoggedIn) {
    header("Location: dashboard.php");
    exit();
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

    <title>Login Form</title>
</head>

<!-- #bde0fe #c1121f #ffc8dd-->

<body class="bg-zinc-950 text-neutral-50">
    <div class="h-screen flex flex-col items-center justify-center">
        <div class="w-[400px] flex flex-col border rounded-[10px] border-stone-800">
            <div class="flex flex-col justify-center items-center p-6 pb-0">
                <h1 class="font-bold text-4xl my-4">Login</h1>
                <div class="text-zinc-500 text-sm">Effettua il login per usufurire dei vantaggi</div>
            </div>
            <form class="p-6" action="./login.php" method="POST">
                <div class="flex flex-col gap-2 mb-4">
                    <label for="username">Username</label>
                    <input class="w-full h-9 text-sm bg-transparent p-2 border border-solid border-zinc-800 outline-none rounded-[5px] focus:outline-none focus:ring focus:ring-[#ffc8ddbb]" type="text" name="username" id="username" placeholder="GiovanniLattuga">
                </div>
                <div class="flex flex-col gap-2 mb-4">
                    <label for="password">Password</label>
                    <input class="w-full h-9 text-sm bg-transparent p-2 border border-solid border-zinc-800 outline-none rounded-[5px] focus:outline-none focus:ring focus:ring-[#ffc8ddbb] " type="password" name="password" id="password" placeholder="*******">
                    <?php
                    if (isset($_SESSION["error"])) {
                        echo "<div class='text-sm text-red-400'>" . htmlspecialchars($_SESSION["error"]) . "</div>";
                        unset($_SESSION["error"]);
                    }
                    ?>
                </div>
                <div>
                    <p class="my-1">Scegli le tue preferenze</p>
                    <div class="flex flex-row gap-2 text-sm">
                        <input class="accent-[#ffc8dd] hover:accent-pink-300" type="checkbox" name="html" id="html">
                        <label for="html">HTML</label>
                    </div>
                    <div class="flex flex-row gap-2">
                        <input class="accent-[#ffc8dd] hover:accent-pink-300" type="checkbox" name="php" id="php">
                        <label for="php">PHP</label>
                    </div>
                    <div class="flex flex-row gap-2">
                        <input class="accent-[#ffc8dd] hover:accent-pink-300" type="checkbox" name="asp" id="asp">
                        <label for="asp">ASP</label>
                    </div>
                    <div class="flex flex-row gap-2">
                        <input class="accent-[#ffc8dd] hover:accent-pink-300" type="checkbox" name="obg" id="obg">
                        <label for="obg">Oggetti multimediali</label>
                    </div>
                </div>
                <form action="login.php" method="POST">
                    <button type="submit" class="w-full h-9 bg-[#ffc8dd] rounded-[5px] text-zinc-950 font-semibold text-sm mt-4" submit">Login</button>
                </form>
            </form>
        </div>
</body>

</html>
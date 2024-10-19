<?php
$accounts = [
    [
        "username" => "GiovanniLattuga",
        "password" => "Banana05",
        "preferences" => ["html", "php"]
    ],
    [
        "username" => "PietroPomodoro",
        "password" => "654321",
        "preferences" => ["html", "asp"]
    ],
    [
        "username" => "CarloCipolla",
        "password" => "abcdef",
        "preferences" => ["html", "php", "oggetti multimediali"]
    ],
    [
        "username" => "admin",
        "password" => "admin",
        "preferences" => ["html", "php", "asp", "oggetti multimediali"]
    ]
];

// Deserializziamo la sessione (Cerchiamo lo username del utente al interno del "database" degli utenti)
// Se l'utente ha la sessione di login la funzione restituisce true (quindi non dobbiamo redirectare per esempio) altrimenti se non ha la sessione di login restituisce false
function deserialize_user(): bool
{
    // Il client non ha inviato nessuna sessione
    if (!isset($_SESSION["user"]) or !isset($_SESSION["user"]["username"])) {
        return false;
    }

    // Prendiamo il dato salvato nella sessione (username del utente)
    $username = $_SESSION["user"]["username"];
    global $accounts;
    $found = false;

    // echo $username . "<br>";
    // Dato l'ho username salvato nella sessione cerchiamo nel "database" l'utente per vedere se esiste
    foreach ($accounts as $acc) {
        if ($acc["username"] == $username) {
            // print_r($acc);
            // echo "<br>";
            $found = true;
            // Assegniamo l'utnte ad una variabile super globale, cosi possiamo accederci ovunque dopo che l'utente è stato deserializzato
            $GLOBALS["user"] = $acc;
        }
    }

    // echo $found;

    if ($found) {
        return true;
    } else {
        return false;
    }
}

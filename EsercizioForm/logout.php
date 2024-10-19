<?php
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: index.php");
    exit();
}

session_start();
session_unset();  // Rimuove dallo script in eseguzione le varaibili di session ($_SESSION)
session_destroy();  // Distrugge la seessione completamente dal server (Elimina il file che ha come nome l'id della sessione e il relativo contenuto)
// Nessuna delle due funzioni elimina il cookie contenente la sessione sul client, ma tanto ora, quel SessioID (valore salvato nel cookie sul client) non sarà mai più associato a nessuna sessione sul server.
header("Location: index.php");
exit();

<?php

declare(strict_types=1);

require_once __DIR__ . "/config/db.php";

// Singleton pattern per la connessione al database
class DatabaseConnection
{
    private static ?mysqli $connection = null;

    private function __construct() {} // Impedisce l'istanza diretta

    public static function getInstance(): mysqli
    {
        if (self::$connection === null) {
            global $DB_HOST, $DB_USER, $DB_PASS, $DB_NAME;
            self::$connection = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

            if (self::$connection->connect_error) {
                throw new Exception('Connection failed: ' . self::$connection->connect_error);
            }
        }

        return self::$connection;
    }

    public static function close(): void
    {
        if (self::$connection !== null) {
            self::$connection->close();
            self::$connection = null;
        }
    }
}

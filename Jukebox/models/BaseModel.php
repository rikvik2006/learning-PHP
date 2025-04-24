<?php

declare(strict_types=1);

require_once __DIR__ . "/DatabaseConnection.php";

/**
 * Base class Model for creating Model, this class get the database connection from the sigleton
 */
class BaseModel
{
    protected mysqli $connection;

    public function __construct()
    {
        $this->connection = DatabaseConnection::getInstance();
    }
}

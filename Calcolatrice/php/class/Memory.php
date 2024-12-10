<?php

declare(strict_types=1);

// Sigleton class to manage memory
class Memory
{
    private static Memory $instance;

    private function __construct()
    {
        if (!isset($_SESSION['memory'])) {
            $_SESSION['memory'] = 0;
        }
    }

    public static function create(): Memory
    {
        if (!isset(self::$instance)) {
            self::$instance = new Memory();
        }
        return self::$instance;
    }

    public function setMemory(float $value): void
    {
        $_SESSION['memory'] = $value;
    }

    public function getMemory(): float
    {
        return $_SESSION['memory'];
    }

    public function hasMemory(): bool
    {
        if ($this->getMemory() !== 0) {
            return true;
        } else {
            return false;
        }
    }

    public function clearMemory(): void
    {
        $_SESSION['memory'] = 0;
    }
}

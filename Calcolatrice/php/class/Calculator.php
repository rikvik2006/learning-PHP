<?php

declare(strict_types=1);

require_once "./Memory.php";

class Calculator
{
    private BlocksManager $blocksManager;

    public function __construct()
    {
        $this->blocksManager = new BlocksManager();
    }

    public function getBlocksManager(): BlocksManager
    {
        return $this->blocksManager;
    }

    // Esempio di metodo per eseguire calcoli (da implementare)
    public function calculate(): float
    {
        $blocks = $this->blocksManager->getBlocks();
        // Implementa la logica per calcolare il risultato in base ai blocchi
        return 0.0; // Placeholder
    }
}

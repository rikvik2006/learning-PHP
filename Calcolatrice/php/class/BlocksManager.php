<?php

declare(strict_types=1);

class BlocksManager
{
    // The blocks that are stored inside the class
    private array $blocks = [];

    public function addBlock(Block $block): void
    {
        $this->blocks[] = $block;
    }

    public function getBlocks(): array
    {
        return $this->blocks;
    }
}

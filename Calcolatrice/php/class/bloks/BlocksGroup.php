<?php
require_once 'Blocks.php';

// This class is used to group multiple blocks together
// It is matematically equivalent to parentesis
// This class is a block itself and can be used in the same way as a single block
class BlocksGroup extends Block
{
    private array $blocks = [];

    public function __construct(array $blocks)
    {
        $this->setBlocks($blocks);
    }

    public function setBlocks(array $blocks): void
    {
        foreach ($blocks as $block) {
            if (!($block instanceof Block)) {
                throw new Exception("All elements must be instances of Block");
            }
        }

        $this->blocks = $blocks;
    }

    public function getBlocks(): array
    {
        return $this->blocks;
    }

    public function addBlock(Block $block): void
    {
        $this->blocks[] = $block;
    }

    public function getBlock(int $index): Block
    {
        return $this->blocks[$index];
    }

    public function removeBlock(int $index): void
    {
        unset($this->blocks[$index]);
    }

    public function getBlocksCount(): int
    {
        return count($this->blocks);
    }

    // Mathematicall rappresentation of the block
    public function getValue(): string
    {
        // Get the value of each block and concatenate them, inside parentesis
        $values = array_map(fn($block) => $block->getValue(), $this->blocks);
        return "(" . implode(" ", $values) . ")";
    }
}

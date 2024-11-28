<?php

declare(strict_types=1);


class Block
{
    // String rappresetation of the value of the block
    private string $value = "";
}

class NumberBlock
{
    // The number that is stored insiede the class
    private float $number = 0;

    public function getNumber(): float
    {
        return $this->number;
    }

    public function setNumber(float $number): void
    {
        if (!is_numeric($number)) {
            throw new Exception("insert a valid number");
        }

        $this->number = $number;
    }
}

<?php

declare(strict_types=1);


abstract class Block
{
    //The value that is stored inside the block
    /**
     * Return a mixed value that rappresent the block in a mathematical way that PHP can evaluate
     * @return mixed
     */
    abstract public function getValue(): mixed;
}

class NumberBlock extends Block
{
    // The number that is stored insiede the class
    private float $number = 0;

    public function __construct(float $number)
    {
        $this->setValue($number);
    }

    public function getValue(): float
    {
        return $this->number;
    }

    public function setValue(float $number): void
    {
        if (!is_numeric($number)) {
            throw new Exception("insert a valid number");
        }

        $this->number = $number;
    }
}

class DecimalPointBlock extends Block
{
    // The number that is stored insiede the class
    private string $decimalPoint = ".";

    public function getValue(): string
    {
        return $this->decimalPoint;
    }
}

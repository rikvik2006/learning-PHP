<?php

declare(strict_types=1);

require_once 'Blocks.php';

class OperationBlock extends Block
{
    // The operation that is stored inside the class
    private string $operation = "";

    public function __construct(string $operation)
    {
        $this->setValue($operation);
    }

    public function getValue(): string
    {
        return $this->operation;
    }

    protected function setValue(string $operation): void
    {
        if ($operation !== "+" && $operation !== "-" && $operation !== "*" && $operation !== "/") {
            throw new Exception("insert a valid operation");
        }

        $this->operation = $operation;
    }
}

class SumBlock extends OperationBlock
{
    public function __construct()
    {
        parent::__construct("+");
    }
}

class SubtractionBlock extends OperationBlock
{
    public function __construct()
    {
        parent::__construct("-");
    }
}

class MultiplicationBlock extends OperationBlock
{
    public function __construct()
    {
        parent::__construct("*");
    }
}

class DivisionBlock extends OperationBlock
{
    public function __construct()
    {
        parent::__construct("/");
    }
}

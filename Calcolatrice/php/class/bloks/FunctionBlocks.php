<?php

declare(strict_types=1);

require_once 'Blocks.php';

abstract class FunctionBlock extends Block
{
    // The function that is stored inside the class
    private string $functionName = "";
    private string $functionStringExpression = "";

    public function __construct(string $functionName, string $functionStringExpression)
    {
        $this->setFunctionName($functionName);
        $this->setfunctionStringExpression($functionStringExpression);
    }

    public function getFunctionName(): string
    {
        return $this->functionName;
    }

    public function setFunctionName(string $functionName): void
    {
        $this->functionName = $functionName;
    }

    private function checkBlockHolderPlaceHolder(array $validBlockHolder): bool
    {
        foreach ($validBlockHolder as $blockHolder) {
            if (strpos($this->functionStringExpression, $blockHolder) !== false) {
                return true;
            }
        }
        return false;
    }

    public function setfunctionStringExpression(string $functionStringExpression): void
    {
        if ($this->checkBlockHolderPlaceHolder(["<blockholder>", "<blockholder_base>", "<blockholder_exponent"])) {
            $this->functionStringExpression = $functionStringExpression;
        } else {
            throw new Exception("the function string expression doen't cotain at leas on placeholder <blockholder>");
        }
    }

    public function getFunctionStringExpression(): string
    {
        return $this->functionStringExpression;
    }

    abstract public function getValue(): string;
}

// TODO: sin, cos, tan, sqrt, sqrt n, 

class SinBlock extends FunctionBlock
{
    private Block $argument;
    private string $functionValue = "";

    public function __construct(Block $argument)
    {
        parent::__construct("sin", "sin(<blockholder>)");
        $this->setValue($argument);
    }

    public function getValue(): string
    {
        return $this->functionValue;
    }

    public function setValue(Block $argument): void
    {
        $this->argument = $argument;
        $this->functionValue = str_replace("<blockholder>", (string)$argument->getValue(), $this->getFunctionStringExpression());
    }
}

class PowerBlock extends FunctionBlock
{
    private Block $base;
    private block $exponent;
    private string $functionValue = "";

    public function __construct(Block $base, Block $exponent)
    {
        parent::__construct("power", "<blockholder_base>**<blockholder_exponent>");
        $this->setValue($base, $exponent);
    }

    public function getValue(): string
    {
        return $this->functionValue;
    }

    public function setValue(Block $base, Block $exponent): void
    {
        $this->base = $base;
        $this->exponent = $exponent;
        $this->functionValue = str_replace("<blockholder_base>", (string)$base->getValue(), $this->getFunctionStringExpression());
        $this->functionValue = str_replace("<blockholder_exponent>", (string)$exponent->getValue(), $this->functionValue);
    }
}

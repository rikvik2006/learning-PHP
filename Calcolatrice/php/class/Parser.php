<?php

declare(strict_types=1);

// 5;+;3;-;\sqrt[2]{5};*;5^{2}
// \sin{5};+;\cos{7.2};+;\tan{-3}


require_once __DIR__ . "./blocks/Blocks.php";
require_once __DIR__ . "./blocks/FunctionBlocks.php";
require_once __DIR__ . "./blocks/OperationBloks.php";
require_once __DIR__ . "./blocks/BlocksGroup.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "../utils/factorialFunction.php";

class Parser
{
    /**
     * Parse a protocol string expression and return an array of blocks
     */
    public static function parse(string $expression): array
    {
        $blocks = explode(';', $expression);
        $objectBlocks = [];

        // print_r($blocks);
        // echo "<br>";
        // print(array_map("var_dump", $blocks));
        // echo "<br>";
        // echo "<br>";
        foreach ($blocks as $block) {
            if (is_numeric($block)) {
                $objectBlocks[] = new NumberBlock((float) $block);
            } elseif (in_array($block, ['+', '-', '*', '/'])) {
                if ($block == "+") {
                    $objectBlocks[] = new SumBlock();
                } elseif ($block == "-") {
                    $objectBlocks[] = new SubtractionBlock();
                } elseif ($block == "*") {
                    $objectBlocks[] = new MultiplicationBlock();
                } elseif ($block == "/") {
                    $objectBlocks[] = new DivisionBlock();
                }
            } elseif (preg_match('/^\\\\sqrt\\[[0-9]+\\]{[0-9]+}$/', $block)) {
                // RADICE ENNESIMA

                $sqrtIndex = (float)substr($block, 6, -1);
                $indexStartArgument = strpos($block, "{") + 1;
                $sqrtArgument = (float)substr($block, $indexStartArgument, -1);

                if (($sqrtIndex % 2 == 0) && $sqrtArgument < 0) {
                    throw new Exception("Impossibile calcolare la radice di un numero negativo con indice pari");
                }

                $objectBlocks[] = new PowerBlock(new NumberBlock($sqrtArgument), new BlocksGroup([new NumberBlock(1), new DivisionBlock(), new NumberBlock($sqrtIndex)]));
            } elseif (preg_match('/^[0-9]+\\^{[0-9]+}$/', $block)) {
                // POTENZA ENNESIMA

                $powerBase = (float)substr($block, 0, -1);
                $exponentIndex = strpos($block, "{") + 1;
                $powerExponent = (float)substr($block, $exponentIndex, -1);

                $objectBlocks[] = new PowerBlock(new NumberBlock($powerBase), new NumberBlock($powerExponent));
            } elseif (preg_match('/^\\\\sin{[0-9]+}$/', $block)) {
                // SENO
                $sinArgument = (float)substr($block, 5, -1);
                $radiantSinArgument = deg2rad($sinArgument);

                $objectBlocks[] = new SinBlock(new NumberBlock($radiantSinArgument));
            } elseif (preg_match('/^\\\\cos{[0-9]+}$/', $block)) {
                // COSENO
                $cosArgument = (float)substr($block, 5, -1);
                $radiantCosArgument = deg2rad($cosArgument);

                $objectBlocks[] = new CosBlock(new NumberBlock($radiantCosArgument));
            } elseif (preg_match('/^\\\\tan{[0-9]+}$/', $block)) {
                // TANGENTE
                $tanArgument = (float)substr($block, 5, -1);

                // Check if the angle is a multiple of 90
                if ($tanArgument % 90 == 0) {
                    throw new Exception("Impossibile calcolare la tangente di un angolo multiplo di 90 non è definito");
                }

                $radiantTanArgument = deg2rad($tanArgument);

                $objectBlocks[] = new TanBlock(new NumberBlock($radiantTanArgument));
            } else if (preg_match('/^\\\\fact{[0-9]+}$/', $block)) {
                // FATTORIALE
                $factArgument = (float)substr($block, 6, -1);

                if ($factArgument < 0) {
                    throw new Exception("Impossibile calcolare il fattoriale di un numero negativo");
                }

                if ($factArgument != floor($factArgument)) {
                    throw new Exception("Impossibile calcolare il fattoriale di un numero decimale");
                }

                $factorialResult = fact((int)$factArgument);

                $objectBlocks[] =  new NumberBlock($factorialResult);
            } else {
                print_r($block);
                echo "<br>";
                throw new Exception("Invalid block");
            }
        }

        return $objectBlocks;
    }

    /**
     * Parse the array of blocks that represent the expression and return the expression as a PHP evaluable string
     */
    public static function parseExpression(array $blocks): string
    {
        $expression = "";

        foreach ($blocks as $block) {
            $expression .= $block->getValue() . " ";
        }

        return $expression;
    }

    /**
     * Validate if the expression has adiacent operators
     */
    public static function validateAdiacentOperator(string $expression): bool
    {
        $blocks = explode(';', $expression);

        // check if there are more than on operator in a row
        for ($i = 0; $i < count($blocks) - 1; $i++) {
            if (in_array($blocks[$i], ['+', '-', '*', '/']) && in_array($blocks[$i + 1], ['+', '-', '*', '/'])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Resolve a PHP avaluable expresssion and return the result   
     * */
    public static function evaluate(string $phpExpression): float
    {
        $result = eval("return $phpExpression;");

        return $result;
    }
}

<?php

declare(strict_types=1);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once __DIR__ . "/../../class/blocks/Block.php";
    require_once __DIR__ . "/../../class/blocks/NumberBlock.php";
    require_once __DIR__ . "/../../class/blocks/OperationBlock.php";
    require_once __DIR__ . "/../../class/blocks/FunctionBlock.php";
    require_once __DIR__ . "/../../class/calculator/Calculator.php";

    $calculator = new Calculator();

    $input = $_POST["input"];
    $input = str_replace(" ", "", $input);

    $input = str_split($input);

    $blocks = [];

    foreach ($input as $char) {
        if (is_numeric($char)) {
            $block = new NumberBlock();
            $block->setNumber((float)$char);
            $blocks[] = $block;
        } else {
            if ($char === "+" || $char === "-" || $char === "*" || $char === "/") {
                $block = new OperationBlock();
                $block->setOperation($char);
                $blocks[] = $block;
            } else {
                $block = new FunctionBlock();
                $block->setFunction($char);
                $blocks[] = $block;
            }
        }
    }

    $result = $calculator->calculate($blocks);

    echo json_encode($result);
}

<?php

declare(strict_types=1);

session_start();

require_once '../class/Parser.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . "../class/Memory.php";

// Get the memory instance singleton
$memory = Memory::create();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../index.php');
    exit;
}

if (!isset($_POST['expression'])) {
    header('Location: ../../index.php');
    exit;
}

try {
    $frontEndExpression = $_POST['expression'];
    $store = $_POST['sto'];
    echo $store;

    echo "<br>";
    echo "🔢" . $frontEndExpression;
    echo "<br>";
    $objectBlocks = Parser::parse($frontEndExpression);

    // echo $frontEndExpression;
    // echo "<br>";

    $phpEvaluableExpression = Parser::parseExpression($objectBlocks);
    echo $phpEvaluableExpression;
    echo "<br>";


    // echo $phpEvaluableExpression;
    // echo "<br>";

    if ($store == "recall") {
        $phpEvaluableExpression = $phpEvaluableExpression . $memory->getMemory();
    }

    if ($store != "recall") {
        $result = Parser::evaluate($phpEvaluableExpression);
        echo $result;
    }

    $_SESSION['frontEndExpression'] = $frontEndExpression;
    if ($store == "store") {
        $memory->setMemory($result);
        echo $memory->getMemory();
    } else if ($store == "memplus") {
        $memory->setMemory($memory->getMemory() + $result);
        echo $memory->getMemory();
    } else if ($store == "recall") {
        $result = $memory->getMemory();
        $_SESSION['frontEndExpression'] = $frontEndExpression . ";" . $result;
    } else if ($store == "false") {
        echo json_encode(['result' => $result]);
        $_SESSION['result'] = $result;
    }


    header('Location: ../../index.php');
} catch (Throwable $e) {
    // TODO: gestire gli errori e visualizzarli nella calcolatrice
    $_SESSION['result'] = "";
    echo "Errore durante il parsing: " . $e->getMessage();
    $_SESSION['error'] = "Errore";
    header('Location: ../../index.php');
}

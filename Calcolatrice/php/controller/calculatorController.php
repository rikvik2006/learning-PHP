<?php

declare(strict_types=1);

require_once '../class/Parser.php';

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
    $objectBlocks = Parser::parse($frontEndExpression);

    // echo $frontEndExpression;
    // echo "<br>";

    $phpEvaluableExpression = Parser::parseExpression($objectBlocks);

    // echo $phpEvaluableExpression;
    // echo "<br>";

    $result = Parser::evaluate($phpEvaluableExpression);

    echo json_encode(['result' => $result]);
    $_SESSION['result'] = $result;
} catch (Exception $e) {
    echo "Errore durante il parsing: " . $e->getMessage();
}

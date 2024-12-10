<?php

declare(strict_types=1);

require_once __DIR__ . DIRECTORY_SEPARATOR . "../class/Parser.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../index.php');
    exit;
}

if (!isset($_POST['expression'])) {
    header('Status Code: 400 Bad Request', true, 400);
    exit;
}

try {
    $frontEndExpression = $_POST['expression'];
    $adiacentOperator = Parser::validateAdiacentOperator($frontEndExpression);

    if ($adiacentOperator) {
        echo json_encode(['error' => 'Operatori adiacenti']);
    } else {
        echo json_encode(["status" => "ok"]);
    }

    exit;
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

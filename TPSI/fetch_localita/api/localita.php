<?php
require_once '../database/databaseConnection.php';

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $query = "SELECT * FROM comuni";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        echo json_encode(array("error" => "Errore nella preparazione della query: " . $conn->error));
        exit();
    }

    $status = $stmt->execute();

    if ($status == false) {
        echo json_encode(array("error" => "Esecuzione della query fallita: " . $conn->error));
        exit();
    }

    $result = $stmt->get_result();

    if ($result) {
        $comuni = array();
        $i = 0;
        while ($row = $result->fetch_assoc()) {
            $comuni[] = $row;
            $i++;
        }

        echo json_encode($comuni);
    } else {
        echo json_encode(array("error" => "Query fallita: " . $conn->error));
    }

    $stmt->close();
} else {
    echo json_encode(array("error" => "Metodo di richiesta non valido"));
}

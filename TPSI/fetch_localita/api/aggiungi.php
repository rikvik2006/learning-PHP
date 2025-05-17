<?php
require_once '../database/databaseConnection.php';

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Verifica se tutti i campi richiesti sono presenti
    $required_fields = ['name', 'slug', 'lat', 'lng', 'codice_provincia_istat', 'codice_comune_istat', 'codice_alfanumerico_istat'];

    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            echo json_encode(array("error" => "Manca il campo obbligatorio: $field"));
            exit();
        }
    }

    // Prepara i dati per l'inserimento
    $name = $_POST['name'];
    $slug = $_POST['slug'];
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $codice_provincia_istat = $_POST['codice_provincia_istat'];
    $codice_comune_istat = $_POST['codice_comune_istat'];
    $codice_alfanumerico_istat = $_POST['codice_alfanumerico_istat'];
    $capoluogo_provincia = isset($_POST['capoluogo_provincia']) ? $_POST['capoluogo_provincia'] : 0;
    $capoluogo_regione = isset($_POST['capoluogo_regione']) ? $_POST['capoluogo_regione'] : 0;

    // Query per inserire un nuovo comune
    $query = "INSERT INTO comuni (name, slug, lat, lng, codice_provincia_istat, codice_comune_istat, 
                                 codice_alfanumerico_istat, capoluogo_provincia, capoluogo_regione) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo json_encode(array("error" => "Errore nella preparazione della query: " . $conn->error));
        exit();
    }

    $stmt->bind_param(
        "sssssssii",
        $name,
        $slug,
        $lat,
        $lng,
        $codice_provincia_istat,
        $codice_comune_istat,
        $codice_alfanumerico_istat,
        $capoluogo_provincia,
        $capoluogo_regione
    );

    $status = $stmt->execute();

    if ($status) {
        // Restituisce l'ID del comune appena inserito
        $id = $conn->insert_id;
        echo json_encode(array("success" => true, "id" => $id, "message" => "Comune aggiunto con successo"));
    } else {
        echo json_encode(array("error" => "Errore nell'inserimento del comune: " . $stmt->error));
    }

    $stmt->close();
} else {
    echo json_encode(array("error" => "Metodo di richiesta non valido. Usa POST."));
}

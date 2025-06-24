<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

include($_SERVER['DOCUMENT_ROOT'].'/includes/database.php');

$data = json_decode(file_get_contents('php://input'), true);
$phone = isset($data['phone']) ? $data['phone'] : '';

$response = ['exists' => false];

if ($phone) {
    try {
        $connection = (new Connection())->connect();

        $stmt = $connection->prepare("SELECT id_cliente, nombre, telefono FROM clientes WHERE telefono = :phone LIMIT 1");
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();

        if ($client = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $response['exists'] = true;
            $response['client'] = [
                'id' => $client['id_cliente'],
                'name' => $client['nombre'],
                'phone' => $client['telefono']
            ];
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error en la base de datos: '.$e->getMessage()]);
        exit;
    }
}

echo json_encode($response);
exit;
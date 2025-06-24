<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

include($_SERVER['DOCUMENT_ROOT'].'/includes/database.php');

$data = json_decode(file_get_contents('php://input'), true);

$name    = trim(isset($data['name']) ? $data['name'] : '');
$email   = trim(isset($data['email']) ? $data['email'] : '');
$address = trim(isset($data['address']) ? $data['address'] : '');
$phone   = trim(isset($data['phone']) ? $data['phone'] : '');

if (!$name || !$email || !$address || !$phone) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Email inválido']);
    exit;
}

try {
    $connection = (new Connection())->connect();

    $stmtCheck = $connection->prepare("SELECT id_cliente FROM clientes WHERE telefono = :phone LIMIT 1");
    $stmtCheck->bindParam(':phone', $phone);
    $stmtCheck->execute();
    if ($stmtCheck->fetch()) {
        echo json_encode(['success' => false, 'message' => 'El cliente ya existe']);
        exit;
    }

    $stmt = $connection->prepare("
        INSERT INTO clientes (nombre, telefono, correo, direccion)
        VALUES (:name, :phone, :email, :address)
    ");
    $stmt->bindParam(':name',    $name);
    $stmt->bindParam(':phone',   $phone);
    $stmt->bindParam(':email',   $email);
    $stmt->bindParam(':address', $address);
    $stmt->execute();

    $newId = $connection->lastInsertId();

    echo json_encode([
        'success' => true,
        'client' => [
            'id'      => $newId,
            'name'    => $name,
            'email'   => $email,
            'address' => $address,
            'phone'   => $phone
        ]
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error en BD: ' . $e->getMessage()]);
}
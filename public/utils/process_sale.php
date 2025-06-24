<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

include("../../includes/database.php");

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($_SESSION['datos']['id_empleado'])) {
    echo json_encode(['success' => false, 'message' => 'Empleado no identificado en sesión']);
    exit;
}
$id_empleado = $_SESSION['datos']['id_empleado'];

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'JSON inválido o no recibido']);
    exit;
}

$client_id = isset($data['client_id']) ? intval($data['client_id']) : 0;
$metodo_pago = isset($data['metodo_pago']) ? trim($data['metodo_pago']) : '';
$cart = isset($data['cart']) && is_array($data['cart']) ? $data['cart'] : [];
$total = isset($data['total']) ? floatval($data['total']) : 0;

if ($client_id <= 0 || empty($metodo_pago) || empty($cart) || $total <= 0) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos para procesar la venta']);
    exit;
}

try {
    $conn = (new Connection())->connect();
    $conn->beginTransaction();

    // Insertar en ventas
    $stmtVenta = $conn->prepare("INSERT INTO ventas (id_cliente, id_empleado, total, metodo_pago) VALUES (:id_cliente, :id_empleado, :total, :metodo_pago)");
    $stmtVenta->bindParam(':id_cliente', $client_id, PDO::PARAM_INT);
    $stmtVenta->bindParam(':id_empleado', $id_empleado, PDO::PARAM_INT);
    $stmtVenta->bindParam(':total', $total);
    $stmtVenta->bindParam(':metodo_pago', $metodo_pago);
    $stmtVenta->execute();

    $id_venta = $conn->lastInsertId();

    // Insertar detalle_venta para cada producto
    $stmtDetalle = $conn->prepare("INSERT INTO detalle_venta (id_venta, id_producto, cantidad, precio_unitario) VALUES (:id_venta, :id_producto, :cantidad, :precio_unitario)");

    foreach ($cart as $item) {
        if (!isset($item['id'], $item['quantity'], $item['price'])) {
            throw new Exception("Datos incompletos en detalle del carrito");
        }

        $stmtDetalle->bindParam(':id_venta', $id_venta, PDO::PARAM_INT);
        $stmtDetalle->bindParam(':id_producto', $item['id'], PDO::PARAM_INT);
        $stmtDetalle->bindParam(':cantidad', $item['quantity'], PDO::PARAM_INT);
        $stmtDetalle->bindParam(':precio_unitario', $item['price']);
        $stmtDetalle->execute();
    }

    // Actualizar stock del producto
    $stmtUpdateStock = $conn->prepare("UPDATE productos SET stock = stock - :cantidad WHERE id_producto = :id_producto");
    $stmtUpdateStock->bindParam(':cantidad', $item['quantity'], PDO::PARAM_INT);
    $stmtUpdateStock->bindParam(':id_producto', $item['id'], PDO::PARAM_INT);
    $stmtUpdateStock->execute();

    // Registrando en movimientos
    $stmtMovimiento = $conn->prepare("
        INSERT INTO movimientos_inventario (id_producto, id_empleado, tipo, cantidad, motivo, fecha)
        VALUES (:id_producto, :id_empleado, 'salida', :cantidad, 'Venta ID $id_venta', :fecha)
    ");

    foreach ($cart as $item) {
        $stmtMovimiento->execute([
            ':id_producto' => $item['id'],
            ':id_empleado' => $id_empleado,
            ':cantidad' => $item['quantity'],
            ':fecha' => date('Y-m-d H:i:s')
        ]);
    }

    $conn->commit();

    echo json_encode(['success' => true, 'message' => 'Venta procesada correctamente']);
} catch (Exception $e) {
    if ($conn && $conn->inTransaction()) {
        $conn->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Error al procesar venta: ' . $e->getMessage()]);
}

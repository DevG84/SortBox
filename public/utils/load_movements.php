<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../../includes/database.php");

$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
$desde = isset($_GET['desde']) ? $_GET['desde'] : '';
$hasta = isset($_GET['hasta']) ? $_GET['hasta'] : '';

try {
    $conn = (new Connection())->connect();

    $query = "SELECT m.*, p.nombre AS producto, CONCAT(e.first_name, ' ', e.last_name) AS empleado
              FROM movimientos_inventario AS m
              JOIN productos AS p ON m.id_producto = p.id_producto
              JOIN empleados AS e ON m.id_empleado = e.id_empleado";

    if ($tipo !== '') {
        $query .= " AND m.tipo = :tipo";
    }

    if ($desde !== '') {
        $query .= " AND m.fecha >= :desde";
    }

    if ($hasta !== '') {
        $query .= " AND m.fecha <= :hasta";
    }

    $stmt = $conn->prepare($query);

    if ($tipo !== '') $stmt->bindParam(':tipo', $tipo);
    if ($desde !== '') $stmt->bindParam(':desde', $desde);
    if ($hasta !== '') $stmt->bindParam(':hasta', $hasta);

    $stmt->execute();

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'data' => $rows]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

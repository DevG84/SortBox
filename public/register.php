<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (isset($_SESSION['datos'])) {
    header("Location: dashboard.php");
    exit();
}

error_reporting(E_ALL);
ini_set("display_errors", 1);

include("../includes/database.php");

$connection = (new Connection())->connect();

function handleRegistration($connection) {
    // Obtener datos del formulario
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $firstName = isset($_POST['firstName']) ? $_POST['firstName'] : '';
    $lastName = isset($_POST['lastName']) ? $_POST['lastName'] : '';
    $secondLastName = isset($_POST['secondLastName']) ? $_POST['secondLastName'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirmPassword = isset($_POST['confirmPassword']) ? $_POST['confirmPassword'] : '';
    $selectedRole = isset($_POST['selectedRole']) ? $_POST['selectedRole'] : '';
    $authUsername = isset($_POST['authUsername']) ? $_POST['authUsername'] : '';
    $authPassword = isset($_POST['authPassword']) ? $_POST['authPassword'] : '';

    $errors = [];

    // Validaciones
    if (empty($username)) $errors['username'] = "El nombre de usuario es obligatorio";
    if (empty($firstName)) $errors['firstName'] = "El nombre es obligatorio";
    if (empty($lastName)) $errors['lastName'] = "El apellido paterno es obligatorio";

    if (strlen($password) < 8) {
        $errors['password'] = "La contraseña debe tener al menos 8 caracteres";
    } elseif (!preg_match('/^[a-zA-Z0-9@_\-.#$%&!*]+$/', $password)) {
        $errors['password'] = "La contraseña contiene caracteres inválidos";
    }

    if ($password !== $confirmPassword) {
        $errors['confirmPassword'] = "Las contraseñas no coinciden";
    }

    if (empty($selectedRole)) {
        $errors['role'] = "Debe seleccionar un rol";
    }

    if (empty($authUsername) || empty($authPassword)) {
        $errors['auth'] = "Se requieren credenciales de administrador";
    } else {
        $stmt = $connection->prepare("SELECT password FROM empleados WHERE username = :authUsername AND role = 'admin'");
        $stmt->execute([':authUsername' => $authUsername]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$admin || !password_verify($authPassword, $admin['password'])) {
            $errors['auth'] = "Credenciales de administrador inválidas";
        }
    }

    // Verificar si el usuario ya existe
    if (empty($errors)) {
        $stmt = $connection->prepare("SELECT COUNT(*) FROM empleados WHERE username = :username");
        $stmt->execute([':username' => $username]);
        if ($stmt->fetchColumn() > 0) {
            $errors['username'] = "El nombre de usuario ya está registrado";
        }
    }

    if (!empty($errors)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'errors' => $errors]);
        exit;
    }

    // Insertar en la base de datos
    try {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $connection->prepare("
                INSERT INTO empleados (username, first_name, last_name, second_last_name, password, role, created_at)
                VALUES (:username, :first_name, :last_name, :second_last_name, :password, :role, NOW())
            ");

        $stmt->execute([
            ':username' => $username,
            ':first_name' => $firstName,
            ':last_name' => $lastName,
            ':second_last_name' => $secondLastName,
            ':password' => $hashedPassword,
            ':role' => $selectedRole
        ]);

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => "Usuario registrado correctamente"]);
        exit;

    } catch (PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'errors' => ['db' => "Error en la base de datos: " . $e->getMessage()]]);
        exit;
    }
}

// Llamar a la función solo si es POST y vienen datos de registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handleRegistration($connection);
}

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function authenticate() {
    if (!isset($_SESSION['datos'])) {
        header("Location: login.php");
        exit();
    }
}

function logout() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_unset();
    session_destroy();

    header("Location: login.php");
    exit();
}
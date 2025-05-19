<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function authenticate() {
    if (!isset($_SESSION['usuario'])) {
        header("Location: login.php");
        exit();
    }
}

function logout() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    session_unset();
    session_destroy();

    header("Location: login.php");
    exit();
}
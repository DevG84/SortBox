<?php
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



$connection = null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="css/singin.css" />
    <link rel="stylesheet" href="css/palette.css">
    <link rel="icon" href="img/sortbox_onlyLogo.svg" type="image/svg">
</head>
<body>

</body>
</html>

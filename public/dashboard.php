<?php
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");
    include("../includes/auth.php");
    if (isset($_POST['logout'])) {
        logout();
    }
    authenticate();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css" />
    <link rel="icon" href="img/sortbox_onlyLogo.svg" type="image/png">
</head>
<body>

    <header class="header">
        <a href="/sortbox/public/login.php"><img src="img/sortbox.svg" alt="SortBox" class="logo" /></a>
        <nav class="links">
            <a href="/sortbox/public/login.php">Inicio</a>
            <a href="/help">Ayuda</a>
        </nav>
    </header>

    <main class="form-container">
        <h1>Hola, <?php echo htmlspecialchars($_SESSION['usuario']); ?> 🎉</h1>
    </main>

    <form method="POST">
        <button type="submit" name="logout">Cerrar sesión</button>
    </form>
</body>
</html>
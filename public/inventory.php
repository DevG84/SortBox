<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
include("../includes/auth.php");
if (isset($_POST['logout'])) {
    logout();
}
authenticate();

$nombre = isset($_SESSION['datos']['nombre']) ? $_SESSION['datos']['nombre'] : '';
$apellido_p = isset($_SESSION['datos']['apellido_p']) ? $_SESSION['datos']['apellido_p'] : '';
$rolOriginal = isset($_SESSION['datos']['rol']) ? $_SESSION['datos']['rol'] : '';
$rolesTraducidos = [
    'admin' => 'Administrador',
    'supervisor' => 'Supervisor',
    'operator' => 'Operador',
    'viewer' => 'Invitado'
];
$rol = isset($rolesTraducidos[$rolOriginal]) ? $rolesTraducidos[$rolOriginal] : 'Invitado';
$iniciales = strtoupper(substr($nombre, 0, 1) . substr($apellido_p, 0, 1));

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/inventory.css" />
    <link rel="stylesheet" href="css/palette.css" />
    <link rel="icon" href="img/sortbox_onlyLogo.svg" type="image/png" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

</head>
<body class="min-h-screen flex flex-col md:flex-row">
<aside class="sidebar w-full md:w-64 md:min-h-screen p-4 flex flex-col bg-[var(--sidebar-bg)] border-r border-[var(--primary-button)]">
    <!-- Logo -->
    <div class="flex items-center justify-center mb-8">
        <img src="img/sortbox.png" alt="SortBox" class="logo" width="200"/>
    </div>

    <!-- Navegación -->
    <nav class="flex-grow">
        <ul class="space-y-1">
            <li><a href="./dashboard.php" class="sidebar-link flex items-center p-3 rounded-md"><i class="fas fa-tachometer-alt w-6"></i><span class="ml-2">Dashboard</span></a></li>
            <li><a href="./inventory.php" class="sidebar-link active flex items-center p-3 rounded-md"><i class="fas fa-box w-6"></i><span class="ml-2">Inventario</span></a></li>
            <li><a href="./sells.php" class="sidebar-link flex items-center p-3 rounded-md"><i class="fas fa-shopping-cart w-6"></i><span class="ml-2">Ventas</span></a></li>
            <li><a href="./suppliers.php" class="sidebar-link flex items-center p-3 rounded-md"><i class="fas fa-truck w-6"></i><span class="ml-2">Proveedores</span></a></li>
            <li><a href="./movements.php" class="sidebar-link flex items-center p-3 rounded-md"><i class="fas fa-exchange-alt w-6"></i><span class="ml-2">Movimientos</span></a></li>
            <li><a href="./settings.php" class="sidebar-link flex items-center p-3 rounded-md"><i class="fas fa-cog w-6"></i><span class="ml-2">Configuración</span></a></li>
        </ul>
    </nav>

    <!-- Perfil -->
    <div class="mt-auto pt-4 border-t border-[rgba(224,89,38,0.3)]">
        <div class="flex items-center p-3">
            <!-- Avatar con iniciales -->
            <div class="w-8 h-8 rounded-full bg-[var(--primary-button)] text-white flex items-center justify-center font-semibold">
                <?= $iniciales ?>
            </div>

            <!-- Nombre y rol -->
            <div class="ml-2">
                <p class="text-sm font-medium">
                    <?= htmlspecialchars($nombre . ' ' . $apellido_p) ?>
                </p>
                <p class="text-xs opacity-70">
                    <?= htmlspecialchars($rol) ?>
                </p>
            </div>

            <!-- Botón cerrar sesión -->
            <form method="POST" class="ml-auto">
                <button type="submit" name="logout" class="text-sm opacity-70 hover:opacity-100" title="Cerrar sesión">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>
</aside>
<!-- Contenido -->
<main class="flex-grow p-6">
    <!-- Contenido de la página -->
</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleSidebar = () => {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('hidden');
        };

        if (window.innerWidth < 768) {
            document.querySelector('.sidebar').classList.add('hidden');
            const header = document.querySelector('body');
            const toggleBtn = document.createElement('button');
            toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
            toggleBtn.className = 'p-2 rounded-md bg-[rgba(197,28,67,0.1)] text-[var(--primary-button)] m-2';
            toggleBtn.addEventListener('click', toggleSidebar);
            header.prepend(toggleBtn);
        }
    });
</script>

</body>
</html>
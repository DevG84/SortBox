<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

include("../includes/auth.php");
include("../includes/database.php");

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

$connection = (new Connection())->connect();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/sells.css" />
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
            <li><a href="./inventory.php" class="sidebar-link flex items-center p-3 rounded-md"><i class="fas fa-box w-6"></i><span class="ml-2">Inventario</span></a></li>
            <li><a href="./sells.php" class="sidebar-link active flex items-center p-3 rounded-md"><i class="fas fa-shopping-cart w-6"></i><span class="ml-2">Ventas</span></a></li>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Productos Disponibles -->
        <div class="lg:col-span-2 custom-card rounded-lg shadow-lg p-4">
            <h2 class="text-xl font-semibold mb-4" style="color: var(--text);">Productos Disponibles</h2>
            <div class="mb-4">
                <input type="text" id="search-product" placeholder="Buscar producto..." class="custom-input w-full px-4 py-2 rounded-md">
            </div>
            <div class="overflow-x-auto">
                <table class="custom-table w-full">
                    <thead>
                    <tr>
                        <th class="px-4 py-2 text-left rounded-tl-md">Código</th>
                        <th class="px-4 py-2 text-left">Producto</th>
                        <th class="px-4 py-2 text-left">Descripción</th>
                        <th class="px-4 py-2 text-left">Precio</th>
                        <th class="px-4 py-2 text-left">Stock</th>
                        <th class="px-4 py-2 text-left rounded-tr-md">Acción</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    try {
                        $stmt = $connection->prepare("SELECT id_producto, nombre, descripcion, precio_unitario AS precio, stock FROM productos WHERE stock > 0 ORDER BY nombre");
                        $stmt->execute();
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $codigo = htmlspecialchars($row['id_producto']);
                            $nombre = htmlspecialchars($row['nombre']);
                            $descripcion = htmlspecialchars($row['descripcion']);
                            $precio = number_format($row['precio'], 2);
                            $stock = (int)$row['stock'];

                            $dataName = htmlspecialchars($row['nombre'], ENT_QUOTES);
                            $dataPrice = (float)$row['precio'];

                            echo "<tr>
            <td class='px-4 py-2'>{$codigo}</td>
            <td class='px-4 py-2'>{$nombre}</td>
            <td class='px-4 py-2'>{$descripcion}</td>
            <td class='px-4 py-2'>\${$precio}</td>
            <td class='px-4 py-2'>{$stock}</td>
            <td class='px-4 py-2'>
                <button class='add-to-cart btn-primary px-3 py-1 rounded-md' data-id='{$codigo}' data-name=\"{$dataName}\" data-price='{$dataPrice}'>
                    <i class='fas fa-plus mr-1'></i> Agregar
                </button>
            </td>
        </tr>";
                        }
                    } catch (PDOException $e) {
                        echo "<tr><td colspan='6' class='text-red-500'>Error al cargar productos: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Carrito de Compras -->
        <div class="custom-card rounded-lg shadow-lg p-4">
            <h2 class="text-xl font-semibold mb-4" style="color: var(--text);">Carrito de Compras</h2>

            <div id="cart-items" class="mb-4 max-h-64 overflow-y-auto">
                <p id="empty-cart-message" class="text-gray-400 italic">El carrito está vacío</p>
            </div>

            <div class="border-t border-gray-700 pt-4 mb-4">
                <div class="flex justify-between mb-2">
                    <span style="color: var(--text);">Subtotal:</span>
                    <span id="subtotal" style="color: var(--text);">$0.00</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span style="color: var(--text);">IVA (16%):</span>
                    <span id="tax" style="color: var(--text);">$0.00</span>
                </div>
                <div class="flex justify-between font-bold">
                    <span style="color: var(--text);">Total:</span>
                    <span id="total" style="color: var(--text);">$0.00</span>
                </div>
            </div>

            <!-- Metodo de Pago -->
            <div class="mb-4">
                <label for="payment-method" class="block text-sm font-medium mb-1" style="color: var(--text);">Método de Pago</label>
                <select id="payment-method" class="w-full px-3 py-2 border rounded">
                    <option value="Efectivo">Efectivo</option>
                    <option value="Tarjeta">Tarjeta</option>
                    <option value="Transferencia">Transferencia</option>
                </select>
            </div>

            <button id="process-sale" class="btn-secondary w-full py-2 rounded-md font-semibold" disabled="">
                <i class="fas fa-shopping-cart mr-2"></i> Procesar Venta
            </button>
        </div>

        <!-- Modal, registro del cliente -->
        <div id="client-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div id="modal" class="bg-black p-6 rounded-lg w-96">
                <h3 class="text-xl mb-4">Datos del Cliente</h3>
                <form id="client-form">
                    <label for="phone" class="block mb-1">Número de Teléfono</label>
                    <input type="tel" id="phone" name="phone" required class="w-full mb-4 px-3 py-2 border rounded" />

                    <div id="additional-fields" class="hidden">
                        <label for="name" class="block mb-1">Nombre Completo</label>
                        <input type="text" id="name" name="name" class="w-full mb-4 px-3 py-2 border rounded" />
                        <label for="email" class="block mb-1">Correo Electrónico</label>
                        <input type="email" id="email" name="email" class="w-full mb-4 px-3 py-2 border rounded" />
                        <label for="Dirección" class="block mb-1">Dirección</label>
                        <input type="text" id="address" name="address" class="w-full mb-4 px-3 py-2 border rounded" />
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" id="cancel-client" class="btn-secondary px-4 py-2 rounded">Cancelar</button>
                        <button type="submit" id="submitClient" class="btn-primary px-4 py-2 rounded">Confirmar</button>
                        <button type="button" id="saveClientBtn" class="btn-primary px-4 py-2 rounded hidden">Confirmar</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Buscar Producto
            const searchInput = document.getElementById('search-product');
            const tableBody = document.querySelector('.custom-table tbody');

            searchInput.addEventListener('input', function () {
                const filter = this.value.toLowerCase();

                // Recorre todas las filas del tbody
                Array.from(tableBody.rows).forEach(row => {
                    const nombre = row.cells[1].textContent.toLowerCase();
                    const descripcion = row.cells[2].textContent.toLowerCase();

                    if (nombre.includes(filter) || descripcion.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            // Crear venta
            const cartItemsContainer = document.getElementById('cart-items');
            const emptyCartMsg = document.getElementById('empty-cart-message');
            const subtotalEl = document.getElementById('subtotal');
            const taxEl = document.getElementById('tax');
            const totalEl = document.getElementById('total');
            const processBtn = document.getElementById('process-sale');

            let cart = {};

            function formatCurrency(amount) {
                return '$' + amount.toFixed(2);
            }

            function updateCartDisplay() {
                // Vaciar el contenedor
                cartItemsContainer.innerHTML = '';

                let subtotal = 0;
                const keys = Object.keys(cart);
                if (keys.length === 0) {
                    emptyCartMsg.style.display = 'block';
                    processBtn.disabled = true;
                    subtotalEl.textContent = '$0.00';
                    taxEl.textContent = '$0.00';
                    totalEl.textContent = '$0.00';
                    return;
                } else {
                    emptyCartMsg.style.display = 'none';
                    processBtn.disabled = false;
                }

                // Crear filas para cada producto en el carrito
                keys.forEach(id => {
                    const item = cart[id];
                    const itemTotal = item.price * item.quantity;
                    subtotal += itemTotal;

                    const itemDiv = document.createElement('div');
                    itemDiv.className = 'flex justify-between items-center mb-2';

                    itemDiv.innerHTML = `
                <div>
                    <strong>${item.name}</strong> x ${item.quantity}
                </div>
                <div>
                    ${formatCurrency(itemTotal)}
                    <button class="remove-item text-red-500 ml-2" data-id="${id}" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;

                    cartItemsContainer.appendChild(itemDiv);
                });

                subtotalEl.textContent = formatCurrency(subtotal / 1.16);
                const tax = subtotal - (subtotal / 1.16);
                taxEl.textContent = formatCurrency(tax);
                totalEl.textContent = formatCurrency(subtotal);
            }

            // Añadir producto al carrito
            function addToCart(id, name, price) {
                if (cart[id]) {
                    cart[id].quantity++;
                } else {
                    cart[id] = { name, price, quantity: 1 };
                }
                updateCartDisplay();
            }

            // Eliminar producto del carrito
            cartItemsContainer.addEventListener('click', function(e) {
                if (e.target.closest('.remove-item')) {
                    const id = e.target.closest('.remove-item').dataset.id;
                    delete cart[id];
                    updateCartDisplay();
                }
            });

            // Botones "Agregar"
            document.querySelectorAll('.add-to-cart').forEach(button => {
                button.addEventListener('click', () => {
                    const id = button.dataset.id;
                    const name = button.dataset.name;
                    const price = parseFloat(button.dataset.price);
                    addToCart(id, name, price);
                });
            });

            // Inicializar carrito vacío
            updateCartDisplay();

            // Modal registro cliente
            const clientModal = document.getElementById('client-modal');
            const clientForm = document.getElementById('client-form');
            const additionalFields = document.getElementById('additional-fields');
            const cancelBtn = document.getElementById('cancel-client');
            const phoneInput = document.getElementById('phone');
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const addressInput = document.getElementById('address');
            const submitClientBtn = document.getElementById('submitClient');
            const saveClientBtn = document.getElementById('saveClientBtn');

            // Al hacer click en Procesar venta, abrir modal y resetear
            processBtn.addEventListener('click', () => {
                clientModal.classList.remove('hidden');
                clientForm.reset();
                additionalFields.classList.add('hidden');
                saveClientBtn.classList.add('hidden');
                submitClientBtn.classList.remove('hidden');
            });

            cancelBtn.addEventListener('click', () => {
                clientModal.classList.add('hidden');
            });

            // Submit para verificar cliente existente
            clientForm.addEventListener('submit', e => {
                e.preventDefault();

                const phone = phoneInput.value.trim();
                if (!phone) {
                    alert('Ingresa un número de teléfono válido');
                    return;
                }

                fetch('utils/check_client.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ phone })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.exists) {
                            clientModal.classList.add('hidden');
                            procesarVenta(data.client);
                        } else {
                            additionalFields.classList.remove('hidden');
                            saveClientBtn.classList.remove('hidden');
                            submitClientBtn.classList.add('hidden');
                        }
                    })
                    .catch(err => {
                        alert('Error al consultar cliente: ' + err.message);
                    });
            });

            // Click para registrar cliente nuevo
            saveClientBtn.addEventListener('click', () => {
                const name = nameInput.value.trim();
                const email = emailInput.value.trim();
                const address = addressInput.value.trim();
                const phone = phoneInput.value.trim();

                if (!name || !email || !address || !phone) {
                    alert('Por favor completa todos los campos');
                    return;
                }

                fetch('utils/register_client.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ name, email, address, phone })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert('Cliente registrado correctamente.');
                            additionalFields.classList.add('hidden');
                            saveClientBtn.classList.add('hidden');
                            submitClientBtn.classList.remove('hidden');
                            clientModal.classList.add('hidden');
                            procesarVenta(data.client);
                        } else {
                            alert('Error: ' + (data.message || 'Error desconocido'));
                        }
                    })
                    .catch(err => alert('Error al registrar cliente: ' + err.message));
            });

            function procesarVenta(client) {
                const metodoPago = document.getElementById('payment-method').value;
                const cartItems = Object.entries(cart).map(([id, item]) => ({
                    id,
                    quantity: item.quantity,
                    price: item.price
                }));

                const total = parseFloat(totalEl.textContent.replace('$', '').replace(',', ''));

                fetch('utils/process_sale.php', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        client_id: client.id,
                        metodo_pago: metodoPago,
                        cart: cartItems,
                        total: total
                    })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert('Venta completada con éxito');
                            clientModal.classList.add('hidden');
                            cart = {};
                            updateCartDisplay();
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(err => alert('Error al procesar venta: ' + err.message));
            }


        });
    </script>

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
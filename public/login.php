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
    $errorMessage = "";

    if (isset($_POST['submit'])){
        $username = $_POST['username'];
        $password = $_POST['password'];

        if (!preg_match('/^[a-zA-Z0-9@_\-.#$%&!*]+$/', $password)) {
            $errorMessage = "La contraseña contiene caracteres inválidos.";
        } else {
            $stmt = $connection->prepare("SELECT * FROM usuarios WHERE LOWER(username) LIKE LOWER(:username)");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            if ($stmt->rowCount() === 1) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if (password_verify($password, $result['password'])) {
                    session_regenerate_id(true);

                    $session = [
                        'id' => $result['id'],
                        'nombre_usuario' => $result['username'],
                        'nombre' => $result['first_name'],
                        'apellido_p' => $result['last_name'],
                        'apellido_m' => $result['second_last_name'],
                        'rol' => $result['role']
                    ];

                    $_SESSION['datos'] = $session;

                    header("Location: dashboard.php");
                    exit();
                } else {
                    $errorMessage = "Usuario o contraseña incorrectos.";
                }
            } else {
                $errorMessage = "Usuario o contraseña incorrectos.";
            }
        }
    }

    $connection = null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="css/login.css" />
    <link rel="stylesheet" href="css/palette.css">
    <link rel="icon" href="img/sortbox_onlyLogo.svg" type="image/svg">
</head>
<body>
<div class="blured-background"></div>
<div class="overlay-background"></div>
<div class="container">

    <header class="header">
        <nav class="links">
            <a href="/">Página principal</a>
            <a href="/">Sobre nosotros</a>
        </nav>
    </header>

    <main class="form-container">
        <form id="loginForm" class="form" method="POST" action="" novalidate>

            <img src="img/sortbox.png" alt="SortBox" class="logo" />

            <strong style="font-size: 2rem">Iniciar sesión</strong>
            <input type="text" placeholder="Usuario" name="username" required />
            <div class="password-container">
                <input type="password" name="password" id="password" placeholder="Contraseña" required />
                <svg id="togglePassword" class="toggle-password" xmlns="http://www.w3.org/2000/svg"
                     width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </div>

            <div id="inputErrorContainer"></div>

            <div class="form-buttons">
                <button onclick="location.href='singin.php'" type="button" class="secondaryB">Registrarse</button>
                <button type="submit" name="submit">Ingresar</button>
            </div>
        </form>
    </main>

    <footer class="footer">
        © 2025 SortBox. Todos los derechos reservados.
    </footer>

</div>

<script>
    const form = document.getElementById("loginForm");
    const usuario = form.elements["username"];
    const password = form.elements["password"];
    const errorContainer = document.getElementById("inputErrorContainer");

    form.addEventListener("submit", function (e) {
        let isValid = true;
        usuario.classList.remove("invalid");
        password.classList.remove("invalid");
        errorContainer.innerHTML = "";

        if (!usuario.value.trim()) {
            usuario.classList.add("invalid");
            isValid = false;
        }

        if (!password.value.trim()) {
            password.classList.add("invalid");
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            mostrarError("Llena todos los campos.");
        }
    });

    function mostrarError(mensaje) {
        errorContainer.innerHTML = "";
        const span = document.createElement("span");
        span.className = "error";
        span.textContent = mensaje;
        errorContainer.appendChild(span);
    }

    window.addEventListener("pageshow", function (event) {
        if (event.persisted) {
            form.reset();
        }
    });

    const togglePassword = document.getElementById("togglePassword");
    const passwordInput = document.getElementById("password");

    let passwordVisible = false;

    const eyeSVG = `
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        `;

    const eyeOffSVG = `
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.96 9.96 0 012.293-3.95M6.305 6.305A9.954 9.954 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.963 9.963 0 01-4.243 5.144M3 3l18 18" />
        `;

    togglePassword.addEventListener("click", () => {
        passwordVisible = !passwordVisible;
        passwordInput.type = passwordVisible ? "text" : "password";

        togglePassword.innerHTML = passwordVisible ? eyeOffSVG : eyeSVG;
    });
    
    <?php if (!empty($errorMessage)): ?>
    mostrarError("<?php echo $errorMessage; ?>");
    <?php endif; ?>
</script>
</body>
</html>

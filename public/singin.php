<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registro de Usuario</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/singin.css" />
    <link rel="stylesheet" href="css/palette.css">
</head>
<body class="min-h-screen flex items-center justify-center p-4">
<div class="form-container w-full max-w-5xl">
    <img src="img/sortbox.png" alt="Sortbox" class="w-full max-w-5xl object-contain mb-6" style="height: 80px;">

    <form id="registrationForm" class="p-6" method="post" action="register_employee.php">
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Columna izquierda - Formulario -->
            <div class="w-full md:w-1/2 space-y-5">
                <div>
                    <label for="username" class="block mb-2 font-medium">Nombre de usuario*</label>
                    <input type="text" id="username" name="username" class="input-field" required />
                    <div id="username-error" class="error-message">El nombre de usuario es obligatorio</div>
                </div>

                <div>
                    <label for="firstName" class="block mb-2 font-medium">Nombre*</label>
                    <input type="text" id="firstName" name="firstName" class="input-field" required />
                    <div id="firstName-error" class="error-message">El nombre es obligatorio</div>
                </div>

                <div>
                    <label for="lastName" class="block mb-2 font-medium">Apellido paterno*</label>
                    <input type="text" id="lastName" name="lastName" class="input-field" required />
                    <div id="lastName-error" class="error-message">El apellido paterno es obligatorio</div>
                </div>

                <div>
                    <label for="secondLastName" class="block mb-2 font-medium">Apellido materno*</label>
                    <input type="text" id="secondLastName" name="secondLastName" class="input-field" required />
                    <div id="secondLastName-error" class="error-message">El apellido materno es obligatorio</div>
                </div>

                <div>
                    <label for="password" class="block mb-2 font-medium">Contraseña*</label>
                    <input type="password" id="password" name="password" class="input-field" required />
                    <div id="password-error" class="error-message">La contraseña debe tener al menos 8 caracteres</div>
                    <div id="password-strength" class="success-message mt-2"><strong>Seguridad: <span id="strength-text">Débil</span></strong></div>
                </div>

                <div>
                    <label for="confirmPassword" class="block mb-2 font-medium">Confirmar contraseña*</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" class="input-field" required />
                    <div id="confirmPassword-error" class="error-message">Las contraseñas no coinciden</div>
                </div>
            </div>

            <!-- Columna derecha - Roles -->
            <div class="w-full md:w-1/2 space-y-5">
                <div>
                    <label class="block mb-4 font-medium text-center">Seleccione su rol en el sistema*</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="role-option p-4 text-center cursor-pointer" data-role="admin">
                            <!-- SVG icono Admin -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <h3 class="font-medium">Administrador</h3>
                            <p class="text-xs opacity-80 mt-1">Control total del sistema</p>
                        </div>

                        <div class="role-option p-4 text-center cursor-pointer" data-role="supervisor">
                            <!-- SVG icono Supervisor -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                            <h3 class="font-medium">Supervisor</h3>
                            <p class="text-xs opacity-80 mt-1">Gestión y supervisión</p>
                        </div>

                        <div class="role-option p-4 text-center cursor-pointer" data-role="operator">
                            <!-- SVG icono Operador -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <h3 class="font-medium">Operador</h3>
                            <p class="text-xs opacity-80 mt-1">Manejo de inventario</p>
                        </div>

                        <div class="role-option p-4 text-center cursor-pointer" data-role="viewer">
                            <!-- SVG icono Visualizador -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <h3 class="font-medium">Visualizador</h3>
                            <p class="text-xs opacity-80 mt-1">Solo consulta</p>
                        </div>
                    </div>
                    <div id="role-error" class="error-message text-center mt-3">Debe seleccionar un rol</div>
                    <input type="hidden" id="selectedRole" name="selectedRole" value="" />
                </div>

                <div class="bg-black bg-opacity-20 p-4 rounded-lg mt-6">
                    <h3 class="font-medium mb-3">Información del rol seleccionado:</h3>
                    <div id="role-info" class="text-sm opacity-80">
                        <p>Seleccione un rol para ver su descripción y permisos.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-6 flex flex-col sm:flex-row justify-center gap-4 mt-4">
            <button type="button" id="registerBtn" class="submit-btn">Registrar Usuario</button>
            <button type="reset" class="reset-btn">Limpiar Formulario</button>
        </div>
    </form>
</div>

<!-- Modal de autorización -->
<div id="authorizationModal" class="fixed inset-0 modal-overlay flex items-center justify-center z-50 hidden">
    <div class="bg-[#1A1A1A] border-2 border-[#C51C43] rounded-lg w-full max-w-md p-6">
        <h2 class="text-xl font-bold mb-4 text-center">Autorización Requerida</h2>
        <p class="text-center mb-6">Para completar el registro, se requiere autorización de un administrador.</p>

        <div class="space-y-4">
            <div>
                <label for="authUsername" class="block mb-2 font-medium">Usuario administrador</label>
                <input type="text" id="authUsername" name="authUsername" class="input-field" required />
            </div>

            <div>
                <label for="authPassword" class="block mb-2 font-medium">Contraseña</label>
                <input type="password" id="authPassword" name="authPassword" class="input-field" required />
            </div>

            <div id="auth-error" class="error-message text-center">Credenciales inválidas</div>
        </div>

        <div class="flex justify-between mt-6">
            <button id="cancelAuth" class="reset-btn">Cancelar</button>
            <button id="confirmAuth" class="submit-btn" type="button">Autorizar</button>
        </div>
    </div>
</div>

<!-- Modal de confirmación -->
<div id="confirmationModal" class="fixed inset-0 modal-overlay flex items-center justify-center z-50 hidden">
    <div class="bg-[#1A1A1A] border-2 border-[#C51C43] rounded-lg w-full max-w-md p-6">
        <h2 class="text-xl font-bold mb-4 text-center">Registro Exitoso</h2>
        <p class="text-center mb-6">El usuario ha sido registrado correctamente en el sistema.</p>
        <div class="flex justify-center">
            <button id="closeModal" class="submit-btn">Aceptar</button>
        </div>
    </div>
</div>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registrationForm');
            const roleOptions = document.querySelectorAll('.role-option');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('confirmPassword');
            const passwordStrength = document.getElementById('password-strength');
            const strengthText = document.getElementById('strength-text');
            const confirmationModal = document.getElementById('confirmationModal');
            const closeModalBtn = document.getElementById('closeModal');
            const authorizationModal = document.getElementById('authorizationModal');
            const cancelAuthBtn = document.getElementById('cancelAuth');
            const confirmAuthBtn = document.getElementById('confirmAuth');
            const roleInfo = document.getElementById('role-info');
            const registerBtn = document.getElementById('registerBtn');
            const selectedRoleInput = document.getElementById('selectedRole');

            let selectedRole = null;

            const roleDescriptions = {
                'admin': {
                    title: 'Administrador',
                    description: 'Control total del sistema con acceso a todas las funciones.',
                    permissions: ['Gestión de usuarios', 'Configuración del sistema', 'Reportes avanzados']
                },
                'supervisor': {
                    title: 'Supervisor',
                    description: 'Supervisión de operaciones y gestión de inventario.',
                    permissions: ['Aprobación de transacciones', 'Gestión de inventario', 'Reportes operativos']
                },
                'operator': {
                    title: 'Operador',
                    description: 'Manejo diario del inventario, registro de entradas y salidas.',
                    permissions: ['Registro de movimientos', 'Consulta de inventario', 'Reportes básicos']
                },
                'viewer': {
                    title: 'Visualizador',
                    description: 'Acceso de solo lectura para consultar información.',
                    permissions: ['Consulta de inventario', 'Visualización de reportes']
                }
            };

            roleOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const role = this.getAttribute('data-role');
                    roleOptions.forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                    selectedRole = role;
                    selectedRoleInput.value = role;
                    document.getElementById('role-error').style.display = 'none';

                    const info = roleDescriptions[role];
                    let html = `
                <h4 class="font-medium text-[#F7A41D] mb-2">${info.title}</h4>
                <p class="mb-2">${info.description}</p>
                <p class="font-medium mt-3 mb-1">Permisos:</p>
                <ul class="list-disc pl-5">
            `;

                    info.permissions.forEach(permission => {
                        html += `<li>${permission}</li>`;
                    });

                    html += '</ul>';
                    roleInfo.innerHTML = html;
                });
            });

            registerBtn.addEventListener('click', function() {
                if (validateForm()) {
                    authorizationModal.classList.remove('hidden');
                    document.getElementById('auth-error').style.display = 'none';
                }
            });

            cancelAuthBtn.addEventListener('click', function() {
                document.getElementById('authUsername').value = '';
                document.getElementById('authPassword').value = '';
                authorizationModal.classList.add('hidden');
            });

            confirmAuthBtn.addEventListener('click', function () {
                console.log('Botón Autorizar presionado');
                const authUsername = document.getElementById('authUsername').value;
                const authPassword = document.getElementById('authPassword').value;

                if (!authUsername || !authPassword) {
                    document.getElementById('auth-error').style.display = 'block';
                    return;
                }

                const formData = new FormData(form);
                formData.set('authUsername', authUsername);
                formData.set('authPassword', authPassword);

                fetch('./register_employee.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        if (data.success) {
                            authorizationModal.classList.add('hidden');
                            confirmationModal.classList.remove('hidden');
                        } else if (data.errors) {
                            authorizationModal.classList.add('hidden');
                            displayErrors(data.errors);
                        }
                    })
                    .catch(error => {
                        console.error('Error en el registro:', error);
                    });
                document.getElementById('authUsername').value = '';
                document.getElementById('authPassword').value = '';
            });

            passwordInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;

                if (password.length >= 8) strength++;
                if (password.match(/[A-Z]/)) strength++;
                if (password.match(/[0-9]/)) strength++;
                if (password.match(/[^A-Za-z0-9]/)) strength++;

                passwordStrength.style.display = 'block';

                if (strength <= 1) {
                    strengthText.textContent = 'Débil';
                    strengthText.style.color = '#FF5E5E';
                } else if (strength === 2) {
                    strengthText.textContent = 'Media';
                    strengthText.style.color = '#F7A41D';
                } else {
                    strengthText.textContent = 'Fuerte';
                    strengthText.style.color = '#008000';
                }

                if (confirmPasswordInput.value) {
                    validatePasswordMatch();
                }
            });

            confirmPasswordInput.addEventListener('input', validatePasswordMatch);

            function validatePasswordMatch() {
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                const errorElement = document.getElementById('confirmPassword-error');

                if (password !== confirmPassword) {
                    errorElement.style.display = 'block';
                    return false;
                } else {
                    errorElement.style.display = 'none';
                    return true;
                }
            }

            function displayErrors(errors) {
                document.querySelectorAll('.error-message').forEach(msg => msg.style.display = 'none');

                for (let field in errors) {
                    const errorMsg = errors[field];
                    const errorElement = document.getElementById(`${field}-error`);
                    if (errorElement) {
                        errorElement.textContent = errorMsg;
                        errorElement.style.display = 'block';
                    } else {
                        alert(errorMsg);
                    }
                }
            }

            function validateForm() {
                let isValid = true;

                const requiredFields = ['username', 'firstName', 'lastName', 'secondLastName', 'password'];
                requiredFields.forEach(field => {
                    const input = document.getElementById(field);
                    const errorElement = document.getElementById(`${field}-error`);

                    if (!input.value.trim()) {
                        errorElement.style.display = 'block';
                        isValid = false;
                    } else {
                        errorElement.style.display = 'none';
                    }
                });

                if (passwordInput.value.length < 8) {
                    document.getElementById('password-error').style.display = 'block';
                    isValid = false;
                }

                if (!validatePasswordMatch()) {
                    isValid = false;
                }

                if (!selectedRole) {
                    document.getElementById('role-error').style.display = 'block';
                    isValid = false;
                }

                return isValid;
            }

            closeModalBtn.addEventListener('click', function() {
                confirmationModal.classList.add('hidden');
                form.reset();
                roleOptions.forEach(opt => opt.classList.remove('selected'));
                selectedRole = null;
                selectedRoleInput.value = '';
                passwordStrength.style.display = 'none';
                roleInfo.innerHTML = '<p>Seleccione un rol para ver su descripción y permisos.</p>';

                window.location.href = 'login.php';
            });

            form.addEventListener('reset', function() {
                document.querySelectorAll('.error-message').forEach(msg => msg.style.display = 'none');

                roleOptions.forEach(opt => opt.classList.remove('selected'));
                selectedRole = null;
                selectedRoleInput.value = '';
                passwordStrength.style.display = 'none';
                roleInfo.innerHTML = '<p>Seleccione un rol para ver su descripción y permisos.</p>';
            });
        });
    </script>

</body>
</html>
<?php
/*
FORO: Registro de usuario
Descripción:
Este archivo permite registrar nuevos usuarios en el sistema,
validando los datos ingresados y almacenando la contraseña de
forma segura utilizando password_hash.
*/

require_once 'conexion.php';
require_once 'includes/funciones.php';

iniciar_sesion();

$errores = [];
$mensaje_exito = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // FORO: Limpieza y obtención de datos del formulario
    $cedula = limpiar($_POST['cedula'] ?? '');
    $nombre = limpiar($_POST['nombre'] ?? '');
    $correo = limpiar($_POST['correo'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmar = $_POST['confirmar'] ?? '';

    // FORO: Validaciones en servidor
    if ($cedula === '') {
        $errores[] = 'La cédula es obligatoria.';
    } elseif (!preg_match('/^\d{10}$/', $cedula)) {
        $errores[] = 'La cédula debe contener exactamente 10 dígitos numéricos.';
    }

    if ($nombre === '') {
        $errores[] = 'El nombre es obligatorio.';
    } elseif (!preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/u', $nombre)) {
        $errores[] = 'El nombre solo puede contener letras y espacios.';
    }

    if ($correo === '') {
        $errores[] = 'El correo es obligatorio.';
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'El correo no tiene un formato válido.';
    }

    if ($password === '' || $confirmar === '') {
        $errores[] = 'La contraseña y su confirmación son obligatorias.';
    } elseif ($password !== $confirmar) {
        $errores[] = 'Las contraseñas no coinciden.';
    } elseif (strlen($password) < 8) {
        $errores[] = 'La contraseña debe tener al menos 8 caracteres.';
    }

    // FORO: Verificar que no exista la cédula o el correo
    if (empty($errores)) {
        $stmt = $pdo->prepare(
            'SELECT id FROM usuarios WHERE cedula = :cedula OR correo = :correo'
        );
        $stmt->execute([
            'cedula' => $cedula,
            'correo' => $correo
        ]);

        if ($stmt->fetch()) {
            $errores[] = 'La cédula o el correo ya están registrados.';
        }
    }

    // FORO: Inserción segura del usuario
    if (empty($errores)) {

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare(
            'INSERT INTO usuarios (cedula, nombre, correo, password)
             VALUES (:cedula, :nombre, :correo, :password)'
        );

        $stmt->execute([
            'cedula' => $cedula,
            'nombre' => $nombre,
            'correo' => $correo,
            'password' => $password_hash
        ]);

        $mensaje_exito = 'Registro exitoso. Ya puedes iniciar sesión.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de Usuario</title>
    <!-- Estilos aplicados con Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Estilos personalizados en CSS centralizado -->
    <link href="assets/css/estilos.css" rel="stylesheet">
    <style>
        .navbar-custom {
            background: linear-gradient(135deg, #ffffff 0%, #e3f2fd 100%);
            border-bottom: 2px solid #1976d2;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand-custom {
            font-weight: bold;
            color: #1976d2 !important;
            font-size: 1.4rem;
            text-shadow: none;
            transition: transform 0.3s ease;
        }

        .navbar-brand-custom:hover {
            transform: scale(1.05);
            color: #0d47a1 !important;
        }

        .navbar-nav .nav-link {
            color: #1976d2 !important;
            font-weight: 500;
            margin: 0 10px;
            transition: all 0.3s ease;
            position: relative;
        }

        .navbar-nav .nav-link:hover {
            color: #0d47a1 !important;
            transform: translateY(-2px);
        }

        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 50%;
            background-color: #1976d2;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover::after {
            width: 100%;
            left: 0;
        }

        .navbar-toggler {
            border: 1px solid #1976d2;
            background: rgba(25,118,210,0.1);
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%231976d2' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='m4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        .auth-container {
            max-width: 520px;
            margin: 50px auto;
            padding: 36px 32px;
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
        }

        .auth-title {
            font-size: 1.9rem;
            font-weight: 700;
            color: #212529;
            margin-bottom: 6px;
            text-align: center;
        }

        .auth-subtitle {
            text-align: center;
            color: #6c757d;
            margin-bottom: 24px;
            font-size: 0.96rem;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            font-weight: 600;
            color: #212529;
            margin-bottom: 8px;
        }

        .btn-register {
            width: 100%;
            padding: 12px;
            font-weight: 600;
            background-color: #0d6efd;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 1rem;
        }

        .btn-register:hover {
            background-color: #0b5ed7;
        }

        .auth-links {
            text-align: center;
            margin-top: 22px;
            padding-top: 18px;
            border-top: 1px solid #dee2e6;
        }

        .auth-links a {
            color: #0d6efd;
            text-decoration: none;
            font-weight: 500;
        }

        .auth-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand navbar-brand-custom" href="index.php">🏥 MediCitas</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Iniciar sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="auth-container">
        <h1 class="auth-title">📝 Registro de usuario</h1>
        <p class="auth-subtitle">Crea tu cuenta para solicitar citas médicas en el prototipo.</p>

        <?php
        // FORO: Mostrar mensajes de error
        if (!empty($errores)) {
            echo '<div class="alert alert-danger" role="alert">';
            echo '<strong>❌ Error:</strong>';
            echo '<ul class="mb-0" style="margin-top: 10px;">';
            foreach ($errores as $error) {
                echo "<li>$error</li>";
            }
            echo '</ul>';
            echo '</div>';
        }

        // FORO: Mensaje de éxito
        if ($mensaje_exito !== '') {
            echo "<div class='alert alert-success' role='alert'>$mensaje_exito</div>";
        }
        ?>

        <form method="post" action="registro.php">
            <div class="form-group">
                <label for="cedula">🆔 Cédula</label>
                <input type="text" name="cedula" id="cedula" class="form-control" pattern="\d{10}" minlength="10" maxlength="10" inputmode="numeric" title="10 dígitos numéricos" placeholder="0123456789" required>
            </div>

            <div class="form-group">
                <label for="nombre">👤 Nombre completo</label>
                <input type="text" name="nombre" id="nombre" class="form-control" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="Solo letras y espacios" placeholder="Juan Pérez" required>
            </div>

            <div class="form-group">
                <label for="correo">📧 Correo</label>
                <input type="email" name="correo" id="correo" class="form-control" placeholder="correo@ejemplo.com" required>
            </div>

            <div class="form-group">
                <label for="password">🔒 Contraseña</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Mínimo 8 caracteres" required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword" title="Mostrar/Ocultar contraseña">
                        👁️
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label for="confirmar">🔒 Confirmar contraseña</label>
                <div class="input-group">
                    <input type="password" name="confirmar" id="confirmar" class="form-control" placeholder="Repite tu contraseña" required>
                    <button class="btn btn-outline-secondary" type="button" id="toggleConfirmar" title="Mostrar/Ocultar contraseña">
                        👁️
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-register">Registrarse</button>
        </form>

        <div class="auth-links">
            <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
        </div>
    </div>

    <!-- Estilos aplicados con Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle para contraseña
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁️' : '🙈';
        });

        // Toggle para confirmar contraseña
        document.getElementById('toggleConfirmar').addEventListener('click', function() {
            const confirmarField = document.getElementById('confirmar');
            const type = confirmarField.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmarField.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁️' : '🙈';
        });
    </script>
</body>
</html>
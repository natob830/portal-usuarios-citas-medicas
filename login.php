<?php
/*
FORO: Inicio de sesión de usuario
Descripción:
Este archivo permite autenticar al usuario mediante correo y contraseña,
verificando los datos con la base de datos y creando una sesión segura.
*/

require_once 'conexion.php';
require_once 'includes/funciones.php';

iniciar_sesion();

$errores = [];
$mensaje_exito = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // FORO: Obtener y limpiar datos del formulario
    $correo = limpiar($_POST['correo'] ?? '');
    $password = $_POST['password'] ?? '';

    // FORO: Validaciones básicas
    if ($correo === '') {
        $errores[] = 'El correo es obligatorio.';
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'El formato del correo no es válido.';
    }

    if ($password === '') {
        $errores[] = 'La contraseña es obligatoria.';
    }

    // FORO: Verificar credenciales
    if (empty($errores)) {

        $stmt = $pdo->prepare(
            'SELECT id, cedula, nombre, correo, password 
             FROM usuarios 
             WHERE correo = :correo'
        );

        $stmt->execute(['correo' => $correo]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // FORO: Comparación segura de contraseñas
        if ($usuario && password_verify($password, $usuario['password'])) {

            // FORO: Creación de la sesión
            $_SESSION['usuario'] = [
                'id' => $usuario['id'],
                'cedula' => $usuario['cedula'],
                'nombre' => $usuario['nombre'],
                'correo' => $usuario['correo']
            ];

            header('Location: perfil.php');
            exit;

        } else {
            $errores[] = 'Correo o contraseña incorrectos.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión</title>
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
            max-width: 500px;
            margin: 50px auto;
            padding: 40px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }

        .auth-title {
            font-size: 1.8rem;
            font-weight: bold;
            color: #212529;
            margin-bottom: 10px;
            text-align: center;
        }

        .auth-subtitle {
            text-align: center;
            color: #6c757d;
            margin-bottom: 30px;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: 600;
            color: #212529;
            margin-bottom: 8px;
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            font-weight: 600;
            background-color: #0d6efd;
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 1rem;
        }

        .btn-submit:hover {
            background-color: #0d47a1;
        }

        .auth-links {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
        }

        .auth-links a {
            color: #0d6efd;
            text-decoration: none;
            font-weight: 500;
            display: block;
            margin: 10px 0;
        }

        .auth-links a:hover {
            text-decoration: underline;
        }

        .back-to-home {
            text-align: center;
            margin-top: 30px;
        }

        .back-to-home a {
            color: #6c757d;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
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
                        <a class="nav-link" href="registro.php">Registro</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenedor de Autenticación -->
    <div class="auth-container">
        <h1 class="auth-title">🔐 Iniciar sesión</h1>
        <p class="auth-subtitle">Ingresa tus datos para acceder al sistema</p>

        <?php
        // FORO: Mostrar errores
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
        ?>

        <form method="post" action="login.php">
            <div class="form-group">
                <label for="correo">📧 Correo electrónico</label>
                <input type="email" name="correo" id="correo" class="form-control" placeholder="correo@ejemplo.com" required>
            </div>

            <div class="form-group">
                <label for="password">🔒 Contraseña</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Ingresa tu contraseña" required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword" title="Mostrar/Ocultar contraseña">
                        👁️
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-submit">Ingresar al Sistema</button>
        </form>

        <div class="auth-links">
            <span style="color: #6c757d;">¿No tienes cuenta?</span>
            <a href="registro.php">👤 Crear cuenta nueva</a>
        </div>

        <div class="back-to-home">
            <a href="index.php">← Volver a la página principal</a>
        </div>
    </div>

    <!-- Estilos aplicados con Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁️' : '🙈';
        });
    </script>
</body>
</html>

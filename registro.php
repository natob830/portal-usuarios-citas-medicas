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
    }

    if ($nombre === '') {
        $errores[] = 'El nombre es obligatorio.';
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
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2>Registro de Usuario</h2>

                <?php
                // FORO: Mostrar mensajes de error
                if (!empty($errores)) {
                    echo '<div class="alert alert-danger">';
                    echo '<ul class="mb-0">';
                    foreach ($errores as $error) {
                        echo "<li>$error</li>";
                    }
                    echo '</ul>';
                    echo '</div>';
                }

                // FORO: Mensaje de éxito
                if ($mensaje_exito !== '') {
                    echo "<div class='alert alert-success'>$mensaje_exito</div>";
                }
                ?>

                <form method="post" action="registro.php">
                    <div class="mb-3">
                        <label for="cedula" class="form-label">Cédula:</label>
                        <input type="text" name="cedula" id="cedula" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo:</label>
                        <input type="email" name="correo" id="correo" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña:</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="confirmar" class="form-label">Confirmar contraseña:</label>
                        <input type="password" name="confirmar" id="confirmar" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Registrarse</button>
                </form>

                <div class="text-center mt-3">
                    <a href="login.php" class="btn btn-link">¿Ya tienes cuenta? Inicia sesión</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Estilos aplicados con Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
/*
FORO: Zona privada de perfil de usuario
Descripción:
Este archivo representa el área privada del usuario. Solo es accesible
si existe una sesión activa. Permite visualizar y actualizar los datos
básicos del perfil (nombre y correo).
*/

require_once 'conexion.php';
require_once 'includes/funciones.php';

// FORO: Protección de la página
require_login();

$usuario = $_SESSION['usuario'];
$errores = [];
$mensaje_exito = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // FORO: Limpieza de datos
    $nombre = limpiar($_POST['nombre'] ?? '');
    $correo = limpiar($_POST['correo'] ?? '');

    // FORO: Validaciones en servidor
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

    // FORO: Verificar que el correo no esté en uso por otro usuario
    if (empty($errores)) {
        $stmt = $pdo->prepare(
            'SELECT id FROM usuarios WHERE correo = :correo AND id != :id'
        );
        $stmt->execute([
            'correo' => $correo,
            'id' => $usuario['id']
        ]);

        if ($stmt->fetch()) {
            $errores[] = 'El correo ya está en uso por otro usuario.';
        }
    }

    // FORO: Actualización del perfil
    if (empty($errores)) {
        $stmt = $pdo->prepare(
            'UPDATE usuarios SET nombre = :nombre, correo = :correo WHERE id = :id'
        );
        $stmt->execute([
            'nombre' => $nombre,
            'correo' => $correo,
            'id' => $usuario['id']
        ]);

        // FORO: Actualizar datos de la sesión
        $_SESSION['usuario']['nombre'] = $nombre;
        $_SESSION['usuario']['correo'] = $correo;

        $mensaje_exito = 'Perfil actualizado correctamente.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perfil de Usuario</title>
    <!-- Estilos aplicados con Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Estilos personalizados en CSS centralizado -->
    <link href="assets/css/estilos.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2>Perfil de Usuario</h2>

                <p class="text-center"><strong>Bienvenido:</strong> <?= htmlspecialchars($usuario['nombre']) ?></p>

                <?php
                // FORO: Mostrar errores
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

                <form method="post">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" value="<?= htmlspecialchars($usuario['nombre']) ?>" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="Solo letras y espacios" required>
                    </div>

                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo:</label>
                        <input type="email" name="correo" id="correo" class="form-control" value="<?= htmlspecialchars($usuario['correo']) ?>" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Actualizar datos</button>
                </form>

                <hr>

                <div class="nav-links">
                    <a href="cambiar_password.php">Cambiar contraseña</a> |
                    <a href="logout.php">Cerrar sesión</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Estilos aplicados con Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
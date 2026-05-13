<?php
/*
FORO: Cambio seguro de contraseña con auditoría
Descripción:
Este archivo permite al usuario cambiar su contraseña de forma segura,
verificando la contraseña actual, generando un nuevo hash y registrando
el cambio en una tabla de auditoría.
*/

require_once 'conexion.php';
require_once 'includes/funciones.php';

// FORO: Proteger la página (solo usuarios autenticados)
require_login();

$usuario = $_SESSION['usuario'];
$errores = [];
$mensaje_exito = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // FORO: Obtener datos del formulario
    $password_actual = $_POST['password_actual'] ?? '';
    $password_nuevo = $_POST['password_nuevo'] ?? '';
    $password_confirmar = $_POST['password_confirmar'] ?? '';

    // FORO: Validaciones básicas
    if ($password_actual === '') {
        $errores[] = 'La contraseña actual es obligatoria.';
    }

    if ($password_nuevo === '') {
        $errores[] = 'La nueva contraseña es obligatoria.';
    } elseif (strlen($password_nuevo) < 8) {
        $errores[] = 'La nueva contraseña debe tener al menos 8 caracteres.';
    }

    if ($password_confirmar === '') {
        $errores[] = 'Debe confirmar la nueva contraseña.';
    } elseif ($password_nuevo !== $password_confirmar) {
        $errores[] = 'La nueva contraseña y la confirmación no coinciden.';
    }

    // FORO: Verificar contraseña actual
    if (empty($errores)) {
        $stmt = $pdo->prepare(
            'SELECT password FROM usuarios WHERE id = :id'
        );
        $stmt->execute(['id' => $usuario['id']]);
        $datos_usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$datos_usuario || !password_verify($password_actual, $datos_usuario['password'])) {
            $errores[] = 'La contraseña actual es incorrecta.';
        }
    }

    // FORO: Actualizar contraseña y registrar auditoría
    if (empty($errores)) {

        $hash_anterior = $datos_usuario['password'];
        $hash_nuevo = password_hash($password_nuevo, PASSWORD_DEFAULT);

        // Actualizar contraseña en la tabla usuarios
        $stmt = $pdo->prepare(
            'UPDATE usuarios SET password = :password WHERE id = :id'
        );
        $stmt->execute([
            'password' => $hash_nuevo,
            'id' => $usuario['id']
        ]);

        // Registrar auditoría del cambio de contraseña
        $stmt = $pdo->prepare(
            'INSERT INTO auditoria_cambios_password 
            (usuario_id, password_anterior_hash, password_nuevo_hash)
            VALUES (:usuario_id, :hash_anterior, :hash_nuevo)'
        );
        $stmt->execute([
            'usuario_id' => $usuario['id'],
            'hash_anterior' => $hash_anterior,
            'hash_nuevo' => $hash_nuevo
        ]);

        $mensaje_exito = 'La contraseña se cambió correctamente.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cambiar contraseña</title>
    <!-- Estilos aplicados con Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Estilos personalizados en CSS centralizado -->
    <link href="assets/css/estilos.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2>Cambiar contraseña</h2>

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
                        <label for="password_actual" class="form-label">Contraseña actual:</label>
                        <input type="password" name="password_actual" id="password_actual" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="password_nuevo" class="form-label">Nueva contraseña:</label>
                        <input type="password" name="password_nuevo" id="password_nuevo" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmar" class="form-label">Confirmar nueva contraseña:</label>
                        <input type="password" name="password_confirmar" id="password_confirmar" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Cambiar contraseña</button>
                </form>

                <div class="text-center mt-3">
                    <a href="perfil.php" class="btn btn-link">Volver al perfil</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Estilos aplicados con Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 
<?php
/*
PROTOTIPO DE CITAS MÉDICAS - Panel del Paciente
Descripción: Panel privado del paciente autenticado.
Requiere login obligatorio. Muestra opciones de citas.
*/

require_once 'conexion.php';
require_once 'includes/funciones.php';

// FORO: Protección - Solo acceso si está autenticado
require_login();

$usuario = $_SESSION['usuario'];

// FORO: Datos simulados de citas para el prototipo
// Estas no se guardan en BD, solo para demostración
$citas_disponibles = [
    ['id' => 1, 'especialidad' => 'Medicina General', 'duracion' => '30 min', 'descripcion' => 'Consulta general y diagnostico'],
    ['id' => 2, 'especialidad' => 'Cardiología', 'duracion' => '45 min', 'descripcion' => 'Especialista en corazón'],
    ['id' => 3, 'especialidad' => 'Pediatría', 'duracion' => '30 min', 'descripcion' => 'Para niños y adolescentes'],
    ['id' => 4, 'especialidad' => 'Dermatología', 'duracion' => '40 min', 'descripcion' => 'Problemas de piel'],
    ['id' => 5, 'especialidad' => 'Oftalmología', 'duracion' => '35 min', 'descripcion' => 'Salud visual'],
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel de Citas - MediCitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/estilos.css" rel="stylesheet">
    <style>
        .navbar-custom {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }

        .navbar-brand-custom {
            font-weight: bold;
            color: #0d6efd !important;
            font-size: 1.3rem;
        }

        .header-welcome {
            background: linear-gradient(135deg, #0d6efd 0%, #0d47a1 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 40px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-left: 5px solid #0d6efd;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            color: inherit;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #0d6efd;
            margin-bottom: 10px;
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.95rem;
        }

        .especialidades-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .especialidad-card {
            background: white;
            border: 2px solid #dee2e6;
            border-radius: 10px;
            padding: 25px;
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
        }

        .especialidad-card:hover {
            border-color: #0d6efd;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
        }

        .especialidad-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .especialidad-card h4 {
            font-weight: bold;
            color: #212529;
            margin-bottom: 10px;
        }

        .especialidad-card p {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .btn-solicitar {
            background-color: #0d6efd;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s;
        }

        .btn-solicitar:hover {
            background-color: #0d47a1;
            color: white;
            text-decoration: none;
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
                        <span class="nav-link">Hola, <strong><?php echo htmlspecialchars($usuario['nombre']); ?></strong></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="mis_citas.php">Mis Citas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="perfil.php">Perfil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Cerrar sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <div class="container" style="margin-top: 30px; margin-bottom: 50px;">
        
        <!-- Bienvenida -->
        <div class="header-welcome">
            <h1 style="margin-bottom: 10px;">Bienvenido, <?php echo htmlspecialchars($usuario['nombre']); ?> 👋</h1>
            <p style="margin-bottom: 0; opacity: 0.95;">Estamos aquí para cuidar tu salud. Solicita tu cita médica en pocos pasos.</p>
        </div>

        <!-- Estadísticas Rápidas -->
        <h3 style="font-weight: bold; margin-bottom: 25px;">Tu estado actual</h3>
        <div class="stats-grid">
            <a href="mis_citas.php" class="stat-card" style="text-decoration: none; color: inherit;">
                <div class="stat-number">0</div>
                <div class="stat-label">Citas próximas</div>
            </a>
            <a href="solicitar_cita.php" class="stat-card" style="text-decoration: none; color: inherit;">
                <div class="stat-number">+</div>
                <div class="stat-label">Solicitar nueva cita</div>
            </a>
            <div class="stat-card">
                <div class="stat-number">5</div>
                <div class="stat-label">Especialidades disponibles</div>
            </div>
        </div>

        <!-- Nuestras Especialidades -->
        <h3 style="font-weight: bold; margin-bottom: 25px;">Elige una especialidad</h3>
        <div class="especialidades-grid">
            <?php foreach ($citas_disponibles as $especialidad): ?>
                <div class="especialidad-card">
                    <div class="especialidad-icon">
                        <?php
                        $iconos = [
                            'Medicina General' => '👨‍⚕️',
                            'Cardiología' => '❤️',
                            'Pediatría' => '👶',
                            'Dermatología' => '🩺',
                            'Oftalmología' => '👁️'
                        ];
                        echo $iconos[$especialidad['especialidad']] ?? '🏥';
                        ?>
                    </div>
                    <h4><?php echo htmlspecialchars($especialidad['especialidad']); ?></h4>
                    <p><?php echo htmlspecialchars($especialidad['descripcion']); ?></p>
                    <small style="color: #999;">⏱️ Duración: <?php echo $especialidad['duracion']; ?></small>
                    <div style="margin-top: 15px;">
                        <a href="solicitar_cita.php?especialidad=<?php echo $especialidad['id']; ?>" class="btn-solicitar">
                            Solicitar cita →
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Información -->
        <div class="alert alert-info" role="alert">
            <h5>💡 Información útil</h5>
            <ul class="mb-0">
                <li>Las citas se solicitan de lunes a viernes.</li>
                <li>Horario de atención: 8:00 AM a 5:00 PM.</li>
                <li>Recibirás confirmación vía correo electrónico.</li>
                <li>Puedes ver y cancelar tus citas en "<strong>Mis Citas</strong>".</li>
            </ul>
        </div>

        <!-- Botón principal -->
        <div style="text-align: center; margin-top: 40px;">
            <a href="solicitar_cita.php" class="btn btn-primary btn-lg">
                📅 Solicitar una Cita Ahora
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
/*
PROTOTIPO DE CITAS MÉDICAS - Página Principal
Descripción: Página pública de inicio del sistema.
Muestra información general y opciones de login/registro.
*/

require_once 'includes/funciones.php';
iniciar_sesion();

$usuario_autenticado = usuario_autenticado();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema de Citas Médicas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

        .hero-section {
            background: linear-gradient(135deg, #0d6efd 0%, #0d47a1 100%);
            color: white;
            padding: 80px 20px;
            text-align: center;
            border-radius: 10px;
            margin-bottom: 50px;
        }

        .hero-section h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .hero-section p {
            font-size: 1.1rem;
            margin-bottom: 30px;
            opacity: 0.95;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 50px;
        }

        .feature-card {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        .feature-card h3 {
            font-weight: bold;
            color: #0d6efd;
            margin-bottom: 12px;
        }

        .feature-card p {
            color: #6c757d;
            font-size: 0.95rem;
        }

        .btn-group-hero {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-hero {
            font-weight: 500;
            padding: 12px 30px;
            font-size: 1rem;
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
                    <?php if ($usuario_autenticado): ?>
                        <li class="nav-item">
                            <span class="nav-link">Hola, <?php echo htmlspecialchars($_SESSION['usuario']['nombre']); ?></span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="panel_citas.php">Panel de Citas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="perfil.php">Perfil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Cerrar sesión</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Iniciar sesión</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="registro.php">Registrarse</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <div class="container" style="margin-top: 40px; margin-bottom: 50px;">
        <!-- Sección Hero -->
        <div class="hero-section">
            <h1>Sistema de Citas Médicas</h1>
            <p>Gestiona tus citas de forma rápida y sencilla desde cualquier lugar</p>
            
            <?php if ($usuario_autenticado): ?>
                <a href="panel_citas.php" class="btn btn-light btn-hero btn-lg">
                    📋 Ir al Panel de Citas
                </a>
            <?php else: ?>
                <div class="btn-group-hero">
                    <a href="login.php" class="btn btn-light btn-hero">Iniciar sesión</a>
                    <a href="registro.php" class="btn btn-outline-light btn-hero">Crear cuenta</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Características -->
        <h2 style="text-align: center; margin-bottom: 40px; font-weight: bold;">¿Cómo funciona nuestro sistema?</h2>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">📅</div>
                <h3>Solicita tu Cita</h3>
                <p>Elige la especialidad, fecha y hora que mejor se adapten a tu horario.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">👨‍⚕️</div>
                <h3>Profesionales Certificados</h3>
                <p>Nuestros médicos están altamente capacitados y con amplia experiencia.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">📍</div>
                <h3>Ubicación Céntrica</h3>
                <p>Estamos ubicados en el centro de la ciudad con fácil acceso.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">💊</div>
                <h3>Atención de Calidad</h3>
                <p>Te ofrecemos una atención médica integral y personalizada.</p>
            </div>
        </div>

        <!-- Información General -->
        <div class="alert alert-info" role="alert" style="margin-top: 40px;">
            <h5>ℹ️ Información Importante</h5>
            <ul class="mb-0">
                <li><strong>Especialidades disponibles:</strong> Medicina General, Cardiología, Pediatría, Dermatología, Oftalmología.</li>
                <li><strong>Horario de atención:</strong> Lunes a viernes, 8:00 AM a 5:00 PM.</li>
                <li><strong>Para solicitar una cita:</strong> Debes estar registrado e iniciar sesión en el sistema.</li>
                <li><strong>Confirmación:</strong> Recibirás un correo de confirmación después de solicitar tu cita.</li>
            </ul>
        </div>

        <!-- Call to Action -->
        <?php if (!$usuario_autenticado): ?>
            <div style="text-align: center; margin-top: 50px;">
                <h3 style="margin-bottom: 20px;">¿Listo para solicitar tu cita?</h3>
                <div class="btn-group-hero">
                    <a href="registro.php" class="btn btn-primary btn-lg">Crear una cuenta</a>
                    <a href="login.php" class="btn btn-outline-primary btn-lg">Ya tengo cuenta</a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer style="background-color: #f8f9fa; border-top: 1px solid #dee2e6; margin-top: 50px; padding: 30px 0; text-align: center; color: #6c757d;">
        <div class="container">
            <p style="margin-bottom: 0;">
                &copy; 2024 Sistema de Citas Médicas. Todos los derechos reservados.
            </p>
            <small>Este es un prototipo académico para propósitos educativos.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

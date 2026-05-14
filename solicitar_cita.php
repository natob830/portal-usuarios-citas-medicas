<?php
/*
PROTOTIPO DE CITAS MÉDICAS - Solicitar Cita
Descripción: Formulario para simular solicitud de cita.
Los datos se guardan en localStorage (no en BD).
*/

require_once 'includes/funciones.php';

// FORO: Protección - Solo acceso si está autenticado
require_login();

$usuario = $_SESSION['usuario'];

// FORO: Datos simulados de especialidades
$especialidades = [
    1 => 'Medicina General',
    2 => 'Cardiología',
    3 => 'Pediatría',
    4 => 'Dermatología',
    5 => 'Oftalmología'
];

// FORO: Obtener especialidad pre-seleccionada si viene en GET
$especialidad_seleccionada = limpiar($_GET['especialidad'] ?? '');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Solicitar Cita - MediCitas</title>
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

        .form-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            padding: 40px;
            max-width: 600px;
            margin: 40px auto 50px;
        }

        .form-title {
            font-size: 1.8rem;
            font-weight: bold;
            color: #212529;
            margin-bottom: 10px;
            text-align: center;
        }

        .form-subtitle {
            text-align: center;
            color: #6c757d;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            font-weight: 600;
            color: #212529;
            margin-bottom: 8px;
            display: block;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            font-size: 1rem;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
            outline: none;
        }

        .form-help {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 5px;
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            font-size: 1rem;
            font-weight: 600;
            background-color: #0d6efd;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-submit:hover {
            background-color: #0d47a1;
        }

        .btn-volver {
            display: inline-block;
            margin-top: 20px;
            text-align: center;
        }

        .btn-volver a {
            color: #0d6efd;
            text-decoration: none;
            font-weight: 500;
        }

        .btn-volver a:hover {
            text-decoration: underline;
        }

        .alert-info-custom {
            background-color: #cfe2ff;
            border: 1px solid #b6d4fe;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 25px;
            color: #084298;
        }

        .required {
            color: #dc3545;
        }

        .modal-success {
            display: none;
        }

        .modal-success.show {
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .modal-content-custom {
            background: white;
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            max-width: 500px;
        }

        .modal-content-custom .icon {
            font-size: 3rem;
            margin-bottom: 20px;
        }

        .modal-content-custom h2 {
            color: #28a745;
            margin-bottom: 15px;
        }

        .modal-content-custom p {
            color: #6c757d;
            margin-bottom: 20px;
        }

        .btn-close-modal {
            background-color: #0d6efd;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
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
                        <a class="nav-link" href="panel_citas.php">Panel</a>
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

    <!-- Modal de Éxito -->
    <div id="modalExito" class="modal-success">
        <div class="modal-content-custom">
            <div class="icon">✅</div>
            <h2>¡Cita Solicitada!</h2>
            <p>Tu cita ha sido registrada exitosamente. Recibirás una confirmación por correo electrónico.</p>
            <button class="btn-close-modal" onclick="irAMisCitas()">Ver mis citas</button>
        </div>
    </div>

    <!-- Formulario -->
    <div class="container">
        <div class="form-container">
            <h1 class="form-title">📅 Solicitar una Cita</h1>
            <p class="form-subtitle">Completa el formulario para agendar tu cita médica</p>

            <div class="alert-info-custom">
                <strong>📌 Nota:</strong> Este es un prototipo de demostración. Los datos se guardan temporalmente para mostrar el flujo del sistema.
            </div>

            <form id="formSolicitud" method="POST">
                <!-- Especialidad -->
                <div class="form-group">
                    <label for="especialidad">
                        👨‍⚕️ Especialidad <span class="required">*</span>
                    </label>
                    <select id="especialidad" name="especialidad" required>
                        <option value="">-- Selecciona una especialidad --</option>
                        <?php foreach ($especialidades as $id => $nombre): ?>
                            <option value="<?php echo $id; ?>" <?php echo ($especialidad_seleccionada == $id) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($nombre); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Fecha -->
                <div class="form-group">
                    <label for="fecha">
                        📆 Fecha Preferida <span class="required">*</span>
                    </label>
                    <input type="date" id="fecha" name="fecha" required 
                           min="<?php echo date('Y-m-d'); ?>" 
                           max="<?php echo date('Y-m-d', strtotime('+30 days')); ?>">
                    <div class="form-help">Máximo 30 días en adelante</div>
                </div>

                <!-- Hora -->
                <div class="form-group">
                    <label for="hora">
                        🕐 Hora Preferida <span class="required">*</span>
                    </label>
                    <input type="time" id="hora" name="hora" required min="08:00" max="17:00">
                    <div class="form-help">Horario: 8:00 AM a 5:00 PM</div>
                </div>

                <!-- Síntomas/Motivo -->
                <div class="form-group">
                    <label for="motivo">
                        📝 Motivo de la Consulta <span class="required">*</span>
                    </label>
                    <textarea id="motivo" name="motivo" rows="4" placeholder="Describe brevemente tu motivo de consulta..." required></textarea>
                    <div class="form-help">Máximo 300 caracteres</div>
                </div>

                <!-- Teléfono (informativo) -->
                <div class="form-group">
                    <label for="telefono">
                        📞 Teléfono de Contacto
                    </label>
                    <input type="tel" id="telefono" name="telefono" placeholder="Ej: 123-456-7890">
                    <div class="form-help">Opcional - Para confirmación de cita</div>
                </div>

                <!-- Botón Submit -->
                <button type="submit" class="btn-submit">
                    ✓ Confirmar Solicitud
                </button>
            </form>

            <!-- Botón Volver -->
            <div class="btn-volver">
                <a href="panel_citas.php">← Volver al panel</a>
            </div>
        </div>
    </div>

    <!-- Script para manejar el formulario -->
    <script>
        // FORO: Evento del formulario - Guardar datos simulados
        document.getElementById('formSolicitud').addEventListener('submit', function(e) {
            e.preventDefault();

            // Recopilar datos del formulario
            const datos = {
                id: Date.now(),
                especialidad: document.getElementById('especialidad').options[document.getElementById('especialidad').selectedIndex].text,
                fecha: document.getElementById('fecha').value,
                hora: document.getElementById('hora').value,
                motivo: document.getElementById('motivo').value,
                telefono: document.getElementById('telefono').value,
                fecha_solicitud: new Date().toLocaleString('es-ES'),
                estado: 'Pendiente'
            };

            // FORO: Guardar en localStorage (persistencia simulada)
            let citas = JSON.parse(localStorage.getItem('citas_medicas') || '[]');
            citas.push(datos);
            localStorage.setItem('citas_medicas', JSON.stringify(citas));

            // Mostrar modal de éxito
            document.getElementById('modalExito').classList.add('show');
        });

        // Función para ir a mis citas
        function irAMisCitas() {
            window.location.href = 'mis_citas.php';
        }

        // Limitar caracteres en textarea
        document.getElementById('motivo').addEventListener('input', function() {
            if (this.value.length > 300) {
                this.value = this.value.substring(0, 300);
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

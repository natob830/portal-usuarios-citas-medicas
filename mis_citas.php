<?php
/*
PROTOTIPO DE CITAS MÉDICAS - Mis Citas
Descripción: Visualiza las citas guardadas en localStorage.
Los datos son simulados y no se persisten en BD.
*/

require_once 'includes/funciones.php';

// FORO: Protección - Solo acceso si está autenticado
require_login();

$usuario = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mis Citas - MediCitas</title>
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

        .container-citas {
            margin-top: 30px;
            margin-bottom: 50px;
        }

        .filtros {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .filtro-btn {
            padding: 8px 16px;
            border: 2px solid #dee2e6;
            background: white;
            border-radius: 20px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
            color: #6c757d;
        }

        .filtro-btn:hover {
            border-color: #0d6efd;
            color: #0d6efd;
        }

        .filtro-btn.active {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
        }

        .cita-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 25px;
            margin-bottom: 20px;
            border-left: 5px solid #0d6efd;
            transition: all 0.3s;
        }

        .cita-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }

        .cita-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .cita-especialidad {
            font-size: 1.2rem;
            font-weight: bold;
            color: #212529;
        }

        .cita-estado {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .cita-estado.pendiente {
            background-color: #fff3cd;
            color: #856404;
        }

        .cita-estado.confirmada {
            background-color: #d4edda;
            color: #155724;
        }

        .cita-estado.cancelada {
            background-color: #f8d7da;
            color: #721c24;
        }

        .cita-detalles {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .cita-detalle {
            color: #6c757d;
        }

        .cita-detalle-label {
            font-weight: 600;
            color: #212529;
            display: block;
            margin-bottom: 3px;
        }

        .cita-motivo {
            background-color: #f8f9fa;
            border-left: 3px solid #0d6efd;
            padding: 12px;
            border-radius: 5px;
            color: #495057;
            margin-top: 15px;
            font-size: 0.95rem;
        }

        .cita-acciones {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            flex-wrap: wrap;
        }

        .btn-action {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s;
        }

        .btn-cancelar {
            background-color: #dc3545;
            color: white;
        }

        .btn-cancelar:hover {
            background-color: #c82333;
        }

        .btn-editar {
            background-color: #0d6efd;
            color: white;
        }

        .btn-editar:hover {
            background-color: #0d47a1;
        }

        .vacio {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .vacio-icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }

        .alert-info-prototipo {
            background-color: #cfe2ff;
            border: 1px solid #b6d4fe;
            color: #084298;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 30px;
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
                        <a class="nav-link" href="solicitar_cita.php">Solicitar Cita</a>
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

    <!-- Contenido -->
    <div class="container container-citas">
        <h1 style="font-weight: bold; margin-bottom: 10px;">📋 Mis Citas Médicas</h1>
        <p style="color: #6c757d; margin-bottom: 30px;">Visualiza y gestiona todas tus citas agendadas</p>

        <div class="alert-info-prototipo">
            <strong>📌 Nota del Prototipo:</strong> Las citas se guardan temporalmente en tu navegador. Estos datos son de demostración y no se persisten en la base de datos.
        </div>

        <!-- Filtros -->
        <h5 style="font-weight: bold; margin-bottom: 15px;">Filtrar por estado:</h5>
        <div class="filtros">
            <button class="filtro-btn active" data-filtro="todos">Todas</button>
            <button class="filtro-btn" data-filtro="pendiente">Pendientes</button>
            <button class="filtro-btn" data-filtro="confirmada">Confirmadas</button>
            <button class="filtro-btn" data-filtro="cancelada">Canceladas</button>
        </div>

        <!-- Lista de Citas -->
        <div id="citasContainer">
            <!-- Las citas se cargarán aquí con JavaScript -->
        </div>

        <!-- Botón para solicitar -->
        <div style="text-align: center; margin-top: 40px;">
            <a href="solicitar_cita.php" class="btn btn-primary btn-lg">
                📅 Solicitar Nueva Cita
            </a>
        </div>
    </div>

    <!-- Script para cargar citas desde localStorage -->
    <script>
        // FORO: Cargar citas guardadas en localStorage
        function cargarCitas(filtro = 'todos') {
            const container = document.getElementById('citasContainer');
            const citas = JSON.parse(localStorage.getItem('citas_medicas') || '[]');
            
            // Filtrar citas
            let citasFiltradas = citas;
            if (filtro !== 'todos') {
                citasFiltradas = citas.filter(c => c.estado.toLowerCase() === filtro.toLowerCase());
            }

            // Mostrar mensaje si no hay citas
            if (citasFiltradas.length === 0) {
                container.innerHTML = `
                    <div class="vacio">
                        <div class="vacio-icon">📭</div>
                        <p style="font-size: 1.1rem;">No hay citas para mostrar</p>
                        <a href="solicitar_cita.php" class="btn btn-primary" style="margin-top: 15px;">
                            Solicitar una cita
                        </a>
                    </div>
                `;
                return;
            }

            // Mostrar citas
            container.innerHTML = citasFiltradas.map((cita, index) => `
                <div class="cita-card" data-id="${cita.id}">
                    <div class="cita-header">
                        <div class="cita-especialidad">👨‍⚕️ ${cita.especialidad}</div>
                        <span class="cita-estado ${cita.estado.toLowerCase()}">${cita.estado}</span>
                    </div>
                    <div class="cita-detalles">
                        <div class="cita-detalle">
                            <span class="cita-detalle-label">📅 Fecha</span>
                            ${new Date(cita.fecha).toLocaleDateString('es-ES', {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'})}
                        </div>
                        <div class="cita-detalle">
                            <span class="cita-detalle-label">🕐 Hora</span>
                            ${cita.hora}
                        </div>
                        <div class="cita-detalle">
                            <span class="cita-detalle-label">📅 Solicitado</span>
                            ${cita.fecha_solicitud}
                        </div>
                    </div>
                    <div class="cita-motivo">
                        <strong>Motivo:</strong> ${cita.motivo}
                    </div>
                    ${cita.telefono ? `<div class="cita-motivo" style="border-left-color: #999;">
                        <strong>📞 Teléfono:</strong> ${cita.telefono}
                    </div>` : ''}
                    <div class="cita-acciones">
                        <button class="btn-action btn-editar" onclick="editarCita(${index})">
                            ✏️ Editar
                        </button>
                        <button class="btn-action btn-cancelar" onclick="cancelarCita(${index})">
                            ✕ Cancelar
                        </button>
                    </div>
                </div>
            `).join('');
        }

        // FORO: Cancelar una cita
        function cancelarCita(index) {
            if (confirm('¿Estás seguro de que deseas cancelar esta cita?')) {
                let citas = JSON.parse(localStorage.getItem('citas_medicas') || '[]');
                citas[index].estado = 'Cancelada';
                localStorage.setItem('citas_medicas', JSON.stringify(citas));
                cargarCitas(obtenerFiltroActual());
            }
        }

        // FORO: Editar una cita (simulado - solo muestra alerta)
        function editarCita(index) {
            alert('La funcionalidad de editar citas está disponible en la versión completa.');
        }

        // Obtener filtro activo
        function obtenerFiltroActual() {
            const botones = document.querySelectorAll('.filtro-btn');
            let filtroActual = 'todos';
            botones.forEach(btn => {
                if (btn.classList.contains('active')) {
                    filtroActual = btn.getAttribute('data-filtro');
                }
            });
            return filtroActual;
        }

        // Agregar eventos a los botones de filtro
        document.querySelectorAll('.filtro-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                cargarCitas(this.getAttribute('data-filtro'));
            });
        });

        // Cargar citas al iniciar la página
        cargarCitas();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

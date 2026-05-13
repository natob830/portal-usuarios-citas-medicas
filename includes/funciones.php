<?php
/*
FORO: Funciones generales del sistema
Descripción:
Este archivo contiene funciones reutilizables que permiten manejar
sesiones, validar datos y proteger las páginas privadas del portal
de usuarios.
*/

// FORO: Iniciar sesión si aún no está iniciada
// Esta función verifica si ya existe una sesión activa y, si no,
// la inicia para poder usar $_SESSION en todo el sistema.
function iniciar_sesion() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

// FORO: Limpiar datos de entrada
// Esta función elimina espacios innecesarios y caracteres peligrosos
// para evitar problemas de seguridad.
function limpiar($dato) {
    return htmlspecialchars(trim($dato), ENT_QUOTES, 'UTF-8');
}

// FORO: Verificar si el usuario ha iniciado sesión
// Retorna true si existe una sesión activa, false en caso contrario.
function usuario_autenticado() {
    iniciar_sesion();
    return isset($_SESSION['usuario']);
}

// FORO: Proteger páginas privadas
// Si el usuario no ha iniciado sesión, se redirige al login.
function require_login() {
    if (!usuario_autenticado()) {
        header('Location: login.php');
        exit;
    }
}
?>
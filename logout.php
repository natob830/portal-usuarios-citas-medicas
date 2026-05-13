<?php
/*
FORO: Cierre de sesión del usuario
Descripción:
Este archivo se encarga de cerrar la sesión del usuario de forma segura,
eliminando los datos de sesión y redirigiendo al inicio de sesión.
*/

require_once 'includes/funciones.php';

// FORO: Iniciar sesión para poder destruirla
iniciar_sesion();

// FORO: Eliminar todas las variables de sesión
$_SESSION = [];

// FORO: Destruir la sesión
session_destroy();

// FORO: Redirigir al login
header('Location: login.php');
exit;
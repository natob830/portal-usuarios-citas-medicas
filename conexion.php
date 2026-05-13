<?php
/*
FORO: Conexión a la base de datos
Descripción:
Este archivo se encarga de establecer la conexión entre PHP y MySQL
utilizando PDO, permitiendo que el sistema acceda de forma segura a la
base de datos del portal de usuarios.
*/

$host = 'localhost';
$base_datos = 'portal_usuarios';
$usuario = 'root';
$contrasena = ''; // En XAMPP normalmente está vacía

try {
    // FORO: Creación de la conexión PDO
    // Se utiliza PDO para una conexión segura y preparada
    $pdo = new PDO(
        "mysql:host=$host;dbname=$base_datos;charset=utf8mb4",$usuario,$contrasena
    );

    // FORO: Configuración del manejo de errores
    // Se establece que PDO lance excepciones si ocurre un error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} 
catch (PDOException $e) {
    // FORO: Manejo de errores de conexión
    // Si la conexión falla, se muestra un mensaje claro
    die('Error de conexión a la base de datos: ' . $e->getMessage());
}

?>
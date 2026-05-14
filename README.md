# Portal de Usuarios - Prototipo de Citas Médicas

## Descripción del Sistema

Este proyecto es un portal de usuarios desarrollado en PHP y MySQL que implementa un prototipo de sistema de citas médicas. El sistema permite a los usuarios registrarse, autenticarse y gestionar citas médicas de manera segura. Todas las funcionalidades requieren inicio de sesión previo, garantizando la privacidad y seguridad de los datos.

## Requisitos del Sistema

Para ejecutar este proyecto, se requieren los siguientes componentes:

- **PHP**: Versión 8.0 o superior
- **MySQL o MariaDB**: Sistema de gestión de base de datos relacional
- **Servidor web local**: XAMPP (recomendado para entornos de desarrollo)
- **Navegador web moderno**: Chrome, Firefox, Edge u otro navegador actualizado

## Instalación y Prueba Local

Siga estos pasos para instalar y probar el proyecto en un entorno local:

1. **Colocación del proyecto**:
   - Copie la carpeta completa del proyecto en el directorio `htdocs` de su instalación de XAMPP.
   - Asegúrese de que la ruta sea accesible, por ejemplo: `C:\xampp\htdocs\Portal de Usuarios_Desarrollo WEB\`

2. **Importación de la base de datos**:
   - Inicie el panel de control de XAMPP y active los módulos Apache y MySQL.
   - Abra phpMyAdmin desde el panel de XAMPP o acceda directamente a `http://localhost/phpmyadmin/`.
   - Cree una nueva base de datos con el nombre especificado en el archivo `conexion.php`.
   - Importe el archivo de base de datos proporcionado (generalmente un archivo .sql) a través de la opción "Importar" en phpMyAdmin.

3. **Verificación de la conexión**:
   - Abra el archivo `conexion.php` y verifique que los parámetros de conexión (servidor, usuario, contraseña y nombre de la base de datos) sean correctos para su entorno local.
   - Si es necesario, ajuste estos parámetros para que coincidan con su configuración de XAMPP.

4. **Acceso al sistema**:
   - Abra su navegador web y navegue a `http://localhost/Portal de Usuarios_Desarrollo WEB/index.php`.
   - Esta es la página principal del sistema desde donde puede comenzar a explorar las funcionalidades.

El sistema está listo para ser probado. Puede registrarse como nuevo usuario o iniciar sesión si ya tiene credenciales existentes.

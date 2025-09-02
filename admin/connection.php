<?php
// Configuración de la conexión (mejor usar variables de entorno)
define('DB_HOST', 'localhost');
define('DB_NAME', 'actualizacion_crm');
define('DB_USER', 'upgcorr');
define('DB_PASS', 'UozsxYa!!fh}Z87#');

try {
    // Validar que las credenciales no estén vacías
    if (empty(DB_HOST) || empty(DB_NAME) || empty(DB_USER)) {
        throw new Exception("Error: Configuración de la base de datos incompleta.");
    }

    // Cadena de conexión
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";

    // Opciones de PDO
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Lanzar excepciones en errores
        PDO::ATTR_EMULATE_PREPARES => false, // Desactivar preparaciones emuladas
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch mode por defecto
    ];

    // Conexión a la base de datos
    $conexion = new PDO($dsn, DB_USER, DB_PASS, $options);

} catch (Exception $error) {
    // Registrar el error en un archivo de log
    error_log("Error de conexión: " . $error->getMessage());

    // Mostrar un mensaje genérico al usuario
    die("Error: No se pudo conectar a la base de datos. Por favor, inténtelo más tarde.");
}
?>

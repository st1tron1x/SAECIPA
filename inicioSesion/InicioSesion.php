<?php
session_start();
include('../admin/connection.php'); // Asegúrate de que la conexión a la base de datos sea correcta

// Verificar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir los datos del formulario
    $username = $_POST['username'];
    $password = $_POST['password']; // Aquí recibimos la contraseña tal como fue introducida

    try {
        // Consulta para verificar el usuario y la contraseña
        $query = "SELECT id, username, password FROM usuarios WHERE username = :username";
        $stmt = $conexion->prepare($query);
        $stmt->bindValue(':username', $username);
        $stmt->execute();

        // Verificamos si se encontró un usuario con ese nombre
        if ($stmt->rowCount() > 0) {
            // Obtener el usuario
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificar la contraseña (suponiendo que estás almacenando contraseñas de forma segura, con hash)
            // Si las contraseñas están almacenadas sin hash, solo usa: $user['password'] == $password
            if (password_verify($password, $user['password'])) {
                // Las credenciales son correctas, generamos una sesión para el usuario
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                // Redirigir al home.php
                header('Location: ../admin/home.php');
                exit();
            } else {
                // Contraseña incorrecta
                $error_message = 'Contraseña incorrecta. Por favor, inténtelo de nuevo.';
            }
        } else {
            // El usuario no existe
            $error_message = 'El usuario no existe. Por favor, verifique sus credenciales.';
        }
    } catch (Exception $e) {
        // Error de base de datos
        $error_message = 'Error al conectar a la base de datos. Intente más tarde.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.css">
    <title>Iniciar sesión</title>
</head>
<body>
    <div class="container">
        <form action="InicioSesion.php" method="POST">
            <div>
                <label for="username">Usuario</label>
                <input type="text" name="username" required>
            </div>
            <div>
                <label for="password">Contraseña</label>
                <input type="password" name="password" required>
            </div>
            <div>
                <input type="submit" value="Ingresar">
            </div>
            <?php if (isset($error_message)): ?>
                <div style="color: red; text-align: center;"><?php echo $error_message; ?></div>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
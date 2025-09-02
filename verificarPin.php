<?php
session_start();

if (isset($_POST['verify_pin'])) {
    $enteredPin = $_POST['pin'];

    if ($enteredPin == $_SESSION['pin']) {
        // El PIN es correcto, redirigir al home.php
        header("Location: admin/home.php");
        exit();
    } else {
        $error_message = "PIN incorrecto.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de PIN</title>
</head>
<body>
    <form action="verificarPin.php" method="POST">
        <h2>Ingresa el PIN enviado a tu correo</h2>

        <?php if (isset($error_message)): ?>
            <div style="color: red;"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <label for="pin">PIN:</label>
        <input type="text" name="pin" required>

        <button type="submit" name="verify_pin">Verificar PIN</button>
    </form>
</body>
</html>

<?php
include('admin/connection.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/style_index.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
    <link href="https://www.correagro.com/wp-content/uploads/2021/03/logo-150x150.png" rel="shortcout icon">
    <title>Login</title>
</head>
<body>
    <img class="wave" src="assets/6557.jpg">
    <div class="container">
        <div class="img">
            <img src="assets/bg.svg">
        </div>
        <div class="login-content">
            <form action="inicioSesion/InicioSesion.php" method="POST">
                <img src="assets/Logo-ai.jpg">
                <h2 class="title">BIENVENIDO</h2>

                <?php if (isset($error_message)): ?>
                    <div style="color: red; text-align: center;"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <div class="input-div one">
                    <div class="i">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="div">
                        <h5>Usuario</h5>
                        <input id="usuario" type="text" class="input" required name="username">
                    </div>
                </div>
                <div class="input-div pass">
                    <div class="i">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="div">
                        <h5 class="">Contraseè´–a</h5>
                        <input type="password" id="input" class="input" required name="password">
                    </div>
                </div>
                <div class="view">
                    <div class="fas fa-eye verPassword" onclick="vista()" id="verPassword"></div>
                </div>

                <div class="text-center">
                    <a class="font-italic isai5" href="">Olvide mi contraseè´–a</a>
                    <a class="font-italic isai5" href="registrarse.php">Registrarse</a>
                </div>
                <div class="checkbox font-italic isai5">
                    <input type="checkbox" name="" id="remember-me">
                    <label for="remember-me">Recordar</label>
                </div>
                <input type="submit" name="btningresar" class="btn" value="INGRESAR">
            </form>
        </div>
    </div>
    <script src="js/fontawesome.js"></script>
    <script src="js/main.js"></script>
    <script src="js/main2.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/bootstrap.bundle.js"></script>
</body>
</html>
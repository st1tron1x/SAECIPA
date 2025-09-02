<?php
// Si existe el usuario, este quede inscrito en unas variables de sesión
session_start();

// Recepción de datos y pregunta
if ($_POST) {
    include("./connection.php");

    $usuario = (isset($_POST['usuario'])) ? $_POST['usuario'] : "";
    $password = (isset($_POST['password'])) ? $_POST['password'] : "";

    // SELECCIÓN DE REGISTROS
    $sentencia = $conexion->prepare("SELECT login, columna_10 FROM clients WHERE login = :usuario");
    $sentencia->bindParam(":usuario", $usuario);
    $sentencia->execute();

    $lista_usuarios = $sentencia->fetch(PDO::FETCH_LAZY);

    /*if ($lista_usuarios && password_verify($password, $lista_usuarios['columna_10'])) {
        print_r("El usuario y contraseña son correctos");
        $_SESSION['usuario'] = $lista_usuarios['login'];
        $_SESSION['logueado'] = true;
        header("Location:home.php");
    } else {
        $mensaje = "Error: El usuario o la contraseña son incorrectos";
    }*/
    if ($lista_usuarios && $password == $lista_usuarios['columna_10']) {
        // La contraseña es correcta
        $_SESSION['usuario'] = $lista_usuarios['login'];
        $_SESSION['logueado'] = true;
        header("Location: home.php");
    } else {
        $mensaje = "Error: El usuario o la contraseña son incorrectos";
    }    
}
?>
<!doctype html>
<html lang="es">
<head>
    <title>Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="SHORTCUT ICON" href="https://www.correagro.com/wp-content/uploads/2021/03/logo-150x150.png">
</head>
<body>
    <header>
        <!-- place navbar here -->
    </header>
    <main>
        <div class="container">
            <div class="row">
                <div class="col-4"></div>
                <div class="col-4">
                    <br /><br />
                    <?php if (isset($mensaje)) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            <strong><?php echo $mensaje; ?></strong>
                        </div>
                    <?php } ?>

                    <div class="card">
                        <div class="card-header">Login</div>
                        <div class="card-body">
                            <script>
                                var alertList = document.querySelectorAll('.alert');
                                alertList.forEach(function(alert) {
                                    new bootstrap.Alert(alert)
                                });
                            </script>

                            <form action="" method="post">
                                <div class="mb-3">
                                    <label for="usuario" class="form-label">Usuario</label>
                                    <input type="text" class="form-control" name="usuario" id="usuario" aria-describedby="helpId" placeholder="Usuario o Correo">
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Contraseña</label>
                                    <input type="password" class="form-control" name="password" id="password" aria-describedby="helpId" placeholder="Contraseña">
                                </div>

                                <input name="" id="" class="btn btn-success" type="submit" value="Iniciar">
                            </form>
                        </div>
                        <div class="card-footer text-muted"></div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <!-- place footer here -->
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous"></script>
</body>
</html>

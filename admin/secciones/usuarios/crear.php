<?php
include("../../connection.php");

if($_POST){
    $usuario=(isset($_POST['usuario']))?$_POST['usuario']:"";
    $password=(isset($_POST['password']))?$_POST['password']:"";
    $correo=(isset($_POST['correo']))?$_POST['correo']:"";

    $sentencia=$conexion->prepare("INSERT INTO `tbl_usuarios` 
    (`ID`, `usuario`, `password`, `correo`) 
    VALUES (NULL,:usuario, :password, :correo);");
    
    $sentencia->bindParam(":usuario",$usuario);
    $sentencia->bindParam(":password",$password);
    $sentencia->bindParam(":correo",$correo);    
    $sentencia->execute();
    $mensaje="Registro Creado con exito";
    header("Location:index.php?mensaje=".$mensaje);
}

include("../../templates/header.php");
?>

<div class="card">
    <div class="card-header">
        Usuario
    </div>
    <div class="card-body">
        <form action="" method="post">
            <div class="mb-3">
                <label for="usuario" class="form-label">Nombre Usuario:</label>
                <input type="text"
                    class="form-control" name="usuario" id="usuario" aria-describedby="helpId" placeholder="Usuario">
            </div>
            
            <div class="mb-3">
              <label for="password" class="form-label">Contraseña:</label>
              <input type="password"
                class="form-control" name="password" id="password" aria-describedby="helpId" placeholder="Contraseña"> 
            </div>

            <div class="mb-3">
              <label for="correo" class="form-label">Correo:</label>
              <input type="email" class="form-control" name="correo" id="correo" aria-describedby="emailHelpId" placeholder="Correo">
            </div>
            <button type="submit" class="btn btn-success">Agregar</button>
            |
            <a name="" id="" class="btn btn-success" href="index.php" role="button">Cancelar</a>
        </form>
    </div>
</div>


<?php
include("../../templates/footer.php");
?>
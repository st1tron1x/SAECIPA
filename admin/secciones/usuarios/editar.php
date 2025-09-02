<?php
include("../../connection.php");

if(isset($_GET['txtID'])){
    //editar o recuperar los registros con el ID correspondiente de la BD
    //echo $_GET['textID'];
    $txtID=(isset($_GET['txtID']) )?$_GET['txtID']:"";

    $sentencia=$conexion->prepare("SELECT * FROM tbl_usuarios WHERE id=:id");
    $sentencia->bindParam(":id",$txtID);
    $sentencia->execute();
    $registro=$sentencia->fetch(PDO::FETCH_LAZY);
//Asignar registros a una variable
    $usuario=$registro['usuario'];
    $correo=$registro['correo'];
    $password=$registro['password'];    
}
//Actualizar los campos en las tablas
if($_POST){
    //print_r($_POST);

    //Recepcion de valores de form
    $txtID=(isset($_POST['txtID']))?$_POST['txtID']:"";
    $usuario=(isset($_POST['usuario']))?$_POST['usuario']:"";
    $correo=(isset($_POST['correo']))?$_POST['correo']:"";
    $password=(isset($_POST['password']))?$_POST['password']:"";

    //echo $icono;
    //ejecutar instruccion de actualización
    $sentencia=$conexion->prepare("UPDATE tbl_usuarios 
    SET 
    usuario=:usuario, 
    correo=:correo, 
    password=:password 
    WHERE id=:id");

    $sentencia->bindParam(":id",$txtID);
    $sentencia->bindParam(":usuario",$usuario);
    $sentencia->bindParam(":correo",$correo);
    $sentencia->bindParam(":password",$password);
    $sentencia->execute();
    $mensaje="Registro Modificado con exito";
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
              <label for="txtID" class="form-label">ID</label>
              <input readonly type="text"
                class="form-control"  value="<?php echo $txtID;?>" name="txtID" id="txtID" aria-describedby="helpId" placeholder="ID">
            </div>

            <div class="mb-3">
                <label for="usuario" class="form-label">Nombre Usuario:</label>
                <input type="text"
                    class="form-control" value="<?php echo $usuario;?>" name="usuario" id="usuario" aria-describedby="helpId" placeholder="Usuario">
            </div>
            
            <div class="mb-3">
              <label for="password" class="form-label">Contraseña:</label>
              <input type="password"
                class="form-control" value="<?php echo $password;?>" name="password" id="password" aria-describedby="helpId" placeholder="Contraseña"> 
            </div>

            <div class="mb-3">
              <label for="correo" class="form-label">Correo:</label>
              <input type="email" value="<?php echo $correo;?>" class="form-control" name="correo" id="correo" aria-describedby="emailHelpId" placeholder="Correo">
            </div>
            <button type="submit" class="btn btn-success">Actualizar</button>
            |
            <a name="" id="" class="btn btn-success" href="index.php" role="button">Cancelar</a>
        </form>
    </div>
</div>
<?php
include("../../templates/footer.php");
?>
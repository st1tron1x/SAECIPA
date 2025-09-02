<?php

include("../../../bdmyspa.php");
if(isset($_GET['txtID'])){
    //editar o recuperar los registros con el ID correspondiente de la BD
    //echo $_GET['textID'];
    $txtID=(isset($_GET['txtID']) )?$_GET['txtID']:"";

    $sentencia=$conexion->prepare("SELECT * FROM tbl_sspa WHERE id=:id");
    $sentencia->bindParam(":id",$txtID);
    $sentencia->execute();
    $registro=$sentencia->fetch(PDO::FETCH_LAZY);
//Asignar registros a una variable
    $icono=$registro['icono'];
    $titulo=$registro['titulo'];
    $descripcion=$registro['descripcion'];
}

//Actualizar los campos en las tablas
if($_POST){
    //print_r($_POST);

    //Recepcion de valores de form
    $icono=(isset($_POST['txtID']))?$_POST['txtID']:"";
    $icono=(isset($_POST['icono']))?$_POST['icono']:"";
    $titulo=(isset($_POST['titulo']))?$_POST['titulo']:"";
    $descripcion=(isset($_POST['descripcion']))?$_POST['descripcion']:"";

    //echo $icono;
//ejecutar instruccion de actualización
    $sentencia=$conexion->prepare("UPDATE tbl_sspa 
    SET 
    titulo=:titulo, 
    icono=:icono, 
    descripcion=:descripcion 
    WHERE id=:id");

    $sentencia->bindParam(":id",$txtID);
    $sentencia->bindParam(":icono",$icono);
    $sentencia->bindParam(":titulo",$titulo);
    $sentencia->bindParam(":descripcion",$descripcion);

    $sentencia->execute();
    $mensaje="Registro Modificado con exito";
    header("Location:index.php?mensaje=".$mensaje);

}

include("../../../templates/header.php");
?>

<div class="card">
    <div class="card-header">
        Editar Servicios
    </div>
    <div class="card-body">
        <form action="" enctype="multipart/form-data" method="post">

        <div class="mb-3">
          <label for="textID" class="form-label">ID:</label>
          <input readonly value="<?php echo $txtID;?>" type="text"
            class="form-control" name="textID" id="textID" aria-describedby="helpId" placeholder="ID">
        </div>

        <div class="mb-3">
            <label for="" class="form-label">Icono:</label>
            <input value="<?php echo $icono;?>" type="text"
               class="form-control" name="icono" id="icono" aria-describedby="helpId" placeholder="Icono">
        </div>
        
        <div class="mb-3">
          <label for="titulo" class="form-label">Titulo:</label>
          <input value="<?php echo $titulo;?>" type="text"
            class="form-control" name="titulo" id="titulo" aria-describedby="helpId" placeholder="Titulo">
        </div>

        <div class="mb-3">
          <label for="descripcion" class="form-label">Descrpcion:</label>
          <input value="<?php echo $descripcion;?>" type="text"
            class="form-control" name="descripcion" id="descripcion" aria-describedby="helpId" placeholder="Descripcion">
          
        </div>

        <button type="submit" class="btn btn-success">Editar</button>
        <a name="" id="" class="btn btn-success" href="index.php" role="button">Cancelar</a>

        </form>
    </div>
    <div class="card-footer text-muted">
        
    </div>
</div>

<?php
include("../../../templates/footer.php");
?>
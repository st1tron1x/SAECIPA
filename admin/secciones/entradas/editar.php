<?php
include("../../connection.php");

if(isset($_GET['txtID'])){
    //editar o recuperar los registros con el ID correspondiente de la BD
    //echo $_GET['textID'];
    $txtID=(isset($_GET['txtID']) )?$_GET['txtID']:"";
    //realizar consulta de registros:
    $sentencia=$conexion->prepare("SELECT * FROM tbl_entradas WHERE id=:id");
    $sentencia->bindParam(":id",$txtID);
    $sentencia->execute();
    $registro=$sentencia->fetch(PDO::FETCH_LAZY);

    //Asignar registros a una variable
    $fecha=$registro["fecha"];
    $titulo=$registro["titulo"];
    $imagen=$registro["imagen"];
    $descripcion=$registro["descripcion"];
}

if($_POST){
    //Recepcion de valores de form
    $txtID=(isset($_POST['txtID']))?$_POST['txtID']:"";
    $fecha=(isset($_POST['fecha']))?$_POST['fecha']:"";
    $titulo=(isset($_POST['titulo']))?$_POST['titulo']:"";
    $descripcion=(isset($_POST['descripcion']))?$_POST['descripcion']:"";
    
    $sentencia=$conexion->prepare("UPDATE `tbl_entradas`
    SET fecha=:fecha,titulo=:titulo,descripcion=:descripcion WHERE id=:id");

    $sentencia->bindParam(":fecha",$fecha);
    $sentencia->bindParam(":titulo",$titulo);
    $sentencia->bindParam(":descripcion",$descripcion);
    $sentencia->bindParam(":id",$txtID);
    $sentencia->execute();
    //$mensaje="Registro Creado con exito";
    //header("Location:index.php?mensaje=".$mensaje);

    //creando validacion para actualizar la imagen
    if($_FILES["imagen"]["tmp_name"]!=""){//validar, si hay una imagen?
      //obtener todos los datos de la imagen
      $imagen=(isset($_FILES["imagen"]["name"]))?$_FILES["imagen"]["name"]:"";
      $fecha_imagen=new DateTime();//se agarra el tiempo para poner otro nombre a la imagen_temporal
      $nombre_archivo_imagen=($imagen!="")?$fecha_imagen->getTimestamp()."_".$imagen:"";
      //Mover la imagen al directorio
      $tmp_imagen=$_FILES["imagen"]["tmp_name"];
      
      move_uploaded_file($tmp_imagen,"../../../assets/img/about/".$nombre_archivo_imagen);

      //borrado archivo anterior
      $sentencia=$conexion->prepare("SELECT imagen FROM tbl_entradas WHERE id=:id");
      $sentencia->bindParam(":id",$txtID);
      $sentencia->execute();
      $registro_imagen=$sentencia->fetch(PDO::FETCH_LAZY);

      //Preguntar si el registro existe y si el registro existe borrarlo.

      if(isset($registro_imagen["imagen"])){
          if(file_exists("../../../assets/img/about/".$registro_imagen["imagen"])){
              //borrar imagen del directorio
              unlink("../../../assets/img/about/".$registro_imagen["imagen"]);
      }
  }
      
      //actualizar o cambiar imagen y nombre.
      $sentencia=$conexion->prepare("UPDATE tbl_entradas SET imagen=:imagen WHERE id=:id");
      $sentencia->bindParam("imagen",$nombre_archivo_imagen);
      $sentencia->bindParam(":id",$txtID);
      $sentencia->execute();
      $imagen=$nombre_archivo_imagen;
  }

  $mensaje="Registro Modificado con Exito.";
  header("Location:index.php?mensaje".$mensaje);
}

include("../../templates/header.php");
?>

<div class="card">
    <div class="card-header">
        Entrada / Comentarios
    </div>
    <div class="card-body">

    <form action="" method="post" enctype="multipart/form-data">

        <div class="mb-3">
          <label for="txtID" class="form-label">ID</label>
          <input type="text"
            class="form-control" value="<?php echo $txtID;?>" readonly name="txtID" id="txtID" aria-describedby="helpId" placeholder="ID">
        </div>
    
        <div class="mb-3">
          <label for="fecha" class="form-label">Fecha:</label>
          <input type="date"
            class="form-control" value="<?php echo $fecha;?>" name="fecha" id="fecha" aria-describedby="helpId" placeholder="Fecha">
          </div>

        <div class="mb-3">
          <label for="titulo" class="form-label">Título:</label>
          <input type="text"
            class="form-control" value="<?php echo $titulo;?>" name="titulo" id="titulo" aria-describedby="helpId" placeholder="Título">
        </div>

        <div class="mb-3">
          <label for="imagen" class="form-label">Imagen:</label>
          <img width="50" src="../../../assets/img/about/<?php echo $imagen;?>" alt="">
          <input type="file"
            class="form-control" name="imagen" id="imagen" aria-describedby="helpId" placeholder="Imagen">
        </div>

        <div class="mb-3">
          <label for="descripcion" class="form-label">Descripción:</label>
          <input type="text"
            class="form-control" value="<?php echo $descripcion;?>" name="descripcion" id="descripcion" aria-describedby="helpId" placeholder="Descripción">
        </div>        

        <button type="submit" class="btn btn-success">Actualizar</button>
        |
        <a name="" id="" class="btn btn-success" href="index.php" role="button">Cancelar</a>


    </form>
        
    </div>
    <div class="card-footer text-muted">
        
    </div>
</div>

<?php
include("../../templates/footer.php");
?>
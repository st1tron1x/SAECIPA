<?php
include("../../connection.php");

if(isset($_GET['txtID'])){
    //editar o recuperar los registros con el ID correspondiente de la BD
    //echo $_GET['textID'];
    $txtID=(isset($_GET['txtID']) )?$_GET['txtID']:"";
    //realizar consulta de registros:
    $sentencia=$conexion->prepare("SELECT * FROM tbl_equipo WHERE id=:id");
    $sentencia->bindParam(":id",$txtID);
    $sentencia->execute();
    $registro=$sentencia->fetch(PDO::FETCH_LAZY);

    //Asignar registros a una variable
    $imagen=$registro["imagen"];
    $nombrecompleto=$registro["nombrecompleto"];
    $puesto=$registro["puesto"];    
    $X=$registro["x"];
    $facebook=$registro["facebook"];
    $instagram=$registro["instagram"];
    $linkedin=$registro["linkedin"];
}

if($_POST){
    //Recepcion de valores de form
    $txtID=(isset($_POST['txtID']))?$_POST['txtID']:"";
    $nombrecompleto=(isset($_POST['nombrecompleto']))?$_POST['nombrecompleto']:"";
    $puesto=(isset($_POST['puesto']))?$_POST['puesto']:"";
    $X=(isset($_POST['x']))?$_POST['x']:"";
    $facebook=(isset($_POST['facebook']))?$_POST['facebook']:"";
    $instagram=(isset($_POST['instagram']))?$_POST['instagram']:"";
    $linkedin=(isset($_POST['linkedin']))?$_POST['linkedin']:"";
    
    $sentencia=$conexion->prepare("UPDATE tbl_equipo
    SET nombrecompleto=:nombrecompleto,puesto=:puesto,X=:X,facebook=:facebook,instagram=:instagram,
    linkedin=:linkedin WHERE id=:id");

    $sentencia->bindParam(":nombrecompleto",$nombrecompleto);
    $sentencia->bindParam(":puesto",$puesto);
    $sentencia->bindParam(":x",$x);
    $sentencia->bindParam(":facebook",$facebook);
    $sentencia->bindParam(":instagram",$instagram);
    $sentencia->bindParam(":linkedin",$linkedin);
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
      
      move_uploaded_file($tmp_imagen,"../../../assets/img/team/".$nombre_archivo_imagen);

      //borrado archivo anterior
      $sentencia=$conexion->prepare("SELECT imagen FROM tbl_equipo WHERE id=:id");
      $sentencia->bindParam(":id",$txtID);
      $sentencia->execute();
      $registro_imagen=$sentencia->fetch(PDO::FETCH_LAZY);

      //Preguntar si el registro existe y si el registro existe borrarlo.

      if(isset($registro_imagen["imagen"])){
          if(file_exists("../../../assets/img/team/".$registro_imagen["imagen"])){
              //borrar imagen del directorio
              unlink("../../../assets/img/team/".$registro_imagen["imagen"]);
      }
  }
      
      //actualizar o cambiar imagen y nombre.
      $sentencia=$conexion->prepare("UPDATE tbl_equipo SET imagen=:imagen WHERE id=:id");
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
        Edición Informacion de Equipo
    </div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            
            <div class="mb-3">
            <label for="txtID" class="form-label">ID</label>
            <input type="text"
                class="form-control" value="<?php echo $txtID;?>" readonly name="txtID" id="txtID" aria-describedby="helpId" placeholder="ID">
            </div>
        
            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen:</label>
                <img width="50" src="../../../assets/img/team/<?php echo $imagen;?>" alt="">
                <input type="file"
                    class="form-control" name="imagen" id="imagen" aria-describedby="helpId" placeholder="Imagen">
            </div>

            <div class="mb-3">
                <label for="nombrecompleto" class="form-label">Nombre Completo:</label>
                <input type="text"
                    class="form-control" value="<?php echo $nombrecompleto;?>" name="nombrecompleto" id="nombrecompleto" aria-describedby="helpId" placeholder="Nombre Completo">
            </div>

            <div class="mb-3">
              <label for="puesto" class="form-label">Puesto:</label>
              <input type="text"
                class="form-control" value="<?php echo $puesto;?>" name="puesto" id="puesto" aria-describedby="helpId" placeholder="Puesto">
            </div>

            <div class="mb-3">
              <label for="x" class="form-label">x:</label>
              <input type="text"
                class="form-control" value="<?php echo $x;?>" name="x" id="x" aria-describedby="helpId" placeholder="x">
            </div>

            <div class="mb-3">
              <label for="facebook" class="form-label">Facebook:</label>
              <input type="text"
                class="form-control" value="<?php echo $facebook;?>" name="facebook" id="facebook" aria-describedby="helpId" placeholder="Facebook">
            </div>

            <div class="mb-3">
              <label for="instagram" class="form-label">Instagram:</label>
              <input type="text"
                class="form-control" value="<?php echo $instagram;?>" name="instagram" id="instagram" aria-describedby="helpId" placeholder="instagram">
            </div>

            <div class="mb-3">
              <label for="linkedin" class="form-label">Linkedin:</label>
              <input type="text"
                class="form-control" value="<?php echo $linkedin;?>" name="linkedin" id="linkedin" aria-describedby="helpId" placeholder="Linkedin">
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
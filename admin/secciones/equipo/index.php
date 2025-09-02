<?php
include("../../connection.php");

//borrando registros por el ID
if(isset($_GET['txtID'])){
    //editar o recuperar los registros con el ID correspondiente de la BD para la funcion del boton eliminiar
    //echo $_GET['textID'];
    $txtID=(isset($_GET['txtID']) )?$_GET['txtID']:"";
    //eliminar imagen tanto de la BD como de la carpeta
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
//Borra registro DB.
    $sentencia=$conexion->prepare("DELETE FROM tbl_equipo WHERE id=:id");
    $sentencia->bindParam(":id",$txtID);
    $sentencia->execute();

}

//seleccionar registros
$sentencia=$conexion->prepare("SELECT * FROM `tbl_equipo`");
$sentencia->execute();
$lista_equipo=$sentencia->fetchAll(PDO::FETCH_ASSOC);



include("../../templates/header.php");
?>

<div class="card">
    <div class="card-header">
    <a name="" id="" class="btn btn-success" href="crear.php" role="button">Crear</a>
    </div>
    <div class="card-body">
        <div class="table-responsive-sm">
            <table class="table table-success">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Imagen</th>
                        <th scope="col">Nombre_Completo</th>
                        <th scope="col">Puesto</th>
                        <th scope="col">Redes Sociales</th>                        
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($lista_equipo as $registros){ ?>
                        <tr class="">
                            <td scope="row"><?php echo $registros['ID'];?></td>
                            <td scope="col">
                                <img width="70" src="../../../assets/img/team/<?php echo $registros['imagen'];?>" /> 
                            </td>
                            <td><?php echo $registros['nombrecompleto'];?></td>
                            <td><?php echo $registros['puesto'];?></td>
                            <td>
                                <?php echo $registros['x'];?></br>
                                <?php echo $registros['facebook'];?></br>
                                <?php echo $registros['instagram'];?></br>
                                <?php echo $registros['linkedin'];?>
                            </td>
                            <td scope="col">
                            <a name="" id="" class="btn btn-success" href="editar.php?txtID=<?php echo $registros['ID']; ?>" role="button">Editar</a>
                                |
                            <a name="" id="" class="btn btn-danger" href="index.php?txtID=<?php echo $registros['ID']; ?>"role="button">Eliminiar</a>
                            </td>
                        </tr>
                    <?php } ?>    
                </tbody>
            </table>
        </div>
        
    </div>
    <div class="card-footer text-muted">
        
    </div>
</div>

<?php
include("../../templates/footer.php");
?>
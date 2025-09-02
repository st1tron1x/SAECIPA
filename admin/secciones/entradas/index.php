<?php
include("../../connection.php");

//borrando registros por el ID
if(isset($_GET['txtID'])){
    //editar o recuperar los registros con el ID correspondiente de la BD para la funcion del boton eliminiar
    //echo $_GET['textID'];
    $txtID=(isset($_GET['txtID']) )?$_GET['txtID']:"";
    //eliminar imagen tanto de la BD como de la carpeta
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
//Borra registro DB.
    $sentencia=$conexion->prepare("DELETE FROM tbl_entradas WHERE id=:id");
    $sentencia->bindParam(":id",$txtID);
    $sentencia->execute();

}

//seleccion de registros
$sentencia=$conexion->prepare("SELECT * FROM `tbl_entradas`");
$sentencia->EXECUTE();
$lista_entradas=$sentencia->fetchAll(PDO::FETCH_ASSOC);



include("../../templates/header.php");
?>

<div class="card">
    <div class="card-header">
    <a name="" id="" class="btn btn-success" href="crear.php" role="button">Crear</a>
    </div>
    <div class="card-body">
        
    <div class="table-responsive-sm">
        <table class="table table table-success">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Título</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Imagen</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($lista_entradas as $registros){ ?>
                <tr class="">
                <td scope="col"><?php echo $registros['ID'];?></td>
                    <td><?php echo $registros['fecha'];?></td>
                    <td><?php echo $registros['titulo'];?></td>
                    <td><?php echo $registros['descripcion'];?></td>
                    <td scope="col">
                        <img width="70" src="../../../assets/img/about/<?php echo $registros['imagen'];?>" /> 
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
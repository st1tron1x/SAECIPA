<?php
include("../../connection.php");

if(isset($_GET['txtID'])){
    //editar o recuperar los registros con el ID correspondiente de la BD para la funcion del boton eliminiar
    //echo $_GET['textID'];
    $txtID=(isset($_GET['txtID']) )?$_GET['txtID']:"";
    //eliminar imagen tanto de la BD como de la carpeta
    $sentencia=$conexion->prepare("SELECT imagen FROM tbl_portafolio WHERE id=:id");
    $sentencia->bindParam(":id",$txtID);
    $sentencia->execute();
    $registro_imagen=$sentencia->fetch(PDO::FETCH_LAZY);

    //Preguntar si el registro existe y si el registro existe borrarlo.

    if(isset($registro_imagen["imagen"])){
        if(file_exists("../../../assets/img/portfolio/".$registro_imagen["imagen"])){
            //borrar imagen del directorio
            unlink("../../../assets/img/portfolio/".$registro_imagen["imagen"]);
        }
    }
//Borra registro DB.
    $sentencia=$conexion->prepare("DELETE FROM tbl_portafolio WHERE id=:id");
    $sentencia->bindParam(":id",$txtID);
    $sentencia->execute();

}  

//seleccion de registros
$sentencia=$conexion->prepare("SELECT * FROM `tbl_portafolio`");
$sentencia->EXECUTE();
$lista_portafolio=$sentencia->fetchAll(PDO::FETCH_ASSOC);
include("../../templates/header.php");
?>

<div class="card">
    <div class="card-header">
    <a name="" id="" class="btn btn-success" href="crear.php" role="button">Agregar Registro</a>
    </div>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Título</th>
                    <th scope="col">Subtitulo</th>
                    <th scope="col">Imagen</th>
                    <th scope="col">Descripcion</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">Categoría</th>
                    <th scope="col">Url</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($lista_portafolio as $registros){?>
                <tr class="">
                    <td scope="col"><?php echo $registros['ID'];?></td>
                    <td scope="col"><?php echo $registros['titulo'];?></td>
                    <td scope="col"><?php echo $registros['subtitulo'];?></td>
                    
                    <td scope="col">
                        <img width="70" src="../../../assets/img/portfolio/<?php echo $registros['imagen'];?>" /> 
                    </td>
                    
                    <td scope="col"><?php echo $registros['descripcion'];?></td>
                    <td scope="col"><?php echo $registros['cliente'];?></td>
                    <td scope="col"><?php echo $registros['categoria'];?></td>
                    <td scope="col"><?php echo $registros['url'];?></td>
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

<?php
include("../../templates/footer.php");
?>
<?php
include("../../bdmyspa.php");

if(isset($_GET['txtID'])){
//borrar registros con el ID correspondiente de la BD
//echo $_GET['textID'];

$txtID=(isset($_GET['txtID']) )?$_GET['txtID']:"";
$sentencia=$conexion->prepare("DELETE FROM tbl_servicios WHERE id=:id");
$sentencia->bindParam(":id",$txtID);
$sentencia->execute();
}
//seleccion de registros
$sentencia=$conexion->prepare("SELECT * FROM `tbl_servicios`");
$sentencia->EXECUTE();
$lista_servicios=$sentencia->fetchAll(PDO::FETCH_ASSOC);
//print_r($lista_servicios);

include("../../templates/header.php");
?>
<!--Listar Spa-->
<div class="card">
    <div class="card-header">
        <a name="" id="" class="btn btn-success" href="crear.php" role="button">Agregar Servicios</a>
        
    </div>
    <div class="card-body">
        <div class="table-responsive-sm">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Icono</th>
                        <th scope="col">Titulo</th>
                        <th scope="col">Descripcion</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($lista_servicios as $registros){?>
                    <tr class="">
                        <td><?php echo $registros['ID'];?></td>
                        <td><?php echo $registros['icono'];?></td>
                        <td><?php echo $registros['titulo'];?></td>
                        <td><?php echo $registros['descripcion'];?></td>
                        <td>
                            <a name="" id="" class="btn btn-success" href="editar.php?txtID=<?php echo $registros['ID']; ?>" role="button">Editar</a>
                            |
                            <a name="" id="" class="btn btn-danger" href="index.php?txtID=<?php echo $registros['ID']; ?>" role="button">Eliminiar</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        
    </div>
</div>

<?php
include("../../templates/footer.php");
?>
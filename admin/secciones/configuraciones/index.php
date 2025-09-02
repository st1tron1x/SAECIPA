<?php
include("../../connection.php");

if(isset($_GET['txtID'])){
    //borrar registros con el ID correspondiente de la BD
    //echo $_GET['textID'];
    
    $txtID=(isset($_GET['txtID']) )?$_GET['txtID']:"";
    $sentencia=$conexion->prepare("DELETE FROM tbl_configuraciones WHERE id=:id");
    $sentencia->bindParam(":id",$txtID);
    $sentencia->execute();
}
//seleccion de registros
$sentencia=$conexion->prepare("SELECT * FROM `tbl_configuraciones`");
$sentencia->EXECUTE();
$lista_configuraciones=$sentencia->fetchAll(PDO::FETCH_ASSOC);

include("../../templates/header.php");
?>

<div class="card">
    <div class="card-header">
    <!--<a name="" id="" class="btn btn-success" href="crear.php" role="button">Crear</a>-->
    Configuraciones
    </div>
    <div class="card-body">
        <div class="table-responsive-sm">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre Conf</th>
                        <th scope="col">Valor</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($lista_configuraciones as $registros){ ?>
                    <tr class="">
                        <td><?php echo $registros['ID'];?></td>
                        <td><?php echo $registros['nombreconfiguracion'];?></td>
                        <td><?php echo $registros['valor'];?></td>
                        <td scope="col">
                        <a name="" id="" class="btn btn-success" href="editar.php?txtID=<?php echo $registros['ID']; ?>" role="button">Editar</a>
                        <!--|
                        <a name="" id="" class="btn btn-danger" href="index.php?txtID=<?php echo $registros['ID']; ?>"role="button">Eliminiar</a>-->
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
<?php
include("../../connection.php");

if(isset($_GET['txtID'])){
    //borrar registros con el ID correspondiente de la BD
    //echo $_GET['textID'];
    
    $txtID=(isset($_GET['txtID']) )?$_GET['txtID']:"";
    $sentencia=$conexion->prepare("DELETE FROM tbl_usuarios WHERE id=:id");
    $sentencia->bindParam(":id",$txtID);
    $sentencia->execute();
    }

//seleccionar registros
$sentencia=$conexion->prepare("SELECT * FROM `tbl_usuarios`");
$sentencia->execute();
$lista_usuarios=$sentencia->fetchAll(PDO::FETCH_ASSOC);

include("../../templates/header.php");
?>

<div class="card">
    <div class="card-header">
    <a name="" id="" class="btn btn-success" href="crear.php" role="button">Crear</a>
    </div>
    <div class="card-body">
        <div class="table-responsive-sm">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Usuario</th>
                    <th scope="col">Correo</th>
                    <th scope="col">Contraseña</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($lista_usuarios as $registros){ ?>
                <tr class="">
                    <td scope="row"><?php echo $registros['usuario'];?></td>
                    <td><?php echo $registros['correo'];?></td>
                    <td><?php echo $registros['password'];?></td>
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
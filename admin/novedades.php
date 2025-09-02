<?php
include('templates/header.php');
include('connection.php');

try {
    // Contar registros por estado en facturas_cipa
    $sql_count = "SELECT Marcacion, COUNT(*) as total FROM facturas_cipa WHERE Marcacion IN (1, 2) GROUP BY Marcacion";
    $stmt = $conexion->prepare($sql_count);
    $stmt->execute();
    $counts = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    // Contar Notas Crédito No Aplicadas en la tabla notas
    $sql_notas = "SELECT COUNT(*) as total FROM notas WHERE marcacion IS NULL OR marcacion = 0";
    $stmt_notas = $conexion->prepare($sql_notas);
    $stmt_notas->execute();
    $total_notas_no_aplicadas = $stmt_notas->fetchColumn();
} catch (Exception $e) {
    error_log("Error en la consulta de conteo: " . $e->getMessage());
    $counts = [1 => 0, 2 => 0];
    $total_notas_no_aplicadas = 0;
}

// Mapeo de códigos de marcación
$marcacion_labels = [
    1 => "Registros en BMC",
    2 => "Notas Crédito Aplicadas",
    0 => "Notas Crédito No Aplicadas"
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novedades</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css">
<!-- DataTables Buttons CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<!-- DataTables Buttons JS -->
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<!-- JSZip (requerido para Excel) -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<!-- PDFMake (requerido para PDF) -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<!-- Excel Export -->
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<!-- PDF Export -->
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Novedades</h2>

        <!-- Dashboard con totales -->
        <div class="row text-center">
            <div class="col-md-4">
                <div class="card shadow-sm border-primary">
                    <div class="card-body">
                        <h4 class="card-title text-primary"><?php echo $marcacion_labels[1]; ?></h4>
                        <h3><?php echo $counts[1] ?? 0; ?></h3>
                        <button class="btn btn-outline-primary ver-detalles" data-marcacion="1">Ver Detalles</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-success">
                    <div class="card-body">
                        <h4 class="card-title text-success"><?php echo $marcacion_labels[2]; ?></h4>
                        <h3><?php echo $counts[2] ?? 0; ?></h3>
                        <button class="btn btn-outline-success ver-detalles" data-marcacion="2">Ver Detalles</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-warning">
                    <div class="card-body">
                        <h4 class="card-title text-warning"><?php echo $marcacion_labels[0]; ?></h4>
                        <h3><?php echo $total_notas_no_aplicadas; ?></h3>
                        <button class="btn btn-outline-warning ver-detalles" data-marcacion="0">Ver Detalles</button>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <!-- Contenedor de resultados dinámicos -->
        <div id="detalle-contenedor"></div>
    </div>

    <?php include("templates/footer.php"); ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
$(document).ready(function() {
    // Función para cargar detalles
    function cargarDetalles(marcacion) {
        $.ajax({
            url: "fetch_novedades.php",
            type: "GET",
            data: { marcacion: marcacion },
            beforeSend: function() {
                $("#detalle-contenedor").html("<p class='text-center'>Cargando...</p>");
            },
            success: function(response) {
                $("#detalle-contenedor").html(response);

                // Definir el título del archivo Excel según la marcación
                let tituloExcel = "Listado de Datos";
                if (marcacion == 1) {
                    tituloExcel = "Registros en BMC";
                } else if (marcacion == 2) {
                    tituloExcel = "Notas Crédito Aplicadas";
                } else if (marcacion == 0) {
                    tituloExcel = "Notas Crédito No Aplicadas";
                }

                // Inicializar DataTables con botones de exportación
                $('#miTabla').DataTable({
                    dom: 'Bfrtip', // Coloca los botones arriba de la tabla
                    buttons: [
                        {
                            extend: 'excel',
                            text: 'Exportar a Excel',
                            className: 'btn btn-success',
                            title: tituloExcel, // Título dinámico del archivo Excel
                            exportOptions: {
                                columns: ':visible' // Exportar solo columnas visibles
                            }
                        },
                        {
                            extend: 'pdf',
                            text: 'Exportar a PDF',
                            className: 'btn btn-danger',
                            title: tituloExcel, // Título dinámico del archivo PDF
                            exportOptions: {
                                columns: ':visible' // Exportar solo columnas visibles
                            }
                        }
                    ],
                    paging: true,
                    searching: true,
                    ordering: true,
                    info: true,
                    responsive: true,
                    pageLength: 10, // Mostrar 10 registros por página
                    lengthMenu: [10, 25, 50, 100] // Opciones de cantidad de registros por página
                });
            },
            error: function() {
                $("#detalle-contenedor").html("<p class='text-danger text-center'>Error al cargar los datos.</p>");
            }
        });
    }

    // Manejar clic en los botones "Ver Detalles"
    $(".ver-detalles").click(function() {
        let estado = $(this).data("marcacion");
        cargarDetalles(estado); // Cargar detalles sin paginación personalizada
    });
});
</script>
</body>
</html>
<?php
include('templates/header.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facturación</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
</head>
<body>
    <div class="container">
        <h2>Listado de Facturas</h2>
        <table id="facturasTable" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Número de Factura</th>
                    <th>Cliente</th>
                    <th>Fecha Factura</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            $('#facturasTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "fetch_data.php",
                "dom": 'Bfrtip',
                "buttons": [
                    {
                        extend: 'copyHtml5',
                        text: 'Copiar',
                        exportOptions: { columns: ':visible' }
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        exportOptions: { columns: ':visible' }
                    },
                    {
                        extend: 'csvHtml5',
                        text: 'CSV',
                        exportOptions: { columns: ':visible' }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        exportOptions: { columns: ':visible' }
                    },
                    {
                        extend: 'print',
                        text: 'Imprimir',
                        exportOptions: { columns: ':visible' }
                    }
                ],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/Spanish.json"
                }
            });
        });
    </script>
</body>
</html>

<?php
include('templates/footer.php');
?>

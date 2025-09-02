

<?php
include('templates/header.php');
include('connection.php');

// Definir paginacion
$items_per_page = 50;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Filtros
$filtro_fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$filtro_fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';
$filtro_numero_factura = isset($_GET['numero_factura']) ? $_GET['numero_factura'] : '';
$filtro_cliente = isset($_GET['cliente']) ? $_GET['cliente'] : '';
$filtro_ciudad = isset($_GET['ciudad']) ? $_GET['ciudad'] : '';
$filtro_producto = isset($_GET['producto']) ? $_GET['producto'] : '';
$filtro_naturaleza = isset($_GET['naturaleza']) ? $_GET['naturaleza'] : '';


try {
    // Consultar todas las facturas con paginacion y filtros
    //$query = "SELECT DISTINCT NoFactura, Comprador, Fecha_Factura, SubTotal FROM facturas_cipa WHERE 1=1";
    $query = "SELECT NoFactura, MAX(Comprador) AS Comprador, MAX(Fecha_Factura) AS Fecha_Factura, SUM(SubTotal) AS TotalSubTotal FROM facturas_cipa WHERE 1=1";
    $params = [];

    // Filtrar por fecha de inicio y fin
    if (!empty($filtro_fecha_inicio) && !empty($filtro_fecha_fin)) {
        $query .= " AND STR_TO_DATE(Fecha_Factura, '%e/%m/%Y') BETWEEN :fecha_inicio AND :fecha_fin";
        $params[':fecha_inicio'] = $filtro_fecha_inicio;
        $params[':fecha_fin'] = $filtro_fecha_fin;
    }

    // Filtro de numero de factura
    if (!empty($filtro_numero_factura)) {
        $query .= " AND NoFactura = :numero_factura";
        $params[':numero_factura'] = $filtro_numero_factura;
    }

    // Filtro de cliente
    if (!empty($filtro_cliente)) {
        $query .= " AND Comprador OR IDComprador LIKE :cliente";
        $params[':cliente'] = '%' . $filtro_cliente . '%';
    }

    // Filtro de ciudad
    if (!empty($filtro_ciudad)) {
        $query .= " AND Ciudad LIKE :ciudad";
        $params[':ciudad'] = '%' . $filtro_ciudad . '%';
    }

    // Filtro de producto
    if (!empty($filtro_producto)) {
        $query .= " AND NoFactura IN (SELECT DISTINCT NoFactura FROM facturas_cipa WHERE Producto LIKE :producto)";
        $params[':producto'] = '%' . $filtro_producto . '%';
    }

    if (!empty($filtro_naturaleza)) {
        if ($filtro_naturaleza == "natural") {
            $query .= " AND NoFactura IN (SELECT DISTINCT NoFactura FROM facturas_cipa WHERE IVA IN (0, 5))";
        } elseif ($filtro_naturaleza == "procesado") {
            $query .= " AND NoFactura IN (SELECT DISTINCT NoFactura FROM facturas_cipa WHERE IVA = 19)";
        }
    }

    $query .= " GROUP BY NoFactura ORDER BY Fecha_Factura DESC LIMIT :limit OFFSET :offset";

    // Consulta para obtener las facturas con paginacion
    //$query .= " ORDER BY Fecha_Factura DESC LIMIT :limit OFFSET :offset";
    $stmt = $conexion->prepare($query);
    $stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }

    $stmt->execute();
    $facturas = $stmt->fetchAll();

    // Obtener total de facturas y subtotales para la paginacion y contadores
    $total_query = "
        SELECT 
            COUNT(DISTINCT NoFactura) AS total_facturas, 
            SUM(SubTotal) AS total_subtotal
        FROM facturas_cipa
        WHERE 1=1
    ";
    $total_params = [];

    // Agregar los mismos filtros
    if (!empty($filtro_fecha_inicio) && !empty($filtro_fecha_fin)) {
        $total_query .= " AND STR_TO_DATE(Fecha_Factura, '%e/%m/%Y') BETWEEN :fecha_inicio AND :fecha_fin";
        $total_params[':fecha_inicio'] = $filtro_fecha_inicio;
        $total_params[':fecha_fin'] = $filtro_fecha_fin;
    }
    if (!empty($filtro_numero_factura)) {
        $total_query .= " AND NoFactura = :numero_factura";
        $total_params[':numero_factura'] = $filtro_numero_factura;
    }

    if (!empty($filtro_cliente)) {
        $total_query .= " AND Comprador OR IDComprador LIKE :cliente";
        $total_params[':cliente'] = '%' . $filtro_cliente . '%';
    }
    if (!empty($filtro_ciudad)) {
        $total_query .= " AND Ciudad LIKE :ciudad";
        $total_params[':ciudad'] = '%' . $filtro_ciudad . '%';
    }
    if (!empty($filtro_producto)) {
        $total_query .= " AND NoFactura IN (SELECT DISTINCT NoFactura FROM facturas_cipa WHERE Producto LIKE :producto)";
        $total_params[':producto'] = '%' . $filtro_producto . '%';
    }

    if (!empty($filtro_naturaleza)) {
        if ($filtro_naturaleza == "natural") {
            $total_query .= " AND NoFactura IN (SELECT DISTINCT NoFactura FROM facturas_cipa WHERE IVA IN (0, 5))";
        } elseif ($filtro_naturaleza == "procesado") {
            $total_query .= " AND NoFactura IN (SELECT DISTINCT NoFactura FROM facturas_cipa WHERE IVA = 19)";
        }
    }

    // Agrupar por NoFactura y obtener el total sin paginacion
    //$total_query .= " GROUP BY NoFactura";
    // Preparar y ejecutar la consulta para los totales
    $total_stmt = $conexion->prepare($total_query);
    foreach ($total_params as $key => $value) {
        $total_stmt->bindValue($key, $value);
    }

    $total_stmt->execute();
    $result = $total_stmt->fetch();
    $total_facturas = (int) $result['total_facturas'];
    $total_subtotal = (float) $result['total_subtotal'];

    // Calculo de paginas para las facturas (usando total de facturas)
    $total_pages = ($total_facturas > 0) ? ceil($total_facturas / $items_per_page) : 1;
} catch (Exception $e) {
    // Captura el error de la consulta
    die("Error en la consulta: " . $e->getMessage());
}



?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facturas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function toggleDetails(id) {
            event.preventDefault();
            const detalles = event.target.closest('tr').nextElementSibling;
            detalles.style.display = detalles.style.display === 'none' ? 'table-row' : 'none';
        }
    </script>
</head>

<body>
    <div class="container mt-4">
        <h2>Lista de Facturas</h2>

        <!-- Filtros -->
        <form method="GET" action="">
            <div class="row mb-3">
                <div class="col-md-2">
                    <label for="fecha_inicio">Fecha Inicio</label>
                    <input type="date" class="form-control" name="fecha_inicio" value="<?= $filtro_fecha_inicio ?>">
                </div>
                <div class="col-md-2">
                    <label for="fecha_fin">Fecha Fin</label>
                    <input type="date" class="form-control" name="fecha_fin" value="<?= $filtro_fecha_fin ?>">
                </div>
                <div class="col-md-2">
                    <label for="numero_factura">Numero Factura</label>
                    <input type="text" class="form-control" name="numero_factura" value="<?= $filtro_numero_factura ?>">
                </div>
                <div class="col-md-2">
                    <label for="cliente">Cliente</label>
                    <input type="text" class="form-control" name="cliente" value="<?= $filtro_cliente ?>">
                </div>
                <div class="col-md-2">
                    <label for="ciudad">Ciudad</label>
                    <input type="text" class="form-control" name="ciudad" value="<?= $filtro_ciudad ?>">
                </div>
                <div class="col-md-2">
                    <label for="producto">Producto</label>
                    <input type="text" class="form-control" name="producto" value="<?= $filtro_producto ?>">
                </div>
                <div class="col-md-2">
                    <label for="naturaleza">Naturaleza</label>
                    <select name="naturaleza" id="naturaleza" class="form-control">
                        <option value="">-- Seleccionar --</option>
                        <option value="natural" <?= ($filtro_naturaleza == "natural") ? "selected" : "" ?>>Natural</option>
                        <option value="procesado" <?= ($filtro_naturaleza == "procesado") ? "selected" : "" ?>>Procesado</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-success">Filtrar</button>
            <!-- Boton para exportar a Excel -->
            <a href="export_excel.php?fecha_inicio=<?= $filtro_fecha_inicio ?>&fecha_fin=<?= $filtro_fecha_fin ?>&numero_factura=<?= $filtro_numero_factura ?>&cliente=<?= $filtro_cliente ?>&ciudad=<?= $filtro_ciudad ?>&producto=<?= $filtro_producto ?>&naturaleza=<?= $filtro_naturaleza ?>" 
   class="btn btn-success">
    <i class="fas fa-file-excel"></i> Exportar a Excel
</a>

        </form>

        <!-- Contador de facturas y total -->
        <!--<div class="card">
            <div class="titulo">
                <center><br><strong>CONTADORES</strong></br></center>
            </div>
            <div class="my-3">
                <div class="tfacturas">
                    <strong>Total de Facturas:</strong> <?= $total_facturas ?>
                </div>
                <div class="tnegocio">
                    <strong>Total de Negociado:</strong> $<?= number_format($total_subtotal, 2) ?>
                </div>
            </div>
        </div>-->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <!-- Total de Facturas -->
                    <div class="col-md-6 mb-3">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <h5 class="card-title text-center">Total de Facturas</h5>
                                <p class="card-text text-center fs-4"><strong><?= $total_facturas ?></strong></p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Negociado -->
                    <div class="col-md-6 mb-3">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <h5 class="card-title text-center">Total de Negociado</h5>
                                <p class="card-text text-center fs-4"><strong>$<?= number_format($total_subtotal, 2) ?></strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <table class="table table-bordered table-responsive" id="facturas-table">
            <thead>
                <tr>
                    <th>No. de Factura</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Accion</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($facturas as $factura) : ?>
                    <tr>
                        <td><a href="#" onclick="toggleDetails(<?= intval($factura['NoFactura']) ?>)"><?= htmlspecialchars($factura['NoFactura']) ?></a></td>
                        <td><?= htmlspecialchars($factura['Comprador']) ?></td>
                        <td><?= $factura['Fecha_Factura'] ?></td>
                        <!--<td>$<?= number_format((float)$factura['TotalSubTotal'], 1) ?></td>-->
                        <td>$<?= is_numeric($factura['TotalSubTotal']) ? number_format((float)$factura['TotalSubTotal'], 1) : '0.00' ?></td>
                        <td><button class="btn btn-success" onclick="toggleDetails(<?= intval($factura['NoFactura']) ?>)">Ver Detalles</button></td>
                    </tr>
                    <tr id="detalle-<?= intval($factura['NoFactura']) ?>" style="display: none;">
                        <td colspan="5">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio Unitario</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    try {
                                        $query_items = "SELECT Producto, Cantidad, Precio FROM facturas_cipa WHERE NoFactura = :no_factura";
                                        $stmt_items = $conexion->prepare($query_items);
                                        $stmt_items->execute([':no_factura' => $factura['NoFactura']]);
                                        $items = $stmt_items->fetchAll();
                                        foreach ($items as $item) : ?>
                                            <tr>
                                                <td><?= htmlspecialchars($item['Producto']) ?></td>
                                                <td><?= $item['Cantidad'] ?></td>
                                                <td>$<?= number_format((float) $item['Precio'], 2) ?></td>
                                                <td>$<?= number_format((float) $item['Cantidad'] * (float) $item['Precio'], 2) ?></td>
                                            </tr>
                                    <?php endforeach;
                                    } catch (Exception $e) {
                                        echo "<tr><td colspan='4'>Error al obtener detalles</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Paginacion -->
        <nav>
            <ul class="pagination">
                <!-- Mostrar paginas 1, 2, 3 -->
                <?php for ($i = 1; $i <= min(3, $total_pages); $i++) : ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&fecha_inicio=<?= $filtro_fecha_inicio ?>&fecha_fin=<?= $filtro_fecha_fin ?>&numero_factura=<?= $filtro_numero_factura ?>&cliente=<?= $filtro_cliente ?>&ciudad=<?= $filtro_ciudad ?>&producto=<?= $filtro_producto ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <!-- Si hay mas de 3 paginas, mostrar el boton "Next" -->
                <?php if ($total_pages > 3) : ?>
                    <?php if ($page < $total_pages) : ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page + 1 ?>&fecha_inicio=<?= $filtro_fecha_inicio ?>&fecha_fin=<?= $filtro_fecha_fin ?>&numero_factura=<?= $filtro_numero_factura ?>&cliente=<?= $filtro_cliente ?>&ciudad=<?= $filtro_ciudad ?>&producto=<?= $filtro_producto ?>">Next</a>
                        </li>
                    <?php endif; ?>

                    <!-- Mostrar el numero final de la pagina si hay mas de 3 -->
                    <?php if ($page < $total_pages) : ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $total_pages ?>&fecha_inicio=<?= $filtro_fecha_inicio ?>&fecha_fin=<?= $filtro_fecha_fin ?>&numero_factura=<?= $filtro_numero_factura ?>&cliente=<?= $filtro_cliente ?>&ciudad=<?= $filtro_ciudad ?>&producto=<?= $filtro_producto ?>">Last</a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

    <script>
        // Funcion para exportar la tabla a Excel
        function exportToExcel() {
            // Array para almacenar todos los datos para la exportación
            let exportData = [];

            // Encabezados de la tabla
            const encabezado = ["No. de Factura", "Cliente", "Fecha", "Producto", "Cantidad", "Precio Unitario", "Total", "IVA", "Ciudad"];
            exportData.push(encabezado);

            // Recorremos las facturas (estos datos ya los tienes en el PHP)
            <?php foreach ($facturas as $factura): ?>
                <?php
                // Obtener todos los productos de la factura
                $query_items = "SELECT Producto, Cantidad, Precio, IVA, Ciudad FROM facturas_cipa WHERE NoFactura = :no_factura";
                $stmt_items = $conexion->prepare($query_items);
                $stmt_items->execute([':no_factura' => $factura['NoFactura']]);
                $items = $stmt_items->fetchAll();
                ?>
                <?php foreach ($items as $item): ?>
                    // Agregar fila por cada producto
                    exportData.push([
                        "<?= $factura['NoFactura'] ?>",
                        "<?= $factura['Comprador'] ?>",
                        "<?= $factura['Fecha_Factura'] ?>",
                        "<?= htmlspecialchars($item['Producto']) ?>",
                        "<?= $item['Cantidad'] ?>",
                        "<?= number_format((float) $item['Precio'], 2) ?>",
                        "<?= number_format((float) $item['Cantidad'] * (float) $item['Precio'], 2) ?>",
                        "<?= $item['IVA'] ?>",
                        "<?= htmlspecialchars($factura['Ciudad']) ?>"
                    ]);
                <?php endforeach; ?>
            <?php endforeach; ?>

            // Crear un nuevo libro de trabajo
            const wb = XLSX.utils.book_new();

            // Crear una hoja y agregarla al libro
            const ws = XLSX.utils.aoa_to_sheet(exportData); // Convertimos los datos en formato AOA (Array of Arrays)

            // Agregar la hoja al libro
            XLSX.utils.book_append_sheet(wb, ws, "Facturas");

            // Generar y descargar el archivo Excel
            XLSX.writeFile(wb, "facturas.xlsx");
        }
    </script>
</body>

</html>
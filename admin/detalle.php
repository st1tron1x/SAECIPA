<?php
include('templates/header.php');
include('connection.php');

// Obtener número de factura desde la URL
$factura_id = $_GET['factura'] ?? '';
if (empty($factura_id)) {
    die("Factura no especificada.");
}

// Consultar detalles de la factura
$query = "SELECT * FROM facturas WHERE NoFactura = ?";
$stmt = $conexion->prepare($query);
$stmt->execute([$factura_id]);
$factura = $stmt->fetch();

if (!$factura) {
    die("Factura no encontrada.");
}

// Consultar productos asociados a la factura
$query_items = "SELECT Producto, Cantidad, Precio FROM facturas WHERE NoFactura = ?";
$stmt_items = $conexion->prepare($query_items);
$stmt_items->execute([$factura_id]);
$items = $stmt_items->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Factura</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Detalle de Factura #<?= htmlspecialchars($factura['NoFactura']) ?></h2>
        <p><strong>Cliente:</strong> <?= htmlspecialchars($factura['Comprador']) ?></p>
        <p><strong>Fecha:</strong> <?= $factura['Fecha_Factura'] ?></p>
        <p><strong>Total:</strong> $<?= number_format($factura['SubTotal'], 2) ?></p>
        
        <h3>Productos</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item) : ?>
                    <tr>
                        <td><?= htmlspecialchars($item['Producto']) ?></td>
                        <td><?= $item['Cantidad'] ?></td>
                        <td>$<?= number_format($item['Precio'], 2) ?></td>
                        <td>$<?= number_format($item['Cantidad'] * $item['Precio'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <a href="home.php" class="btn btn-primary">Volver</a>
    </div>
</body>
</html>
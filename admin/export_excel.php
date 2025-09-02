<?php
include('connection.php');

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=facturas_exportadas.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Filtros recibidos desde la URL
$filtro_fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$filtro_fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';
$filtro_numero_factura = isset($_GET['numero_factura']) ? $_GET['numero_factura'] : '';
$filtro_cliente = isset($_GET['cliente']) ? $_GET['cliente'] : '';
$filtro_ciudad = isset($_GET['ciudad']) ? $_GET['ciudad'] : '';
$filtro_producto = isset($_GET['producto']) ? $_GET['producto'] : '';
$filtro_naturaleza = isset($_GET['naturaleza']) ? $_GET['naturaleza'] : '';

$query = "SELECT NoFactura, MAX(Comprador) AS Comprador, MAX(Fecha_Factura) AS Fecha_Factura, SUM(SubTotal) AS TotalSubTotal FROM facturas_cipa WHERE 1=1";
$params = [];

// Aplicar filtros
if (!empty($filtro_fecha_inicio) && !empty($filtro_fecha_fin)) {
    $query .= " AND STR_TO_DATE(Fecha_Factura, '%e/%m/%Y') BETWEEN :fecha_inicio AND :fecha_fin";
    $params[':fecha_inicio'] = $filtro_fecha_inicio;
    $params[':fecha_fin'] = $filtro_fecha_fin;
}
if (!empty($filtro_numero_factura)) {
    $query .= " AND NoFactura = :numero_factura";
    $params[':numero_factura'] = $filtro_numero_factura;
}
if (!empty($filtro_cliente)) {
    $query .= " AND (Comprador LIKE :cliente OR IDComprador LIKE :cliente)";
    $params[':cliente'] = '%' . $filtro_cliente . '%';
}
if (!empty($filtro_ciudad)) {
    $query .= " AND Ciudad LIKE :ciudad";
    $params[':ciudad'] = '%' . $filtro_ciudad . '%';
}
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

$query .= " GROUP BY NoFactura ORDER BY Fecha_Factura DESC";

$stmt = $conexion->prepare($query);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->execute();
$facturas = $stmt->fetchAll();

// Generar tabla de Excel
echo "<table border='1'>";
echo "<tr><th>No Factura</th><th>Comprador</th><th>Fecha Factura</th><th>Total SubTotal</th></tr>";
foreach ($facturas as $factura) {
    echo "<tr>
        <td>{$factura['NoFactura']}</td>
        <td>{$factura['Comprador']}</td>
        <td>{$factura['Fecha_Factura']}</td>
        <td>{$factura['TotalSubTotal']}</td>
    </tr>";
}
echo "</table>";
?>

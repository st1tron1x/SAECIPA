<?php

include('connection.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Obtener los filtros desde la URL
$filtro_fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$filtro_fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';
$filtro_numero_factura = isset($_GET['numero_factura']) ? $_GET['numero_factura'] : '';
$filtro_cliente = isset($_GET['cliente']) ? $_GET['cliente'] : '';
$filtro_ciudad = isset($_GET['ciudad']) ? $_GET['ciudad'] : '';
$filtro_producto = isset($_GET['producto']) ? $_GET['producto'] : '';
$filtro_naturaleza = isset($_GET['naturaleza']) ? $_GET['naturaleza'] : '';

try {
    // Consulta para obtener todos los datos filtrados (sin paginación)
    $export_query = "SELECT NoFactura, MAX(Comprador) AS Comprador, MAX(Fecha_Factura) AS Fecha_Factura, SUM(SubTotal) AS TotalSubTotal FROM facturas_cipa WHERE 1=1";
    $export_params = [];

    // Aplicar los mismos filtros que en home.php
    if (!empty($filtro_fecha_inicio) && !empty($filtro_fecha_fin)) {
        $export_query .= " AND STR_TO_DATE(Fecha_Factura, '%e/%m/%Y') BETWEEN :fecha_inicio AND :fecha_fin";
        $export_params[':fecha_inicio'] = $filtro_fecha_inicio;
        $export_params[':fecha_fin'] = $filtro_fecha_fin;
    }
    if (!empty($filtro_numero_factura)) {
        $export_query .= " AND NoFactura = :numero_factura";
        $export_params[':numero_factura'] = $filtro_numero_factura;
    }
    if (!empty($filtro_cliente)) {
        $export_query .= " AND (Comprador LIKE :cliente OR IDComprador LIKE :cliente)";
        $export_params[':cliente'] = '%' . $filtro_cliente . '%';
    }
    if (!empty($filtro_ciudad)) {
        $export_query .= " AND Ciudad LIKE :ciudad";
        $export_params[':ciudad'] = '%' . $filtro_ciudad . '%';
    }
    if (!empty($filtro_producto)) {
        $export_query .= " AND NoFactura IN (SELECT DISTINCT NoFactura FROM facturas_cipa WHERE Producto LIKE :producto)";
        $export_params[':producto'] = '%' . $filtro_producto . '%';
    }
    if (!empty($filtro_naturaleza)) {
        if ($filtro_naturaleza == "natural") {
            $export_query .= " AND NoFactura IN (SELECT DISTINCT NoFactura FROM facturas_cipa WHERE IVA IN (0, 5))";
        } elseif ($filtro_naturaleza == "procesado") {
            $export_query .= " AND NoFactura IN (SELECT DISTINCT NoFactura FROM facturas_cipa WHERE IVA = 19))";
        }
    }

    $export_query .= " GROUP BY NoFactura ORDER BY Fecha_Factura DESC";

    // Ejecutar la consulta
    $export_stmt = $conexion->prepare($export_query);
    foreach ($export_params as $key => $value) {
        $export_stmt->bindValue($key, $value);
    }
    $export_stmt->execute();
    $facturas_export = $export_stmt->fetchAll();

    // Generar el archivo Excel
    require 'vendor/autoload.php'; // Si usas PhpSpreadsheet
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Encabezados
    $sheet->setCellValue('A1', 'No. de Factura');
    $sheet->setCellValue('B1', 'Cliente');
    $sheet->setCellValue('C1', 'Fecha');
    $sheet->setCellValue('D1', 'Total');

    // Llenar datos
    $row = 2;
    foreach ($facturas_export as $factura) {
        $sheet->setCellValue('A' . $row, $factura['NoFactura']);
        $sheet->setCellValue('B' . $row, $factura['Comprador']);
        $sheet->setCellValue('C' . $row, $factura['Fecha_Factura']);
        $sheet->setCellValue('D' . $row, $factura['TotalSubTotal']);
        $row++;
    }

    // Descargar el archivo
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="facturas.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
} catch (Exception $e) {
    die("Error en la exportación: " . $e->getMessage());
}
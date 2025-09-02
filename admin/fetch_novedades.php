<?php
include('connection.php');

// Verificar si se recibió el parámetro "marcacion"
if (isset($_GET['marcacion'])) {
    $marcacion = intval($_GET['marcacion']);

    try {
        if ($marcacion == 0) {
            // Consulta para Notas Crédito No Aplicadas (tabla notas)
            $sql = "SELECT consec, IDFactura, ID, Producto, Cantidad, Precio, IDVendedor, IDComprador 
                    FROM notas 
                    WHERE marcacion IS NULL OR marcacion = 0";
            $stmt = $conexion->prepare($sql);
        } else {
            // Consulta para facturas_cipa (marcacion 1 o 2)
            $sql = "SELECT ID, NoFactura, DescuentoNC, Observacion, created_at 
                    FROM facturas_cipa 
                    WHERE Marcacion = :marcacion";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':marcacion', $marcacion, PDO::PARAM_INT);
        }

        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($resultados) > 0) {
            // Mostrar los resultados en una tabla responsive
            echo "<div class='table-responsive'>";
            echo "<table class='table table-bordered table-striped' id='miTabla'>";
            if ($marcacion == 0) {
                echo "<thead class='thead-dark'><tr>
                        <th>Consecutivo</th>
                        <th>ID Factura</th>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Vendedor</th>
                        <th>Comprador</th>
                      </tr></thead>";
            } else {
                echo "<thead class='thead-dark'><tr>
                        <th>ID</th>
                        <th>No. Factura</th>
                        <th>Descuento NC</th>
                        <th>Observación</th>
                        <th>Fecha de Creación</th>
                      </tr></thead>";
            }
            echo "<tbody>";
            foreach ($resultados as $fila) {
                echo "<tr>";
                if ($marcacion == 0) {
                    echo "<td>" . htmlspecialchars($fila['consec']) . "</td>";
                    echo "<td>" . htmlspecialchars($fila['IDFactura']) . "</td>";
                    echo "<td>" . htmlspecialchars($fila['ID']) . "</td>";
                    echo "<td>" . htmlspecialchars($fila['Producto']) . "</td>";
                    echo "<td>" . htmlspecialchars($fila['Cantidad']) . "</td>";
                    echo "<td>" . htmlspecialchars($fila['Precio']) . "</td>";
                    echo "<td>" . htmlspecialchars($fila['IDVendedor']) . "</td>";
                    echo "<td>" . htmlspecialchars($fila['IDComprador']) . "</td>";
                } else {
                    echo "<td>" . htmlspecialchars($fila['ID']) . "</td>";
                    echo "<td>" . htmlspecialchars($fila['NoFactura']) . "</td>";
                    echo "<td>" . htmlspecialchars($fila['DescuentoNC']) . "</td>";
                    echo "<td>" . htmlspecialchars($fila['Observacion']) . "</td>";
                    echo "<td>" . htmlspecialchars($fila['created_at']) . "</td>";
                }
                echo "</tr>";
            }
            echo "</tbody></table>";
            echo "</div>";
        } else {
            echo "<p class='text-center'>No hay registros para esta marcación.</p>";
        }
    } catch (Exception $e) {
        error_log("Error en la consulta de detalles: " . $e->getMessage());
        echo "<p class='text-danger text-center'>Error al cargar los detalles.</p>";
    }
} else {
    echo "<p class='text-danger text-center'>Parámetro no válido.</p>";
}
?>
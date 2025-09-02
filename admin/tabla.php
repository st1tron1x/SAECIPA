<?php
function mostrarTablaFacturas($facturas) {
    if (isset($facturas['error'])) {
        echo "<tr><td colspan='17'>Error: " . htmlspecialchars($facturas['error']) . "</td></tr>";
        return;
    }

    foreach ($facturas as $row) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['IDVendedor']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Vendedor']) . "</td>";
        echo "<td>" . htmlspecialchars($row['IDComprador']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Comprador']) . "</td>";
        echo "<td>" . htmlspecialchars($row['fecha_factura']) . "</td>";
        echo "<td>" . htmlspecialchars($row['fecha_fin']) . "</td>";
        echo "<td><a href='#' class='toggle-detalles'>" . htmlspecialchars($row['NoFactura']) . "</a></td>";
        echo "<td>" . htmlspecialchars($row['Producto']) . "</td>";
        echo "<td>$ " . number_format($row['Precio'], 2, ',', '.') . "</td>";
        echo "<td>" . htmlspecialchars($row['Cantidad']) . "</td>";
        echo "<td>" . htmlspecialchars($row['IVA']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Ciudad']) . "</td>";
        echo "<td>$ " . number_format($row['DescuentoNC'], 2, ',', '.') . "</td>";
        echo "<td>$ " . number_format($row['PrecioAnterior'], 2, ',', '.') . "</td>";
        echo "<td><form action='guardar_comprobante.php' method='post' enctype='multipart/form-data'>
                  <input type='file' name='comprobante_" . htmlspecialchars($row['id']) . "'>
                  <input type='hidden' name='id_factura' value='" . htmlspecialchars($row['id']) . "'>
                  <input type='submit' value='Adjuntar' class='btn btn-sm btn-secondary'>
              </form></td>";
        echo "<td>" . htmlspecialchars($row['IVA']) . "</td>";
        echo "</tr>";

        // Fila oculta con detalles
        echo "<tr class='detalles-factura' style='display:none;'>";
        echo "<td colspan='17'><strong>Detalles de la factura:</strong><br>";
        echo "ID Factura: " . htmlspecialchars($row['id']) . "<br>";
        echo "Vendedor: " . htmlspecialchars($row['Vendedor']) . "<br>";
        echo "Comprador: " . htmlspecialchars($row['Comprador']) . "<br>";
        echo "Producto: " . htmlspecialchars($row['Producto']) . "<br>";
        echo "Precio Unitario: $" . number_format($row['Precio'], 2, ',', '.') . "<br>";
        echo "Cantidad: " . htmlspecialchars($row['Cantidad']) . "<br>";
        echo "Subtotal: $" . number_format($row['DescuentoNC'], 2, ',', '.') . "<br>";
        echo "Total Factura: $" . number_format($row['PrecioAnterior'], 2, ',', '.') . "<br>";
        echo "</td></tr>";
    }
}
?>
<script>
    // Función para aplicar filtros

    function aplicarFiltros() {
        const fechaInicio = document.getElementById('fecha-inicio').value;
        const fechaFin = document.getElementById('fecha-fin').value;
        const cliente = document.getElementById('cliente-filter').value.toLowerCase();
        const producto = document.getElementById('producto-filter').value.toLowerCase();
        const ciudad = document.getElementById('ciudad-filter').value.toLowerCase();
        <?php
        include('connection.php');
        $fechaInicio = $_POST['fecha_inicio'] ?? null;
        $fechaFin = $_POST['fecha_fin'] ?? null;
        $cliente = $_POST['cliente'] ?? '';
        $producto = $_POST['producto'] ?? '';
        $ciudad = $_POST['ciudad'] ?? '';

        // Construir la consulta SQL dinámicamente con filtros
        $sql = "SELECT id, IDVendedor, Vendedor, IDComprador, Comprador, fecha_factura, fecha_fin, NoFactura, Producto, Precio, Cantidad, IVA, Ciudad, DescuentoNC, PrecioAnterior, IVA 
        FROM facturas_cipa WHERE 1=1 ";

        if ($fechaInicio) {
            $sql .= " AND fecha_factura >= :fecha_inicio";
        }
        if ($fechaFin) {
            $sql .= " AND fecha_factura <= :fecha_fin";
        }
        if ($cliente) {
            $sql .= " AND LOWER(Comprador) LIKE :cliente";
        }
        if ($producto) {
            $sql .= " AND LOWER(Producto) LIKE :producto";
        }
        if ($ciudad) {
            $sql .= " AND LOWER(Ciudad) LIKE :ciudad";
        }

        // Limitar los resultados (ajustar según sea necesario)
        $sql .= " LIMIT 10";

        $stmt = $conexion->prepare($sql);

        // Enlazar los parámetros de forma segura
        if ($fechaInicio) {
            $stmt->bindParam(':fecha_inicio', $fechaInicio);
        }
        if ($fechaFin) {
            $stmt->bindParam(':fecha_fin', $fechaFin);
        }
        if ($cliente) {
            $stmt->bindParam(':cliente', '%' . $cliente . '%');
        }
        if ($producto) {
            $stmt->bindParam(':producto', '%' . $producto . '%');
        }
        if ($ciudad) {
            $stmt->bindParam(':ciudad', '%' . $ciudad . '%');
        }

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener los resultados
        $facturas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Llamar a la función para mostrar la tabla con los resultados
        mostrarTablaFacturas($facturas);
        ?>

        // const filas = document.querySelectorAll('#facturas-table tbody tr');
        // let totalFacturas = 0;
        // let totalValor = 0;

        // filas.forEach(fila => {
        //     const fechaFactura = fila.cells[5].textContent; // Fecha Factura
        //     const nomComprador = fila.cells[4].textContent.toLowerCase(); // Nombre del Comprador
        //     const nomProducto = fila.cells[8].textContent.toLowerCase(); // Producto
        //     const ciudadComprador = fila.cells[12].textContent.toLowerCase(); // Ciudad
        //     const totalFactura = parseFloat(fila.cells[14].textContent.replace(/[^0-9.-]+/g, "")) || 0; // Total Factura (limpia el formato COP)

        //     // Convertir fechas a objetos Date para comparación
        //     const fechaFacturaDate = new Date(fechaFactura);
        //     const fechaInicioDate = fechaInicio ? new Date(fechaInicio) : null;
        //     const fechaFinDate = fechaFin ? new Date(fechaFin) : null;

        //     // Aplicar filtros
        //     if (
        //         (!fechaInicioDate || fechaFacturaDate >= fechaInicioDate) &&
        //         (!fechaFinDate || fechaFacturaDate <= fechaFinDate) &&
        //         (!cliente || nomComprador.includes(cliente)) &&
        //         (!producto || nomProducto.includes(producto)) &&
        //         (!ciudad || ciudadComprador.includes(ciudad))
        //     ) {
        //         fila.style.display = '';
        //         totalFacturas++;
        //         totalValor += totalFactura;
        //     } else {
        //         fila.style.display = 'none';
        //     }
        // });

        // // Mostrar totales
        // document.getElementById('total-facturas-count').textContent = totalFacturas;
        // document.getElementById('total-valor-amount').textContent = totalValor.toLocaleString('es-CO', {
        //     style: 'currency',
        //     currency: 'COP',
        //     minimumFractionDigits: 2
        // });

    }
</script>
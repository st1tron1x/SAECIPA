<?php
function obtenerFacturas($conexion, $limite = 50) {
    try {
        $consulta = "SELECT id, IDVendedor, Vendedor, IDComprador, Comprador, fecha_factura, fecha_fin, NoFactura, Producto, Precio, Cantidad, IVA, Ciudad, DescuentoNC, PrecioAnterior
                     FROM facturas_cipa LIMIT :limite";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return ["error" => $e->getMessage()];
    }
}
?>
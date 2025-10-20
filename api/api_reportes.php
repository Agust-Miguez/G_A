<?php
// api/api_reportes.php

require_once 'db_conexion.php';
header('Content-Type: application/json');

// --- Flujo de la API para Reportes ---
// Proporciona datos consolidados para análisis.

if (isset($_GET['tipo'])) {
    $tipo = $_GET['tipo'];

    switch ($tipo) {
        // --- Reporte: Pagos en un rango de fechas ---
        case 'pagos_fecha':
            if (isset($_GET['inicio']) && isset($_GET['fin'])) {
                $fecha_inicio = $_GET['inicio'];
                $fecha_fin = $_GET['fin'];

                $sql = "SELECT p.id, u.nombre, u.apellido, p.monto, p.fecha_pago
                        FROM Pagos p
                        JOIN Clientes c ON p.cliente_id = c.id
                        JOIN Usuarios u ON c.usuario_id = u.id
                        WHERE p.fecha_pago BETWEEN ? AND ?
                        ORDER BY p.fecha_pago DESC";

                $stmt = $conexion->prepare($sql);
                $stmt->bind_param("ss", $fecha_inicio, $fecha_fin);
                $stmt->execute();
                $resultado = $stmt->get_result();

                $pagos = [];
                while ($fila = $resultado->fetch_assoc()) {
                    $pagos[] = $fila;
                }

                http_response_code(200);
                echo json_encode(['status' => 'success', 'reporte' => 'pagos_por_fecha', 'datos' => $pagos]);

                $stmt->close();
            } else {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Se requieren fechas de inicio y fin.']);
            }
            break;

        // --- Reporte: Ventas de productos consolidadas ---
        case 'ventas_producto':
            $sql = "SELECT pr.nombre, SUM(v.cantidad) as total_vendido, SUM(v.precio_total) as ingresos_totales
                    FROM Ventas v
                    JOIN Productos pr ON v.producto_id = pr.id
                    GROUP BY pr.nombre
                    ORDER BY ingresos_totales DESC";

            $resultado = $conexion->query($sql);

            $ventas = [];
            while ($fila = $resultado->fetch_assoc()) {
                $ventas[] = $fila;
            }

            http_response_code(200);
            echo json_encode(['status' => 'success', 'reporte' => 'ventas_por_producto', 'datos' => $ventas]);
            break;

        default:
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Tipo de reporte no válido.']);
            break;
    }
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Se requiere un tipo de reporte.']);
}

$conexion->close();
?>

<?php
// api/api_admin.php

require_once 'db_conexion.php';
header('Content-Type: application/json');

// --- Flujo de la API para Administradores ---
// Se encarga de la gestión de pagos, ventas, productos y usuarios.

if (isset($_GET['accion'])) {
    $accion = $_GET['accion'];

    switch ($accion) {
        // --- Caso: Registrar un nuevo pago de membresía ---
        case 'registrar_pago':
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $data = json_decode(file_get_contents('php://input'));
                if (!empty($data->cliente_id) && !empty($data->monto) && !empty($data->fecha_pago)) {
                    $sql = "INSERT INTO Pagos (cliente_id, monto, fecha_pago, metodo_pago) VALUES (?, ?, ?, ?)";
                    $stmt = $conexion->prepare($sql);
                    $metodo = $data->metodo_pago ?? 'Efectivo'; // Valor por defecto
                    $stmt->bind_param("idss", $data->cliente_id, $data->monto, $data->fecha_pago, $metodo);

                    if ($stmt->execute()) {
                        http_response_code(201);
                        echo json_encode(['status' => 'success', 'message' => 'Pago registrado correctamente.']);
                    } else {
                        http_response_code(500);
                        echo json_encode(['status' => 'error', 'message' => 'Error al registrar el pago.']);
                    }
                    $stmt->close();
                } else {
                    http_response_code(400);
                    echo json_encode(['status' => 'error', 'message' => 'Datos incompletos para el pago.']);
                }
            } else {
                http_response_code(405);
            }
            break;

        // --- Caso: Registrar una nueva venta de producto ---
        case 'registrar_venta':
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $data = json_decode(file_get_contents('php://input'));
                if (!empty($data->cliente_id) && !empty($data->producto_id) && !empty($data->cantidad)) {

                    // --- Transacción para asegurar la consistencia de datos ---
                    $conexion->begin_transaction();

                    try {
                        // 1. Obtener el precio del producto y verificar stock
                        $sql_producto = "SELECT precio, stock FROM Productos WHERE id = ?";
                        $stmt_producto = $conexion->prepare($sql_producto);
                        $stmt_producto->bind_param("i", $data->producto_id);
                        $stmt_producto->execute();
                        $res_producto = $stmt_producto->get_result()->fetch_assoc();

                        if (!$res_producto || $res_producto['stock'] < $data->cantidad) {
                            throw new Exception('Producto no encontrado o stock insuficiente.');
                        }

                        // 2. Insertar la venta
                        $precio_total = $res_producto['precio'] * $data->cantidad;
                        $sql_venta = "INSERT INTO Ventas (cliente_id, producto_id, cantidad, precio_total) VALUES (?, ?, ?, ?)";
                        $stmt_venta = $conexion->prepare($sql_venta);
                        $stmt_venta->bind_param("iiid", $data->cliente_id, $data->producto_id, $data->cantidad, $precio_total);
                        $stmt_venta->execute();

                        // 3. Actualizar el stock del producto
                        $nuevo_stock = $res_producto['stock'] - $data->cantidad;
                        $sql_stock = "UPDATE Productos SET stock = ? WHERE id = ?";
                        $stmt_stock = $conexion->prepare($sql_stock);
                        $stmt_stock->bind_param("ii", $nuevo_stock, $data->producto_id);
                        $stmt_stock->execute();

                        // Si todo fue bien, confirmar la transacción
                        $conexion->commit();

                        http_response_code(201);
                        echo json_encode(['status' => 'success', 'message' => 'Venta registrada correctamente.']);

                    } catch (Exception $e) {
                        // Si algo falla, revertir la transacción
                        $conexion->rollback();
                        http_response_code(400);
                        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                    }
                } else {
                    http_response_code(400);
                }
            } else {
                http_response_code(405);
            }
            break;

        // --- Otros casos CRUD para productos o usuarios podrían ir aquí ---

        default:
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Acción no válida.']);
            break;
    }
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Se requiere una acción.']);
}

$conexion->close();
?>

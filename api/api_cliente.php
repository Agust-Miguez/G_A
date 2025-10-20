<?php
// api/api_cliente.php

require_once 'db_conexion.php';
header('Content-Type: application/json');

// --- Flujo de la API para Clientes ---
// Este script maneja las peticiones específicas del rol de cliente.
// Se utiliza un parámetro 'accion' en la URL (GET) para determinar
// la operación a realizar (ej: ?accion=get_rutinas).

// --- Verificación de la Acción ---
if (isset($_GET['accion'])) {
    $accion = $_GET['accion'];

    switch ($accion) {
        // --- Caso: Obtener Rutinas del Cliente ---
        case 'get_rutinas':
            // Se necesita el ID del cliente para obtener sus rutinas.
            if (isset($_GET['cliente_id'])) {
                $cliente_id = $_GET['cliente_id'];

                // Consulta para obtener las rutinas y los datos del profesor asociado.
                $sql = "SELECT r.id, r.nombre, r.descripcion, u.nombre as profesor
                        FROM Rutinas r
                        JOIN Usuarios u ON r.profesor_id = u.id
                        WHERE r.cliente_id = ?";

                $stmt = $conexion->prepare($sql);
                $stmt->bind_param("i", $cliente_id);
                $stmt->execute();
                $resultado = $stmt->get_result();

                $rutinas = [];
                while ($fila = $resultado->fetch_assoc()) {
                    $rutinas[] = $fila;
                }

                http_response_code(200);
                echo json_encode(['status' => 'success', 'rutinas' => $rutinas]);

                $stmt->close();
            } else {
                http_response_code(400); // Bad Request
                echo json_encode(['status' => 'error', 'message' => 'El ID del cliente es requerido.']);
            }
            break;

        // --- Caso: Registrar Progreso de un Ejercicio ---
        case 'post_progreso':
            // Se utiliza el método POST para enviar los datos del progreso.
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $data = json_decode(file_get_contents('php://input'));

                // Validación de datos de entrada.
                if (!empty($data->rutina_ejercicio_id) && !empty($data->cliente_id) && !empty($data->fecha)) {

                    $sql = "INSERT INTO Progreso_Ejercicios
                            (rutina_ejercicio_id, cliente_id, fecha, peso_levantado, repeticiones_hechas, comentarios)
                            VALUES (?, ?, ?, ?, ?, ?)";

                    $stmt = $conexion->prepare($sql);

                    // Se usan valores por defecto (null) si no se proporcionan.
                    $peso = $data->peso_levantado ?? null;
                    $reps = $data->repeticiones_hechas ?? null;
                    $comentarios = $data->comentarios ?? null;

                    $stmt->bind_param("iisiss",
                        $data->rutina_ejercicio_id,
                        $data->cliente_id,
                        $data->fecha,
                        $peso,
                        $reps,
                        $comentarios
                    );

                    if ($stmt->execute()) {
                        http_response_code(201); // Created
                        echo json_encode(['status' => 'success', 'message' => 'Progreso registrado correctamente.']);
                    } else {
                        http_response_code(500); // Internal Server Error
                        echo json_encode(['status' => 'error', 'message' => 'Error al registrar el progreso.']);
                    }

                    $stmt->close();
                } else {
                    http_response_code(400); // Bad Request
                    echo json_encode(['status' => 'error', 'message' => 'Datos incompletos para registrar el progreso.']);
                }
            } else {
                http_response_code(405); // Method Not Allowed
                echo json_encode(['status' => 'error', 'message' => 'Use el método POST para registrar progreso.']);
            }
            break;

        // --- Acción Desconocida ---
        default:
            http_response_code(400); // Bad Request
            echo json_encode(['status' => 'error', 'message' => 'Acción no válida.']);
            break;
    }
} else {
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'Se requiere una acción.']);
}

$conexion->close();
?>

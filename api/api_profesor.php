<?php
// api/api_profesor.php

require_once 'db_conexion.php';
header('Content-Type: application/json');

// --- Flujo de la API para Profesores ---
// Gestiona las operaciones CRUD para rutinas y ejercicios.

if (isset($_GET['accion'])) {
    $accion = $_GET['accion'];

    switch ($accion) {
        // --- Caso: Crear una nueva rutina ---
        case 'crear_rutina':
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $data = json_decode(file_get_contents('php://input'));
                if (!empty($data->nombre) && !empty($data->profesor_id) && !empty($data->cliente_id)) {
                    $sql = "INSERT INTO Rutinas (nombre, descripcion, profesor_id, cliente_id) VALUES (?, ?, ?, ?)";
                    $stmt = $conexion->prepare($sql);
                    $descripcion = $data->descripcion ?? null;
                    $stmt->bind_param("ssii", $data->nombre, $descripcion, $data->profesor_id, $data->cliente_id);
                    if ($stmt->execute()) {
                        http_response_code(201);
                        echo json_encode(['status' => 'success', 'message' => 'Rutina creada.', 'rutina_id' => $conexion->insert_id]);
                    } else {
                        http_response_code(500);
                        echo json_encode(['status' => 'error', 'message' => 'Error al crear la rutina.']);
                    }
                    $stmt->close();
                } else {
                    http_response_code(400);
                    echo json_encode(['status' => 'error', 'message' => 'Datos incompletos.']);
                }
            } else {
                http_response_code(405);
            }
            break;

        // --- Caso: Obtener todos los ejercicios del banco ---
        case 'get_banco_ejercicios':
            $sql = "SELECT id, nombre, descripcion, grupo_muscular FROM Banco_Ejercicios";
            $resultado = $conexion->query($sql);
            $ejercicios = [];
            while ($fila = $resultado->fetch_assoc()) {
                $ejercicios[] = $fila;
            }
            http_response_code(200);
            echo json_encode(['status' => 'success', 'ejercicios' => $ejercicios]);
            break;

        // --- Caso: Asignar un ejercicio a una rutina ---
        case 'asignar_ejercicio_rutina':
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $data = json_decode(file_get_contents('php://input'));
                if (!empty($data->rutina_id) && !empty($data->ejercicio_id)) {
                    $sql = "INSERT INTO Rutinas_Ejercicios (rutina_id, ejercicio_id, series, repeticiones, peso) VALUES (?, ?, ?, ?, ?)";
                    $stmt = $conexion->prepare($sql);
                    $series = $data->series ?? null;
                    $repeticiones = $data->repeticiones ?? null;
                    $peso = $data->peso ?? null;
                    $stmt->bind_param("iiiii", $data->rutina_id, $data->ejercicio_id, $series, $repeticiones, $peso);
                    if ($stmt->execute()) {
                        http_response_code(201);
                        echo json_encode(['status' => 'success', 'message' => 'Ejercicio asignado.']);
                    } else {
                        http_response_code(500);
                        echo json_encode(['status' => 'error', 'message' => 'Error al asignar ejercicio.']);
                    }
                    $stmt->close();
                } else {
                    http_response_code(400);
                }
            } else {
                http_response_code(405);
            }
            break;

        // --- Otros casos CRUD para rutinas (actualizar, eliminar) pueden añadirse aquí ---

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

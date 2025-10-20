<?php
// api/api_auth.php

// --- Inclusión de la Conexión a la Base de Datos ---
// Se incluye el archivo de conexión para poder interactuar con la base de datos.
// `require_once` asegura que el archivo se incluya solo una vez,
// evitando errores de redefinición de variables o funciones.
require_once 'db_conexion.php';

// --- Cabeceras HTTP ---
// Se establece el tipo de contenido de la respuesta a JSON.
// Esto es fundamental para que el cliente (la aplicación Android)
// interprete correctamente los datos recibidos.
header('Content-Type: application/json');

// --- Verificación del Método de la Petición ---
// Se comprueba que la petición se haya realizado utilizando el método POST.
// El login debe ser POST para enviar las credenciales de forma segura
// en el cuerpo de la petición, en lugar de en la URL (como haría GET).
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // --- Lectura de los Datos de Entrada ---
    // Se lee el cuerpo de la petición en formato JSON y se decodifica.
    // `file_get_contents('php://input')` obtiene los datos brutos del cuerpo.
    // `json_decode` los convierte a un objeto PHP.
    $data = json_decode(file_get_contents('php://input'));

    // --- Validación de los Datos de Entrada ---
    // Se comprueba si los campos 'email' y 'password' existen y no están vacíos.
    // Es una validación básica para asegurar que se han recibido los datos necesarios.
    if (!empty($data->email) && !empty($data->password)) {

        // --- Preparación de la Consulta SQL ---
        // Se utiliza una consulta preparada para evitar la inyección SQL.
        // El uso de `?` como marcador de posición es una práctica de seguridad esencial.
        $sql = "SELECT id, nombre, rol, password FROM Usuarios WHERE email = ?";

        // `prepare` crea una sentencia preparada a partir de la consulta.
        $stmt = $conexion->prepare($sql);

        // --- Vinculación de Parámetros ---
        // Se vincula la variable `$data->email` al marcador de posición.
        // "s" indica que el tipo de dato es una cadena (string).
        $stmt->bind_param("s", $data->email);

        // --- Ejecución de la Consulta ---
        $stmt->execute();

        // --- Obtención de Resultados ---
        // `get_result` obtiene el conjunto de resultados de la consulta.
        $result = $stmt->get_result();

        // --- Verificación del Usuario ---
        // Se comprueba si se encontró exactamente un usuario con ese email.
        if ($result->num_rows == 1) {

            // `fetch_assoc` obtiene la fila de resultados como un array asociativo.
            $usuario = $result->fetch_assoc();

            // --- Verificación de la Contraseña ---
            // `password_verify` compara la contraseña proporcionada con el hash almacenado.
            // Es la forma segura y correcta de verificar contraseñas hasheadas.
            if (password_verify($data->password, $usuario['password'])) {

                // --- Respuesta de Éxito ---
                // Si la contraseña es correcta, se envía una respuesta de éxito.
                // Se devuelve el ID, el nombre y el rol del usuario.
                // Esto permite a la aplicación Android saber quién ha iniciado sesión y su rol.
                http_response_code(200); // OK
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Login correcto.',
                    'usuario' => [
                        'id' => $usuario['id'],
                        'nombre' => $usuario['nombre'],
                        'rol' => $usuario['rol']
                    ]
                ]);

            } else {
                // --- Respuesta de Error: Contraseña Incorrecta ---
                http_response_code(401); // Unauthorized
                echo json_encode(['status' => 'error', 'message' => 'Contraseña incorrecta.']);
            }

        } else {
            // --- Respuesta de Error: Usuario no Encontrado ---
            http_response_code(404); // Not Found
            echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado.']);
        }

        // --- Cierre de la Sentencia ---
        $stmt->close();

    } else {
        // --- Respuesta de Error: Datos Incompletos ---
        http_response_code(400); // Bad Request
        echo json_encode(['status' => 'error', 'message' => 'Email y contraseña son requeridos.']);
    }

} else {
    // --- Respuesta de Error: Método no Permitido ---
    // Si se utiliza un método diferente a POST, se devuelve un error.
    http_response_code(405); // Method Not Allowed
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
}

// --- Cierre de la Conexión a la BD ---
$conexion->close();
?>

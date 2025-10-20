<?php
// api/db_conexion.php

// Este script gestiona la conexión con la base de datos MySQL.
// Es un punto central para la configuración de la base de datos,
// permitiendo que otros scripts la reutilicen fácilmente.

// --- Configuración de la Base de Datos ---
// Define las credenciales de acceso.
// Es una buena práctica almacenar esta información en variables
// para facilitar su modificación y mantenimiento.
$servidor = "localhost";    // Dirección del servidor de la base de datos.
$usuario = "tu_usuario";    // Nombre de usuario de la base de datos.
$contrasena = "tu_contrasena"; // Contraseña del usuario.
$base_de_datos = "gimnasio_db"; // Nombre de la base de datos.

// --- Creación de la Conexión ---
// Se utiliza mysqli para crear una nueva conexión a la base de datos.
// El constructor de mysqli toma las credenciales como parámetros.
$conexion = new mysqli($servidor, $usuario, $contrasena, $base_de_datos);

// --- Verificación de la Conexión ---
// Es crucial comprobar si la conexión se ha establecido correctamente.
// Si hay un error (`connect_error`), el script se detiene (`die`)
// y muestra un mensaje de error descriptivo.
// Esto previene que la aplicación intente realizar operaciones
// en una base de datos no disponible, lo que podría causar errores
// inesperados.
if ($conexion->connect_error) {
    // La función die() termina la ejecución del script.
    die("Error de conexión: " . $conexion->connect_error);
}

// --- Establecer el Juego de Caracteres ---
// Se establece el juego de caracteres a UTF-8 para asegurar
// que los datos se envíen y reciban correctamente, evitando
// problemas con caracteres especiales o acentos.
$conexion->set_charset("utf8");

// --- Devolución de la Conexión ---
// Aunque no es estrictamente necesario en este script si solo
// se incluye con `require` o `include`, es una buena práctica
// tener una referencia a la conexión para ser utilizada por otros
// archivos.
// (Nota: Este script no devuelve explícitamente la conexión,
// sino que la variable $conexion estará disponible en el ámbito
// del script que lo incluya).
?>

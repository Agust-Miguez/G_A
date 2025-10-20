# Configuración del Backend PHP

Para que la API de PHP funcione correctamente, es necesario configurar las credenciales de la base de datos.

1.  **Abrir el archivo `db_conexion.php`**.
2.  **Modificar las siguientes variables** con los datos de tu servidor MySQL local:

    ```php
    $servidor = "localhost";        // O la IP de tu servidor, ej: "127.0.0.1"
    $usuario = "tu_usuario";        // Reemplazar con tu usuario de MySQL
    $contrasena = "tu_contrasena";   // Reemplazar con tu contraseña
    $base_de_datos = "gimnasio_db"; // El nombre de la base de datos creada con schema.sql
    ```

3.  **Asegúrate de haber importado el archivo `schema.sql`** en tu base de datos para que las tablas existan.

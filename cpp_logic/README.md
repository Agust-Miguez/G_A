# Configuración de la Aplicación de Lógica C++

Para que la aplicación de consola en C++ funcione, necesita dos cosas:

### 1. Conector de MySQL para C++

Esta aplicación depende de la librería del conector oficial de MySQL para C++. Debes asegurarte de tenerla instalada en tu sistema y de que el compilador pueda encontrar los archivos de cabecera (`#include <mysql/mysql.h>`) y enlazar las librerías (`-lmysqlclient`).

La forma de instalarlo varía según el sistema operativo:
*   **En Debian/Ubuntu:** `sudo apt-get install libmysqlclient-dev`
*   **En Fedora/CentOS:** `sudo yum install mysql-devel`
*   **En Windows/macOS:** Descargar el "MySQL Connector/C++" desde el sitio oficial de MySQL.

### 2. Credenciales de la Base de Datos

Es necesario configurar las credenciales de la base de datos directamente en el código fuente.

1.  **Abrir el archivo `main.cpp`**.
2.  **Localizar la siguiente línea** en la función `main`:

    ```cpp
    DbManager::getInstance().connect("127.0.0.1", "tu_usuario", "tu_contrasena", "gimnasio_db");
    ```

3.  **Reemplazar `"tu_usuario"` y `"tu_contrasena"`** con tus credenciales de MySQL.

4.  **Asegúrate de haber importado el archivo `schema.sql`** en tu base de datos `gimnasio_db`.

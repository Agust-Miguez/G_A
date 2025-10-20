-- Base de datos para el Sistema de Gestión de Gimnasio

-- Tabla de Usuarios
-- Almacena la información de todos los usuarios del sistema, incluyendo sus roles.
CREATE TABLE Usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('cliente', 'profesor', 'admin') NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de Clientes
-- Almacena información adicional específica para los clientes.
CREATE TABLE Clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    fecha_nacimiento DATE,
    telefono VARCHAR(20),
    direccion VARCHAR(255),
    FOREIGN KEY (usuario_id) REFERENCES Usuarios(id) ON DELETE CASCADE
);

-- Tabla de Pagos
-- Registra los pagos de membresía de los clientes.
CREATE TABLE Pagos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    monto DECIMAL(10, 2) NOT NULL,
    fecha_pago DATE NOT NULL,
    metodo_pago VARCHAR(50),
    FOREIGN KEY (cliente_id) REFERENCES Clientes(id) ON DELETE CASCADE
);

-- Tabla de Productos
-- Almacena los productos que el gimnasio vende.
CREATE TABLE Productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL
);

-- Tabla de Ventas
-- Registra las ventas de productos a los clientes.
CREATE TABLE Ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio_total DECIMAL(10, 2) NOT NULL,
    fecha_venta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES Clientes(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES Productos(id)
);

-- Tabla Banco de Ejercicios
-- Contiene un listado de todos los ejercicios disponibles.
CREATE TABLE Banco_Ejercicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    grupo_muscular VARCHAR(100)
);

-- Tabla de Rutinas
-- Almacena las rutinas de entrenamiento creadas por los profesores.
CREATE TABLE Rutinas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    profesor_id INT NOT NULL,
    cliente_id INT NOT NULL,
    FOREIGN KEY (profesor_id) REFERENCES Usuarios(id),
    FOREIGN KEY (cliente_id) REFERENCES Clientes(id) ON DELETE CASCADE
);

-- Tabla de Rutinas_Ejercicios
-- Tabla intermedia para la relación muchos a muchos entre rutinas y ejercicios.
CREATE TABLE Rutinas_Ejercicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rutina_id INT NOT NULL,
    ejercicio_id INT NOT NULL,
    series INT,
    repeticiones INT,
    peso INT,
    FOREIGN KEY (rutina_id) REFERENCES Rutinas(id) ON DELETE CASCADE,
    FOREIGN KEY (ejercicio_id) REFERENCES Banco_Ejercicios(id)
);

-- Tabla de Progreso de Ejercicios
-- Registra el progreso de un cliente en un ejercicio específico de su rutina.
CREATE TABLE Progreso_Ejercicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rutina_ejercicio_id INT NOT NULL,
    cliente_id INT NOT NULL,
    fecha DATE NOT NULL,
    peso_levantado INT,
    repeticiones_hechas INT,
    comentarios TEXT,
    FOREIGN KEY (rutina_ejercicio_id) REFERENCES Rutinas_Ejercicios(id) ON DELETE CASCADE,
    FOREIGN KEY (cliente_id) REFERENCES Clientes(id) ON DELETE CASCADE
);

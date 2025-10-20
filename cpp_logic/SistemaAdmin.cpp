#include "SistemaAdmin.h"
#include "DbManager.h"
#include <iostream>
#include <string>
#include <cstring> // Para memset

// --- Implementación de la Lógica del Administrador ---

SistemaAdmin::SistemaAdmin() {
    // El constructor puede permanecer vacío si no se necesita inicialización especial.
    // La conexión a la BD se gestiona a través del Singleton DbManager.
}

bool SistemaAdmin::registrarPago(int clienteId, double monto, const std::string& fechaPago) {
    // --- Obtener la Conexión a la BD ---
    MYSQL* conn = DbManager::getInstance().getConnection();
    if (conn == nullptr) {
        std::cerr << "Error: No hay conexión a la base de datos." << std::endl;
        return false;
    }

    // --- Consulta SQL Preparada ---
    // Se utiliza una consulta preparada (`?` como marcadores) para prevenir inyección SQL.
    // Esto es fundamental para la seguridad de la aplicación.
    std::string query = "INSERT INTO Pagos (cliente_id, monto, fecha_pago) VALUES (?, ?, ?)";

    // --- Preparar la Sentencia ---
    MYSQL_STMT* stmt = mysql_stmt_init(conn);
    if (!stmt) {
        std::cerr << "Error en mysql_stmt_init: " << mysql_error(conn) << std::endl;
        return false;
    }
    if (mysql_stmt_prepare(stmt, query.c_str(), query.length())) {
        std::cerr << "Error en mysql_stmt_prepare: " << mysql_stmt_error(stmt) << std::endl;
        mysql_stmt_close(stmt);
        return false;
    }

    // --- Vincular los Parámetros ---
    // Se crea un array de `MYSQL_BIND` para asociar las variables locales
    // con los marcadores de posición de la consulta.
    MYSQL_BIND params[3];
    memset(params, 0, sizeof(params));

    // Cliente ID (entero)
    params[0].buffer_type = MYSQL_TYPE_LONG;
    params[0].buffer = (char*)&clienteId;

    // Monto (doble)
    params[1].buffer_type = MYSQL_TYPE_DOUBLE;
    params[1].buffer = (char*)&monto;

    // Fecha de Pago (cadena)
    params[2].buffer_type = MYSQL_TYPE_STRING;
    params[2].buffer = (char*)fechaPago.c_str();
    params[2].buffer_length = fechaPago.length();

    // Se asocian los parámetros con la sentencia.
    if (mysql_stmt_bind_param(stmt, params)) {
        std::cerr << "Error en mysql_stmt_bind_param: " << mysql_stmt_error(stmt) << std::endl;
        mysql_stmt_close(stmt);
        return false;
    }

    // --- Ejecutar la Sentencia ---
    if (mysql_stmt_execute(stmt)) {
        std::cerr << "Error en mysql_stmt_execute: " << mysql_stmt_error(stmt) << std::endl;
        mysql_stmt_close(stmt);
        return false;
    }

    std::cout << "Pago registrado exitosamente para el cliente ID: " << clienteId << std::endl;

    // --- Limpieza ---
    mysql_stmt_close(stmt);
    return true;
}

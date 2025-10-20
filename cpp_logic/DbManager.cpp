#include "DbManager.h"
#include <iostream>

// --- Implementación del Gestor de Base de Datos ---

// --- Inicialización de la Instancia Singleton ---
// La instancia estática se inicializa a `nullptr`. Se creará
// la primera vez que se llame a `getInstance()`.
DbManager* DbManager::instance = nullptr;

// --- Constructor ---
// Inicializa el puntero de conexión a `nullptr`.
DbManager::DbManager() : connection(nullptr) {
    // La inicialización real de la conexión se hace en el método `connect`.
}

// --- Destructor ---
// Se asegura de que la conexión se cierre correctamente cuando el
// objeto DbManager sea destruido al final del programa.
DbManager::~DbManager() {
    disconnect();
    // Liberar la instancia singleton
    if (instance != nullptr) {
        delete instance;
        instance = nullptr;
    }
}

// --- Obtener Instancia (Singleton) ---
DbManager& DbManager::getInstance() {
    // Si la instancia no ha sido creada, se crea una nueva.
    // Esto se conoce como "inicialización perezosa" (lazy initialization).
    if (instance == nullptr) {
        instance = new DbManager();
    }
    return *instance;
}

// --- Conectar a la Base de Datos ---
void DbManager::connect(const std::string& host, const std::string& user, const std::string& password, const std::string& dbname) {
    // Si ya existe una conexión, no hacer nada.
    if (connection != nullptr) {
        return;
    }

    // 1. Inicializar el objeto MYSQL.
    connection = mysql_init(NULL);
    if (connection == NULL) {
        throw std::runtime_error("Error al inicializar MySQL.");
    }

    // 2. Conectarse a la base de datos.
    // `mysql_real_connect` intenta establecer la conexión.
    if (mysql_real_connect(connection, host.c_str(), user.c_str(), password.c_str(), dbname.c_str(), 0, NULL, 0) == NULL) {
        // Si falla, se lanza una excepción con el mensaje de error de MySQL.
        std::string error_msg = "Error al conectar: ";
        error_msg += mysql_error(connection);
        mysql_close(connection); // Limpiar el objeto de conexión.
        connection = nullptr;
        throw std::runtime_error(error_msg);
    }

    std::cout << "Conexión a la base de datos establecida." << std::endl;
}

// --- Desconectar de la Base de Datos ---
void DbManager::disconnect() {
    // Si existe una conexión activa, se cierra.
    if (connection != nullptr) {
        mysql_close(connection);
        connection = nullptr; // Poner a null para indicar que no hay conexión.
        std::cout << "Conexión a la base de datos cerrada." << std::endl;
    }
}

// --- Obtener el Manejador de Conexión ---
MYSQL* DbManager::getConnection() {
    return connection;
}

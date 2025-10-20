#ifndef DB_MANAGER_H
#define DB_MANAGER_H

// --- Gestor de Conexión a la Base de Datos ---
// Esta clase encapsula la lógica para conectarse y desconectarse
// de la base de datos MySQL. Utiliza el patrón Singleton para
// asegurar que solo exista una única conexión activa en toda la
// aplicación, lo cual es crucial para gestionar los recursos de
// la base de datos de manera eficiente.

// --- Inclusión de la Librería del Conector de MySQL ---
// Se asume que el conector de C++ para MySQL está instalado y
// sus cabeceras están disponibles en la ruta de inclusión del compilador.
// Este ejemplo utiliza la API del conector legado (legacy C-style API),
// que es común, pero podría adaptarse para usar X DevAPI.
#include <mysql/mysql.h>
#include <string>
#include <stdexcept>

class DbManager {
public:
    // --- Obtener Instancia (Singleton) ---
    // Devuelve la única instancia de la clase. Si no existe, la crea.
    static DbManager& getInstance();

    // --- Conectar a la Base de Datos ---
    // Establece la conexión utilizando las credenciales proporcionadas.
    // Lanza una `std::runtime_error` si la conexión falla.
    void connect(const std::string& host, const std::string& user, const std::string& password, const std::string& dbname);

    // --- Desconectar de la Base de Datos ---
    // Cierra la conexión si está abierta.
    void disconnect();

    // --- Obtener el Manejador de Conexión ---
    // Devuelve un puntero al objeto de conexión MYSQL, permitiendo
    // a otras partes del código ejecutar consultas.
    MYSQL* getConnection();

    // --- Eliminación de Copia y Asignación ---
    // Se eliminan el constructor de copia y el operador de asignación
    // para reforzar el patrón Singleton. Esto previene que se puedan
    // crear múltiples instancias de DbManager.
    DbManager(const DbManager&) = delete;
    void operator=(const DbManager&) = delete;

private:
    // --- Constructor y Destructor Privados ---
    // El constructor y el destructor son privados para que la clase
    // solo pueda ser instanciada desde dentro de sí misma (a través
    // de `getInstance`).
    DbManager();
    ~DbManager();

    // --- Atributos ---
    MYSQL* connection; // Puntero al objeto de conexión de MySQL.
    static DbManager* instance; // Puntero a la instancia única.
};

#endif // DB_MANAGER_H

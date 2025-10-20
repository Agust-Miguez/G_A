#include <iostream>
#include <string>
#include <limits>
#include "DbManager.h"
#include "SistemaAdmin.h"

// --- Función para limpiar el buffer de entrada ---
// Necesario después de leer un número para poder leer texto de forma segura.
void limpiarBuffer() {
    std::cin.ignore(std::numeric_limits<std::streamsize>::max(), '\n');
}

// --- Menú del Administrador ---
void mostrarMenuAdmin() {
    SistemaAdmin admin;
    int opcion;

    do {
        std::cout << "\n--- Menú de Administrador ---\n";
        std::cout << "1. Registrar Pago de Cliente\n";
        std::cout << "0. Volver al Menú Principal\n";
        std::cout << "Seleccione una opción: ";
        std::cin >> opcion;

        if (std::cin.fail()) {
            std::cin.clear();
            limpiarBuffer();
            opcion = -1; // Opción inválida
        } else {
            limpiarBuffer(); // Limpiar el newline
        }

        if (opcion == 1) {
            int clienteId;
            double monto;
            std::string fecha;

            std::cout << "Ingrese el ID del cliente: ";
            std::cin >> clienteId;
            std::cout << "Ingrese el monto del pago: ";
            std::cin >> monto;
            limpiarBuffer(); // Limpiar después de leer números
            std::cout << "Ingrese la fecha del pago (YYYY-MM-DD): ";
            std::getline(std::cin, fecha);

            admin.registrarPago(clienteId, monto, fecha);
        }
    } while (opcion != 0);
}


// --- Punto de Entrada Principal ---
int main() {
    try {
        // --- Conexión a la Base de Datos ---
        // Se conecta a la BD al inicio de la aplicación.
        // Reemplazar con las credenciales correctas.
        DbManager::getInstance().connect("127.0.0.1", "tu_usuario", "tu_contrasena", "gimnasio_db");

        int rol;
        do {
            std::cout << "\n--- Sistema de Lógica del Gimnasio ---\n";
            std::cout << "Seleccione su rol:\n";
            std::cout << "1. Administrador\n";
            std::cout << "2. Profesor (No implementado)\n";
            std::cout << "3. Cliente (No implementado)\n";
            std::cout << "0. Salir\n";
            std::cout << "Opción: ";
            std::cin >> rol;

            if (std::cin.fail()) {
                std::cin.clear();
                limpiarBuffer();
                rol = -1;
            } else {
                 limpiarBuffer();
            }

            switch (rol) {
                case 1:
                    mostrarMenuAdmin();
                    break;
                case 2:
                case 3:
                    std::cout << "Este rol aún no ha sido implementado.\n";
                    break;
                case 0:
                    std::cout << "Saliendo del programa.\n";
                    break;
                default:
                    std::cout << "Opción no válida. Inténtelo de nuevo.\n";
                    break;
            }
        } while (rol != 0);

        // --- Desconexión de la Base de Datos ---
        DbManager::getInstance().disconnect();

    } catch (const std::runtime_error& e) {
        // Captura de excepciones (ej. fallo de conexión).
        std::cerr << "Error crítico: " << e.what() << std::endl;
        return 1; // Terminar con código de error.
    }

    return 0; // Salida exitosa.
}

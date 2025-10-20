#ifndef SISTEMA_ADMIN_H
#define SISTEMA_ADMIN_H

#include <string>

// --- Lógica de Negocio: Sistema del Administrador ---
// Esta clase define las operaciones que puede realizar un administrador.
// Actúa como una capa de servicio que interactúa con la base de datos
// (a través de DbManager) para ejecutar la lógica de negocio.
class SistemaAdmin {
public:
    // --- Constructor ---
    SistemaAdmin();

    // --- Operación: Registrar Pago ---
    // Recibe los detalles de un pago y lo inserta en la base de datos.
    // Devuelve `true` si la operación fue exitosa, `false` en caso contrario.
    // Los parámetros son:
    // - clienteId: El ID del cliente que realiza el pago.
    // - monto: La cantidad del pago.
    // - fechaPago: La fecha del pago en formato 'YYYY-MM-DD'.
    bool registrarPago(int clienteId, double monto, const std::string& fechaPago);

    // --- Otras funciones de administrador podrían ir aquí ---
    // bool crearUsuario(...);
    // bool registrarVenta(...);
};

#endif // SISTEMA_ADMIN_H

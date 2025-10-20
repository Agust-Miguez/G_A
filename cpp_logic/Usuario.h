#ifndef USUARIO_H
#define USUARIO_H

#include <string>

// --- Modelo de Datos: Usuario ---
// Esta clase representa a un usuario en la aplicación de C++.
// Contiene los datos básicos de un usuario, como su ID, nombre y rol.
// Es una estructura de datos simple (similar a un POJO en Java) para
// almacenar la información recuperada de la base de datos.
class Usuario {
public:
    // --- Constructor ---
    // Permite crear un objeto Usuario con valores iniciales.
    // Se utiliza un valor por defecto para el rol si no se especifica.
    Usuario(int id, const std::string& nombre, const std::string& rol)
        : id(id), nombre(nombre), rol(rol) {}

    // --- Getters ---
    // Métodos públicos para acceder a los atributos privados de la clase.
    int getId() const { return id; }
    const std::string& getNombre() const { return nombre; }
    const std::string& getRol() const { return rol; }

private:
    // --- Atributos ---
    int id;
    std::string nombre;
    std::string rol;
};

#endif // USUARIO_H

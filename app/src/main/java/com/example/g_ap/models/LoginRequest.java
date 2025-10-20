package com.example.g_ap.models;

// --- Modelo de Datos: LoginRequest ---
// Esta clase representa el cuerpo de la petición JSON que se enviará
// al endpoint de login. Contiene las credenciales del usuario (email y contraseña).
// Es un POJO que Retrofit/Gson serializará a un objeto JSON como este:
// {
//   "email": "usuario@example.com",
//   "password": "suc_ontraseña"
// }
public class LoginRequest {

    // --- Atributos ---
    // Los nombres de los atributos coinciden exactamente con las claves
    // esperadas por la API en el JSON.
    private String email;
    private String password;

    // --- Constructor ---
    // Permite crear una nueva instancia de la petición con las credenciales necesarias.
    public LoginRequest(String email, String password) {
        this.email = email;
        this.password = password;
    }

    // --- Getters y Setters ---
    // Aunque para la serialización con Gson no son estrictamente necesarios
    // si los nombres coinciden, es una buena práctica incluirlos.

    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public String getPassword() {
        return password;
    }

    public void setPassword(String password) {
        this.password = password;
    }
}

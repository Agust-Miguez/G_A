package com.example.g_ap.models;

import com.google.gson.annotations.SerializedName;

// --- Modelo de Datos: LoginResponse ---
// Esta clase está diseñada para mapear la respuesta JSON completa
// del endpoint de autenticación (api_auth.php).
// Encapsula todos los campos de la respuesta: el estado, el mensaje y el objeto de usuario.
public class LoginResponse {

    // --- Atributos ---
    // Cada atributo se corresponde con una clave en el objeto JSON de respuesta.
    // @SerializedName asegura un mapeo correcto.

    @SerializedName("status")
    private String status;

    @SerializedName("message")
    private String message;

    // Este atributo es un objeto anidado. Gson se encargará automáticamente
    // de parsear el objeto JSON 'usuario' y convertirlo a una instancia
    // de la clase Usuario.
    @SerializedName("usuario")
    private Usuario usuario;

    // --- Getters ---
    // Métodos de acceso para obtener los datos de la respuesta.

    public String getStatus() {
        return status;
    }

    public String getMessage() {
        return message;
    }

    public Usuario getUsuario() {
        return usuario;
    }
}

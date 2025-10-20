package com.example.g_ap.models;

import com.google.gson.annotations.SerializedName;

// --- Modelo de Datos: Usuario ---
// Esta clase representa la entidad 'Usuario' tal como se recibe de la API.
// Utiliza la librería Gson para mapear automáticamente los campos JSON
// a los atributos de la clase. Es un objeto POJO (Plain Old Java Object).
public class Usuario {

    // --- Atributos ---
    // @SerializedName se utiliza para indicar el nombre exacto del campo
    // en la respuesta JSON. Aunque en este caso los nombres coinciden,
    // es una buena práctica usarlo para evitar problemas si los nombres
    // de las variables en Java y las claves en JSON difieren.

    @SerializedName("id")
    private int id;

    @SerializedName("nombre")
    private String nombre;

    @SerializedName("rol")
    private String rol;

    // --- Constructor ---
    // Un constructor para crear instancias de la clase Usuario.
    public Usuario(int id, String nombre, String rol) {
        this.id = id;
        this.nombre = nombre;
        this.rol = rol;
    }

    // --- Getters ---
    // Métodos públicos para acceder a los valores de los atributos privados,
    // siguiendo el principio de encapsulación.

    public int getId() {
        return id;
    }

    public String getNombre() {
        return nombre;
    }

    public String getRol() {
        return rol;
    }
}

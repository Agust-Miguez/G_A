package com.example.g_ap.api;

import retrofit2.Retrofit;
import retrofit2.converter.gson.GsonConverterFactory;

// --- Cliente de API (Singleton de Retrofit) ---
// Esta clase sigue el patrón de diseño Singleton para asegurar que solo exista
// una instancia de Retrofit en toda la aplicación. Esto es eficiente y
// consistente, ya que evita crear múltiples objetos Retrofit que consumen recursos.
public class ApiClient {

    // --- URL Base de la API ---
    // Esta es la dirección raíz de la API en el servidor local.
    // Para que un emulador de Android se conecte a 'localhost' en la máquina anfitriona,
    // se debe usar la dirección IP especial '10.0.2.2'.
    private static final String BASE_URL = "http://10.0.2.2/api/";

    // --- Instancia Única de Retrofit ---
    // La variable `retrofit` es estática, lo que significa que pertenece a la clase
    // y no a una instancia particular. Se inicializa como `null`.
    private static Retrofit retrofit = null;

    // --- Método Público para Obtener la Instancia ---
    // Este método estático y sincronizado (`synchronized`) proporciona un punto de
    // acceso global y seguro (thread-safe) a la instancia de Retrofit.
    public static Retrofit getClient() {
        // La primera vez que se llama a este método, `retrofit` será `null`.
        if (retrofit == null) {
            // Se construye la instancia de Retrofit.
            retrofit = new Retrofit.Builder()
                    // Se establece la URL base para todas las peticiones.
                    .baseUrl(BASE_URL)
                    // Se añade un convertidor de Gson, que se encargará de
                    // serializar los objetos Java a JSON y viceversa.
                    .addConverterFactory(GsonConverterFactory.create())
                    // Se construye el objeto Retrofit.
                    .build();
        }
        // Se devuelve la instancia (nueva o existente).
        return retrofit;
    }
}

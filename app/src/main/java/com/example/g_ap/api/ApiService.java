package com.example.g_ap.api;

import com.example.g_ap.models.LoginRequest;
import com.example.g_ap.models.LoginResponse;

import retrofit2.Call;
import retrofit2.http.Body;
import retrofit2.http.POST;

// --- Interfaz de Servicio de la API (Retrofit) ---
// Esta interfaz es el núcleo de la comunicación de red con Retrofit.
// Aquí se definen todos los endpoints de la API a los que la aplicación
// necesitará acceder. Retrofit utiliza esta interfaz para generar una
// implementación funcional en tiempo de ejecución.
public interface ApiService {

    // --- Endpoint de Login ---
    // La anotación @POST("api_auth.php") indica que este método realizará
    // una petición HTTP POST al endpoint 'api_auth.php'.
    //
    // El parámetro del método, anotado con @Body, le dice a Retrofit que
    // serialice el objeto `LoginRequest` a formato JSON y lo envíe
    // en el cuerpo de la petición.
    //
    // El tipo de retorno es `Call<LoginResponse>`. Retrofit envolverá
    // la respuesta de la API en un objeto `Call`, que se puede ejecutar
    // de forma síncrona o asíncrona. `LoginResponse` le indica a Retrofit
    // y a Gson que la respuesta JSON debe ser deserializada a un objeto
    // de la clase LoginResponse.
    @POST("api_auth.php")
    Call<LoginResponse> login(@Body LoginRequest loginRequest);

    // --- Otros Endpoints ---
    // Aquí se añadirían las definiciones para otros endpoints, por ejemplo:
    // @GET("api_cliente.php?accion=get_rutinas")
    // Call<RutinasResponse> getRutinas(@Query("cliente_id") int clienteId);
    //
    // @POST("api_cliente.php?accion=post_progreso")
    // Call<Void> registrarProgreso(@Body ProgresoRequest progreso);
}

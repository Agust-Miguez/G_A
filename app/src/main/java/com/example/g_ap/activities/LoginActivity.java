package com.example.g_ap.activities;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ProgressBar;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.example.g_ap.R;
import com.example.g_ap.api.ApiClient;
import com.example.g_ap.api.ApiService;
import com.example.g_ap.models.LoginRequest;
import com.example.g_ap.models.LoginResponse;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class LoginActivity extends AppCompatActivity {

    private EditText editTextEmail, editTextPassword;
    private Button buttonLogin;
    private ProgressBar progressBar;
    private ApiService apiService;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        // --- Inicialización de Vistas y Servicio ---
        editTextEmail = findViewById(R.id.editTextEmail);
        editTextPassword = findViewById(R.id.editTextPassword);
        buttonLogin = findViewById(R.id.buttonLogin);
        progressBar = findViewById(R.id.progressBar);

        // Se obtiene el servicio de la API a través del cliente singleton de Retrofit.
        apiService = ApiClient.getClient().create(ApiService.class);

        // --- Configuración del Listener del Botón ---
        buttonLogin.setOnClickListener(v -> loginUsuario());
    }

    private void loginUsuario() {
        String email = editTextEmail.getText().toString().trim();
        String password = editTextPassword.getText().toString().trim();

        if (email.isEmpty() || password.isEmpty()) {
            Toast.makeText(this, "Por favor, complete todos los campos", Toast.LENGTH_SHORT).show();
            return;
        }

        // Mostrar el ProgressBar y deshabilitar el botón
        progressBar.setVisibility(View.VISIBLE);
        buttonLogin.setEnabled(false);

        // --- Realizar la Petición de Login ---
        LoginRequest loginRequest = new LoginRequest(email, password);
        apiService.login(loginRequest).enqueue(new Callback<LoginResponse>() {
            @Override
            public void onResponse(Call<LoginResponse> call, Response<LoginResponse> response) {
                // Ocultar el ProgressBar y habilitar el botón
                progressBar.setVisibility(View.GONE);
                buttonLogin.setEnabled(true);

                if (response.isSuccessful() && response.body() != null) {
                    LoginResponse loginResponse = response.body();
                    if ("success".equals(loginResponse.getStatus()) && loginResponse.getUsuario() != null) {
                        // Navegar al dashboard correspondiente según el rol
                        String rol = loginResponse.getUsuario().getRol();
                        int usuarioId = loginResponse.getUsuario().getId();
                        String nombreUsuario = loginResponse.getUsuario().getNombre();
                        navegarSegunRol(rol, usuarioId, nombreUsuario);
                    } else {
                        Toast.makeText(LoginActivity.this, loginResponse.getMessage(), Toast.LENGTH_LONG).show();
                    }
                } else {
                    // Manejar errores de la respuesta (ej. 401, 404)
                    Toast.makeText(LoginActivity.this, "Error: " + response.message(), Toast.LENGTH_LONG).show();
                }
            }

            @Override
            public void onFailure(Call<LoginResponse> call, Throwable t) {
                // Ocultar el ProgressBar y habilitar el botón
                progressBar.setVisibility(View.GONE);
                buttonLogin.setEnabled(true);
                // Manejar errores de red
                Toast.makeText(LoginActivity.this, "Fallo de conexión: " + t.getMessage(), Toast.LENGTH_LONG).show();
            }
        });
    }

    private void navegarSegunRol(String rol, int usuarioId, String nombreUsuario) {
        Intent intent;
        switch (rol) {
            case "cliente":
                intent = new Intent(LoginActivity.this, ClienteDashboardActivity.class);
                break;
            case "profesor":
                intent = new Intent(LoginActivity.this, ProfesorDashboardActivity.class);
                break;
            case "admin":
                intent = new Intent(LoginActivity.this, AdminDashboardActivity.class);
                break;
            default:
                Toast.makeText(this, "Rol no reconocido", Toast.LENGTH_LONG).show();
                return; // No navegar
        }
        // Añadir datos extra al Intent para pasarlos a la siguiente actividad
        intent.putExtra("USUARIO_ID", usuarioId);
        intent.putExtra("NOMBRE_USUARIO", nombreUsuario);

        startActivity(intent);
        finish(); // Finalizar LoginActivity para que el usuario no pueda volver
    }
}

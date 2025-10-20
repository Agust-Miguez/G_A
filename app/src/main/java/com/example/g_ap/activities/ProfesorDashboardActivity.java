package com.example.g_ap.activities;

import android.os.Bundle;
import androidx.appcompat.app.AppCompatActivity;
import com.example.g_ap.R;

// --- Dashboard del Profesor ---
// Pantalla principal para los usuarios con el rol 'profesor'.
// Permitirá gestionar rutinas, consultar el banco de ejercicios y
// ver el progreso de sus clientes.
public class ProfesorDashboardActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_profesor_dashboard);

        // La lógica para los botones y la interacción con la API
        // se implementaría aquí.
    }
}

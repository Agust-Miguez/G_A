package com.example.g_ap.activities;

import android.os.Bundle;
import androidx.appcompat.app.AppCompatActivity;
import com.example.g_ap.R;

// --- Dashboard del Administrador ---
// Pantalla principal para los usuarios con el rol 'admin'.
// Proporcionará acceso a la gestión de pagos, ventas, productos
// y, potencialmente, la gestión de usuarios.
public class AdminDashboardActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_admin_dashboard);

        // Aquí se añadiría la lógica para los botones, que navegarían
        // a las actividades de gestión de pagos, ventas, etc.
    }
}

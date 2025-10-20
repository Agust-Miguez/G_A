package com.example.g_ap.activities;

import android.os.Bundle;
import android.widget.TextView;
import androidx.appcompat.app.AppCompatActivity;
import com.example.g_ap.R;

// --- Dashboard del Cliente ---
// Esta actividad será la pantalla principal para los usuarios con el rol 'cliente'.
// Desde aquí, podrán ver sus rutinas, registrar su progreso y acceder a otras
// funcionalidades específicas para ellos.
public class ClienteDashboardActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        // Se establece el layout que definirá la interfaz de esta actividad.
        setContentView(R.layout.activity_cliente_dashboard);

        // --- Personalización de la Bienvenida ---
        // (A modo de ejemplo, se podría pasar el nombre del usuario desde LoginActivity)
        TextView welcomeText = findViewById(R.id.textViewWelcome);
        // String nombreUsuario = getIntent().getStringExtra("NOMBRE_USUARIO");
        // if (nombreUsuario != null) {
        //     welcomeText.setText("¡Hola, " + nombreUsuario + "!");
        // }
    }
}

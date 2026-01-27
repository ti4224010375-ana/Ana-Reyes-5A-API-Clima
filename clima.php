<?php
// Capa de Procesos: Lógica para obtener datos del servicio
$ciudad = "Carmen, Campeche";
// Usamos una API pública de prueba (7-Timer) que no requiere registro para esta práctica inicial
$apiUrl = "http://www.7timer.info/bin/api.pl?lon=91.413&lat=18.453&product=civil&output=json";

$response = file_get_contents($apiUrl);
$data = json_decode($response, true);


// Función para iconos dinámicos
function getClimaIcon($w) {
    $w = strtolower($w);
   if (strpos($w, 'rain') !== false) return "🌧️";
    if (strpos($w, 'clear') !== false) return "☀️";
    if (strpos($w, 'pcloudy') !== false) return "⛅";
    if (strpos($w, 'mcloudy') !== false) return "🌥️";
    if (strpos($w, 'cloudy') !== false) return "☁️";
    if (strpos($w, 'ts') !== false) return "⛈️";
    return "⛅";
}

$hoy = $data['dataseries'][0];

// Capa de Acceso: Presentación de los datos al usuario
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clima - Ciudad del Carmen</title>
    <style>
       body { 
            font-family: 'Segoe UI', sans-serif; 
            background: linear-gradient(180deg, #0288d1 0%, #fce4ec 100%); 
            display: flex; justify-content: center; align-items: center; 
            min-height: 100vh; margin: 0; padding: 20px 0; color: #333;
        }
        .card { 
            background: rgba(255, 255, 255, 0.95); 
            padding: 25px; border-radius: 40px; 
            box-shadow: 0 20px 50px rgba(0,0,0,0.2); 
            width: 350px; text-align: center;
        }
        .city-tag { color: #01579b; font-weight: bold; font-size: 1.1rem; margin-bottom: 10px; }
        .main-icon { font-size: 70px; margin: 10px 0; }
        .main-temp { font-size: 4.5rem; color: #ec407a; font-weight: bold; margin: 0; }
        
        /* Cuadrícula de detalles */
        .details-grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 12px;
            margin: 20px 0; padding: 15px; background: #e1f5fe; border-radius: 25px;
        }
        .detail-item { font-size: 0.8rem; color: #555; text-align: center; }
        .detail-item b { display: block; color: #0277bd; font-size: 0.95rem; margin-top: 2px; }

        /* Pronóstico por horas */
        .section-title { font-size: 0.75rem; font-weight: bold; color: #01579b; text-align: left; margin: 15px 0 10px 10px; text-transform: uppercase; }
        
        .hourly-scroll {
            display: flex; overflow-x: auto; gap: 12px; padding-bottom: 10px;
        }
        .hourly-scroll::-webkit-scrollbar { height: 4px; }
        .hourly-scroll::-webkit-scrollbar-thumb { background: #90caf9; border-radius: 10px; }
        
        .hour-card {
            min-width: 50px; background: white; padding: 10px; border-radius: 15px;
            text-align: center; box-shadow: 0 4px 8px rgba(0,0,0,0.03);
        }

        /* Lista de días */
        .day-row { 
            display: flex; justify-content: space-between; align-items: center;
            padding: 10px; border-bottom: 1px solid #f0f0f0;
        }
        .day-name { font-weight: bold; width: 45px; color: #0277bd; font-size: 0.9rem; }
        .day-temp { font-weight: bold; color: #ec407a; }
    </style>
</head>
<body>
    <div class="card">
        <div class="city-tag"><?php echo $ciudad; ?></div>
        
        <div class="main-icon"><?php echo getClimaIcon($hoy['weather']); ?></div>
        <div class="main-temp"><?php echo $hoy['temp2m']; ?>°</div>
        <div style="font-weight: bold; color: #666; text-transform: uppercase; margin-bottom: 10px;">
            <?php echo $hoy['weather']; ?>
        </div>

        <div class="details-grid">
            <div class="detail-item">Viento <b>🌬️ <?php echo $hoy['wind10m']['speed']; ?> km/h</b></div>
            <div class="detail-item">Humedad <b>💧 <?php echo $hoy['rh2m']; ?></b></div>
            <div class="detail-item">Nublado <b>☁️ <?php echo $hoy['cloudcover']; ?>/9</b></div>
            <div class="detail-item">Precipitación <b>☔ <?php echo $hoy['prec_type']; ?></b></div>
        </div>

        <div class="section-title">Próximas Horas</div>
        <div class="hourly-scroll">
            <?php for ($i = 0; $i < 6; $i++) { $h = $data['dataseries'][$i]; ?>
                <div class="hour-card">
                    <div style="font-size: 0.65rem; color: #999;">+<?php echo $h['timepoint']; ?>h</div>
                    <div style="font-size: 1.2rem; margin: 3px 0;"><?php echo getClimaIcon($h['weather']); ?></div>
                    <div style="font-weight: bold; font-size: 0.9rem; color: #ec407a;"><?php echo $h['temp2m']; ?>°</div>
                </div>
            <?php } ?>
        </div>

        <div class="section-title">Próximos Días</div>
        <div class="days-list">
            <?php 
            $nombres = ["Hoy", "Dom", "Lun", "Mar", "Mié", "Jue"];
            for ($i = 0; $i < count($nombres); $i++) { 
                $d = $data['dataseries'][$i * 8]; // Salto de 24h aprox.
            ?>
            <div class="day-row">
                <span class="day-name"><?php echo $nombres[$i]; ?></span>
                <span style="font-size: 1.3rem;"><?php echo getClimaIcon($d['weather']); ?></span>
                <span style="font-size: 0.75rem; color: #888;">💧 <?php echo $d['rh2m']; ?></span>
                <span class="day-temp"><?php echo $d['temp2m']; ?>°</span>
            </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Traductor Minion Mágico</title>
    <style>
        body {
        /*aqui para mi fondo */
            background-color: #FFF2A1; 
            font-family: 'Arial', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            color: #00529B;
        }

        /* aqui para todo mi cuadro blanco el tamaño*/
        .contenedor {
            text-align: center;
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0px 10px 30px rgba(0,0,0,0.1); 
            max-width: 400px;
        }

        .minion-img {
        /*aqui para mi imagen de arriba su tamaño */
            width: 120px;
            margin-bottom: 10px;
        }

        input {
            padding: 12px;
            border-radius: 10px;
            /* aqui pues para el cuadro de donde se introduce eñ texto */
            border: 1px solid #ddd; 
            width: 85%;
            margin-bottom: 15px;
            outline: none;
            font-size: 16px;
        }

        button {
            background-color: #00529B; /* para el color del boton */
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 50px; /* para el botón más redondeado */
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
            transition: 0.3s;
        }

       
        .resultado {
            margin-top: 20px;
            padding: 15px;
            background-color: #f0f8ff; /* Un azul muy pálido de fondo */
            border-radius: 15px;
            /* Borde punteado */
            border: 2px dashed #00529B; 
            font-weight: bold;
            font-size: 1.2em;
        }
    </style>
</head>
<body>

    <div class="contenedor"> 
        <img src="https://pngimg.com/uploads/minions/minions_PNG57.png" alt="Minion" class="minion-img">
        
        <h1>¡Bello! 🍌</h1>
        <p>Escribe en inglés y te lo traduzco</p>
        
        <form method="POST">
            <input type="text" name="frase" placeholder="Ej: I love bananas" required>
            <br>
            <button type="submit">¡Traducir ahora!</button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $textoParaTraducir = $_POST["frase"];
            $url = "https://api.funtranslations.com/translate/minion.json?text=" . urlencode($textoParaTraducir);
            $respuesta = @file_get_contents($url);
            
            if ($respuesta) {
                $datos = json_decode($respuesta, true);
                if (isset($datos['contents']['translated'])) {
                    echo "<div class='resultado'>";
                    echo "<span style='font-size: 0.8em; color: #555;'>Dice el Minion:</span><br>";
                    echo "<span style='color: #00529B;'>" . $datos['contents']['translated'] . "</span>";
                    echo "</div>";
                }
            } else {
                echo "<p style='color:#d9534f; margin-top:10px; font-weight:bold;'>¡Oh no! Los Minions están ocupados comiendo bananas. Prueba en unos minutos.</p>";
            }
        }
        ?>
    </div>

</body>
</html>
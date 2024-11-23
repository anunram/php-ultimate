<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rifa/Sorteo</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>PARTICIPA Y GANA</h1>
        
        <!-- Mensaje informativo -->
        <p class="info-message">
            El número para participar tiene un costo de <strong>$1</strong>. 
            Habrá <strong>tres números ganadores</strong> al final del sorteo.
        </p>
        
        <form id="raffleForm" action="process.php" method="POST">
            <div class="form-group">
                <label for="name">Nombre:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="phone">Número de celular:</label>
                <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" required>
            </div>
            <div class="form-group">
                <label for="number">Escoge un número (00-99):</label>
                <select id="number" name="number" required>
                    <?php
                    for ($i = 0; $i < 100; $i++) {
                        $formatted_number = str_pad($i, 2, "0", STR_PAD_LEFT);
                        echo "<option value='$i'>$formatted_number</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit">Participar</button>
        </form>
    </div>
</body>
</html>

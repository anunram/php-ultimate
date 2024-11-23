<?php
// Configuración de la conexión a la base de datos
$host = "localhost";
$user = "root";
$password = "";
$dbname = "raffle_db";

// Conectar a MySQL
$conn = new mysqli($host, $user, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Inicializar el mensaje
$message = "";

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $raffle_number = intval($_POST['number']);

    // Verificar si el número ya ha sido seleccionado
    $sql_check = "SELECT * FROM participants WHERE raffle_number = $raffle_number";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        // El número ya ha sido seleccionado, mostrar mensaje de error
        $message = "El número $raffle_number ya ha sido seleccionado. Por favor elige otro número.";
    } else {
        // Si el número no ha sido seleccionado, proceder a guardarlo
        $sql = "INSERT INTO participants (name, phone, raffle_number) 
                VALUES ('$name', '$phone', $raffle_number)";

        if ($conn->query($sql) === TRUE) {
            $message = "Gracias por participar, $name. Tu número es $raffle_number. Ingresa a este enlace para terminar la transacción de manera segura: <a href='https://wa.link/etjn9d' target='_blank'>https://wa.link/etjn9d</a>";
        } else {
            $message = "Error al registrar tus datos. Por favor, intenta de nuevo.";
        }
    }
}

// Cerrar conexión
$conn->close();
?>

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

        <!-- Mensaje de error o éxito -->
        <?php if ($message): ?>
            <div id="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Formulario -->
        <form id="raffleForm" action="process.php" method="POST">
            <div class="form-group">
                <label for="name">Nombre:</label>
                <input type="text" id="name" name="name" required placeholder="Ingresa tu nombre">
            </div>
            <div class="form-group">
                <label for="phone">Número de celular:</label>
                <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" required placeholder="Ingresa tu número (10 dígitos)">
            </div>
            <div class="form-group">
                <label for="number">Escoge un número (1-100):</label>
                <select id="number" name="number" required>
                    <?php
                    // Generar opciones del 1 al 100, excluyendo los números ya seleccionados
                    $selected_numbers = [];
                    $conn = new mysqli($host, $user, $password, $dbname);
                    $sql = "SELECT raffle_number FROM participants";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $selected_numbers[] = $row['raffle_number'];
                        }
                    }

                    // Generar las opciones, excluyendo los números ya seleccionados
                    for ($i = 1; $i <= 100; $i++) {
                        if (!in_array($i, $selected_numbers)) {
                            echo "<option value='$i'>$i</option>";
                        }
                    }

                    // Cerrar la conexión para esta parte del código
                    $conn->close();
                    ?>
                </select>
            </div>
            <button type="submit">Participar</button>
        </form>
    </div>
</body>
</html>

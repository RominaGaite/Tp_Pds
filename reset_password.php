<?php
// reset_password.php

session_start();
include('db.php'); // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Verificar si el correo existe en la base de datos
    $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generar token de restablecimiento
        $token = bin2hex(random_bytes(50));
        $expiracion = date("Y-m-d H:i:s", strtotime('+1 hour'));

        // Actualizar la base de datos con el token y la fecha de expiración
        $stmt = $conn->prepare("UPDATE usuarios SET reset_token = ?, reset_token_expiration = ? WHERE email = ?");
        $stmt->bind_param("sss", $token, $expiracion, $email);
        $stmt->execute();

        // Enviar correo electrónico con el enlace de restablecimiento de contraseña
        $resetLink = "http://localhost/TuProyecto/cambiar_contraseña.php?token=" . $token;
        $subject = "Restablecer contraseña";
        $message = "Haz clic en el siguiente enlace para restablecer tu contraseña: " . $resetLink;
        $headers = "From: no-reply@tuprojecto.com\r\n";

        mail($email, $subject, $message, $headers);

        // Mostrar la alerta de SweetAlert
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                title: '¡Correo enviado!',
                text: 'Hemos enviado un correo para que restablezcas tu contraseña.',
                icon: 'success',
                width: '40%', // Tamaño más grande
                position: 'center', // Centrado
                showConfirmButton: false, // Ocultar botón de confirmación
                timer: 3000, // Duración de la alerta (3 segundos)
                timerProgressBar: true // Mostrar barra de progreso
            });
        </script>";
    } else {
        echo "<script>alert('Correo no encontrado.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./estilos.css">
    <title>Recuperar Contraseña</title>
</head>
<body>
    <div class="login-container">
    <div class="login-img"></div>
        <div class="login-form">
            <h2>Recuperar Contraseña</h2>
            <form action="reset_password.php" method="POST">
                <label for="email">Ingresa tu correo electrónico:</label>
                <input type="email" id="email" name="email" required>
                <button type="submit">Restablecer Contraseña</button>
            </form>
            <p><a href="index.php">Iniciar sesión</a></p> <!-- Enlace para iniciar sesión -->
        </div>
    </div>
</body>
</html>

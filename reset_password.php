<?php
// reset_password.php

session_start();
include('db.php'); // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Verificar si el correo existe en la base de datos
    $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
    $stmt->bindParam(1, $email, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->rowCount();

    // Inicializar variables para la alerta
    $alertTitle = "";
    $alertText = "";
    $alertIcon = "";
    $redirectUrl = "";

    if ($result > 0) {
        // Generar token de restablecimiento
        $token = bin2hex(random_bytes(50));
        $expiracion = date("Y-m-d H:i:s", strtotime('+1 hour'));

        // Actualizar la base de datos con el token y la fecha de expiración
        $stmt = $conn->prepare("UPDATE usuarios SET reset_token = ?, reset_token_expiration = ? WHERE email = ?");
        $stmt->bindParam(1, $token, PDO::PARAM_STR);
        $stmt->bindParam(2, $expiracion, PDO::PARAM_STR);
        $stmt->bindParam(3, $email, PDO::PARAM_STR);
        $stmt->execute();

        // Configurar alerta para correo enviado
        $alertTitle = 'Correo enviado';
        $alertText = 'Se ha enviado un correo para restablecer tu contraseña.';
        $alertIcon = 'success';
        $redirectUrl = "simulate_mail.php?token=$token&email=$email"; // Redirigir a la página que simula el correo enviado
    } else {
        // Configurar alerta para correo no encontrado
        $alertTitle = 'Error';
        $alertText = 'Correo no encontrado.';
        $alertIcon = 'error';
        $redirectUrl = 'reset_password.php'; // Redirigir a la misma página en caso de error
    }

    // Mostrar la alerta de SweetAlert y redirigir después de 5 segundos
    echo "
    <!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <link rel='stylesheet' href='./estilos.css'>
        <title>Recuperar Contraseña</title>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: '$alertTitle',
                    text: '$alertText',
                    icon: '$alertIcon',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '$alertIcon' === 'success' ? '#4CAF50' : '#f44336'
                });

                // Redirigir después de 5 segundos (5000 ms)
                setTimeout(function() {
                    window.location.href = '$redirectUrl';
                }, 5000);
            });
        </script>
    </body>
    </html>";
    exit; // Asegúrate de salir después de mostrar la alerta
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='./estilos.css'>
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

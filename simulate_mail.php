<?php
session_start();
include('db.php'); // Conexión a la base de datos

if (!isset($_GET['token']) || !isset($_GET['email'])) {
    header("Location: reset_password.php");
    exit;
}

$token = $_GET['token'];
$email = $_GET['email'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='./estilos.css'>
    <title>Correo Simulado</title>
</head>
<body>
    <div class="email-simulation">
        <h2>Correo de Restablecimiento de Contraseña</h2>
        <p>Hemos enviado un correo a <?php echo htmlspecialchars($email); ?> con un enlace para restablecer tu contraseña.</p>
        <p>Haz clic en el siguiente botón para cambiar tu contraseña:</p>
        <a href="new_password.php?token=<?php echo htmlspecialchars($token); ?>" class="button">Cambiar Contraseña</a>
    </div>
</body>
</html>

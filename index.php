<?php
session_start();
include('db.php'); // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validar usuario
    $stmt = $conn->prepare("SELECT id_usuario, contraseña, perfil FROM usuarios WHERE email = ? AND estado = 'activo'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['contraseña'])) {
        $_SESSION['user_id'] = $user['id_usuario'];
        $_SESSION['perfil'] = $user['perfil'];

        // Redirigir al panel de admin o a usuario
        if ($user['perfil'] == 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: user_dashboard.php");
        }
    } else {
        $error = "Credenciales incorrectas.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./estilos.css">
    <title>Iniciar Sesión</title>
</head>
<body>
    <div class="container">

        <div  class="login-img">
            <img src="./assets/imgportada.png" alt="">
        </div>
        
        <div class="login-form">
        <h2>Iniciar Sesión</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="index.php" method="POST">
            <label for="email">Correo electrónico:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Ingresar</button>
        </form>
        <p><a href="resetpasword.php">¿Olvidaste tu contraseña?</a></p>
        <p>¿No tienes cuenta? <a href="register.php">Regístrate aquí</a></p>
        </div>
        
    </div>
</body>
</html>

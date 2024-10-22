<?php
session_start();
include('db.php'); // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validar usuario con PDO
    try {
        $stmt = $conn->prepare("SELECT id_usuario, contraseña, perfil FROM usuarios WHERE email = :email AND estado = 'activo'");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Ver si el usuario existe y si la contraseña esta ok
        if ($user && password_verify($password, $user['contraseña'])) {
            // Iniciar sesión y almacenar los datos en la sesión
            $_SESSION['user_id'] = $user['id_usuario'];
            $_SESSION['perfil'] = $user['perfil'];

            // Redirigir al panel de admin o de usuario según el rol
            if ($user['perfil'] == 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: user_dashboard.php");
            }
            exit();
        } else {
            $error = "Credenciales incorrectas.";
        }
    } catch (PDOException $e) {
        $error = "Error en la base de datos: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./estilos.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Iniciar Sesión</title>
</head>
<body>
<div class="login-container">
    <div class="login-img"></div>
    <div class="login-form">
        <h2>Iniciar Sesión</h2>
        <?php if (isset($error)): ?>
            <script>
                Swal.fire({
                    title: 'Error',
                    text: '<?php echo $error; ?>',
                    icon: 'error'
                });
            </script>
        <?php endif; ?>
        
        <form action="index.php" method="POST">
            <label for="email">Correo electrónico</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Log In</button>
        </form>

        <!-- si quiere recuperar contraseña -->
        <p><a href="reset_password.php">¿Olvidaste tu contraseña?</a></p>
        <p>¿No tienes cuenta? <a href="register.php">Regístrate aquí</a></p>
    </div>
</div>

</body>
</html>

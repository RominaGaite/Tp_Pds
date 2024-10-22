<?php
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_completo = $_POST['nombre_completo'];
    $email = $_POST['email'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $password = $_POST['password'];
    $perfil = $_POST['profile'];
    
    // Validaciones de la contraseña
    if (strlen($password) < 8 || !preg_match("/[A-Z]/", $password) || !preg_match("/[a-z]/", $password) || !preg_match("/\d/", $password) || !preg_match("/[@$!%*#?&+]/", $password)) {
        $error = "La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un símbolo.";
    } else {
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        // Verificar si el email ya está registrado
        $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = "El correo ya está registrado.";
        } else {
            $stmt = $conn->prepare("INSERT INTO usuarios (nombre_completo, email, fecha_nacimiento, contraseña, perfil) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $nombre_completo, $email, $fecha_nacimiento, $password_hash, $perfil);
            if ($stmt->execute()) {
                // Redirigir al usuario a la página de inicio después de registrarse
                header("Location: index.php");
                exit(); // Termina el script para asegurar que no se ejecute nada más
            } else {
                $error = "Error en el registro.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./estilos.css">
    <title>Registro de Usuario</title>
</head>
<body>
    <div class="login-container">
        <div class="login-img"></div>
        <div class="login-form">
            <h2>Registro</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <form action="register.php" method="POST">
                <label for="nombre_completo">Nombre Completo</label>
                <input type="text" id="nombre_completo" name="nombre_completo" required>
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" required>
                <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
                <label for="profile">Perfil</label>
                <select id="profile" name="profile" required>
                    <option value="usuario">Usuario</option>
                    <option value="admin">Administrador</option>
                </select>
                <button type="submit">Registrarse</button>
            </form>
        </div>
    </div>
</body>
</html>


<?php
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $perfil = $_POST['profile'];
    
    // Validaciones de la contraseña
    if (strlen($password) < 8 || !preg_match("/[A-Z]/", $password) || !preg_match("/\d/", $password) || !preg_match("/[@$!%*#?&]/", $password)) {
        $error = "La contraseña debe tener al menos 8 caracteres, una mayúscula, un número y un símbolo.";
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
            $stmt = $conn->prepare("INSERT INTO usuarios (email, contraseña, perfil) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $email, $password_hash, $perfil);
            if ($stmt->execute()) {
                $success = "Usuario registrado con éxito.";
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
    <link rel="stylesheet" href="css/style.css">
    <title>Registro de Usuario</title>
</head>
<body>
    <div class="container">
        <h2>Registro</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php elseif (isset($success)): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
        <form action="register.php" method="POST">
            <label for="email">Correo electrónico:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
            <label for="profile">Perfil:</label>
            <select id="profile" name="profile" required>
                <option value="usuario">Usuario</option>
                <option value="admin">Administrador</option>
            </select>
            <button type="submit">Registrarse</button>
        </form>
    </div>
</body>
</html>

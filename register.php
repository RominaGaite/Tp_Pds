<?php
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_completo = $_POST['nombre_completo'];
    $email = $_POST['email'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $password = $_POST['password'];
    $perfil = $_POST['profile'];
    
    // Validar la contraseña
    if (strlen($password) < 8 || !preg_match("/[A-Z]/", $password) || !preg_match("/[a-z]/", $password) || !preg_match("/\d/", $password) || !preg_match("/[@$!%*#?&+]/", $password)) {
        $error = "La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un símbolo.";
    } else {
        // Encriptar la contraseña
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        try {
            // Ver si el email ya está registrado
            $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);  // Corregir el tipo de parámetro
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                $error = "El correo ya está registrado.";
            } else {
                // preparar la consulta a la bd
                $stmt = $conn->prepare("INSERT INTO usuarios (nombre_completo, email, fecha_nacimiento, contraseña, perfil) 
                                        VALUES (:nombre_completo, :email, :fecha_nacimiento, :password_hash, :perfil)");
                
                // Enlazar los parámetros con los nombres correctos
                $stmt->bindParam(':nombre_completo', $nombre_completo, PDO::PARAM_STR);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento, PDO::PARAM_STR);
                $stmt->bindParam(':password_hash', $password_hash, PDO::PARAM_STR);
                $stmt->bindParam(':perfil', $perfil, PDO::PARAM_STR);
                
                // hacer la consulta y ver si esta ok 
                if ($stmt->execute()) {
                    header("Location: index.php");
                    exit();
                } else {
                    $error = "Error en el registro.";
                }
            }
        } catch (PDOException $e) {
            $error = "Error en la base de datos: " . $e->getMessage();
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

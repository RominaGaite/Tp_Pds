<?php
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include('db.php');
        $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Validar el token y actualizar la contraseña
        $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE reset_token = ? AND reset_token_exp > NOW()");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            $stmt = $conn->prepare("UPDATE usuarios SET contraseña = ?, reset_token = NULL, reset_token_exp = NULL WHERE reset_token = ?");
            $stmt->bind_param("ss", $new_password, $token);
            $stmt->execute();

            // Mensaje de éxito con SweetAlert
            echo "<script>
                    Swal.fire({
                      title: '¡Contraseña actualizada!',
                      text: 'Tu contraseña ha sido modificada con éxito.',
                      icon: 'success'
                    }).then(function() {
                        window.location = 'index.php';
                    });
                  </script>";
        } else {
            echo "<script>
                    Swal.fire({
                      title: 'Error',
                      text: 'Token inválido o expirado.',
                      icon: 'error'
                    });
                  </script>";
        }
    }
} else {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Contraseña</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="./estilos.css">
</head>
<body>
<div class="login-container">
    <div class="login-form">
        <h2>Generar Nueva Contraseña</h2>
        <form action="new_password.php?token=<?php echo htmlspecialchars($token); ?>" method="POST">
            <label for="password">Nueva Contraseña:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Guardar Nueva Contraseña</button>
        </form>
    </div>
</div>
</body>
</html>

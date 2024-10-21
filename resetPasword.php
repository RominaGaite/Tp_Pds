<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./estilos.css">
    <title>Recuperar Contraseña</title>
</head>
<body>
    <div class="container">
        <h2>Recuperar Contraseña</h2>
        <form action="process_forgot_password.php" method="POST">
            <label for="email">Ingresa tu correo electrónico:</label>
            <input type="email" id="email" name="email" required>
            <button type="submit">Enviar</button>
        </form>
    </div>
</body>
</html>

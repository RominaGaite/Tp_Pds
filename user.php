<?php
include('db.php');

// Verificar si se ha pasado un ID en la URL
if (isset($_GET['id'])) {
    $id_usuario = $_GET['id'];

    // Preparar la consulta para obtener los datos del usuario
    $stmt = $conn->prepare("SELECT nombre_completo, email, fecha_nacimiento, perfil, estado, fecha_registro FROM usuarios WHERE id_usuario = ?");
    $stmt->bindParam(1, $id_usuario, PDO::PARAM_INT);
    $stmt->execute();

    // Verificar si se encontró el usuario
    if ($stmt->rowCount() > 0) {
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        // Si no se encuentra el usuario, redirigir o mostrar un error
        header("Location: admin.php"); // O mostrar un mensaje de error
        exit();
    }
} else {
    // Si no se pasa un ID, redirigir
    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./estilos.css">
    <title>Perfil de Usuario</title>
</head>
<body>
    <div class="containerAdmin">
        <h2>Perfil de Usuario</h2>
        <table>
            <tr>
                <td><strong>Nombre Completo:</strong></td>
                <td><?php echo htmlspecialchars($usuario['nombre_completo']); ?></td>
            </tr>
            <tr>
                <td><strong>Correo Electrónico:</strong></td>
                <td><?php echo htmlspecialchars($usuario['email']); ?></td>
            </tr>
            <tr>
                <td><strong>Fecha de Nacimiento:</strong></td>
                <td><?php echo htmlspecialchars($usuario['fecha_nacimiento']); ?></td>
            </tr>
            <tr>
                <td><strong>Perfil:</strong></td>
                <td><?php echo htmlspecialchars($usuario['perfil']); ?></td>
            </tr>
            <tr>
                <td><strong>Estado:</strong></td>
                <td><?php echo htmlspecialchars($usuario['estado']); ?></td>
            </tr>
            <tr>
                <td><strong>Fecha de Registro:</strong></td>
                <td><?php echo htmlspecialchars($usuario['fecha_registro']); ?></td>
            </tr>
            <tr>
                <td><strong>Contraseña:</strong></td>
                <td>*********</td>
            </tr>
        </table>
        <br>
        <a href="admin.php"><button class="btn-volver">Volver</button></a>
    </div>
</body>
</html>

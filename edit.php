<?php
session_start();
include('db.php');

if (!isset($_GET['id'])) {
    die("ID de usuario no proporcionado.");
}

$id_usuario = $_GET['id'];

// Obtener los datos del usuario
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Usuario no encontrado.");
}

$usuario = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Procesar el formulario de edición
    $nombre_completo = $_POST['nombre_completo'];
    $email = $_POST['email'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $estado = $_POST['estado'];
    
    // Actualizar el usuario en la base de datos
    $stmt = $conn->prepare("UPDATE usuarios SET nombre_completo = ?, email = ?, fecha_nacimiento = ?, estado = ? WHERE id_usuario = ?");
    $stmt->bind_param("ssssi", $nombre_completo, $email, $fecha_nacimiento, $estado, $id_usuario);

    if ($stmt->execute()) {
        header("Location: admin.php"); // Redirigir después de la actualización
        exit();
    } else {
        $error = "Error al actualizar el usuario.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./estilos.css">
    <title>Editar Usuario</title>
</head>
<body>
    <div class="containerAdmin">
        <h2>Editar Usuario</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="edit.php?id=<?php echo $id_usuario; ?>" method="POST">
            <label for="nombre_completo">Nombre Completo</label>
            <input type="text" id="nombre_completo" name="nombre_completo" value="<?php echo htmlspecialchars($usuario['nombre_completo']); ?>" required>
            <label for="email">Correo Electrónico</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
            <label for="fecha_nacimiento">Fecha de Nacimiento</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo htmlspecialchars($usuario['fecha_nacimiento']); ?>" required>
            <label for="estado">Estado</label>
            <select id="estado" name="estado" required>
                <option value="activo" <?php if ($usuario['estado'] == 'activo') echo 'selected'; ?>>Activo</option>
                <option value="inactivo" <?php if ($usuario['estado'] == 'inactivo') echo 'selected'; ?>>Inactivo</option>
                <option value="suspendido" <?php if ($usuario['estado'] == 'suspendido') echo 'selected'; ?>>Suspendido</option>
            </select>
            <button type="submit">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>
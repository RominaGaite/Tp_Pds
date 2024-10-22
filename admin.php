<?php
include('db.php');

// Manejar la eliminación de usuarios
if (isset($_GET['eliminar'])) {
    $id_usuario = intval($_GET['eliminar']);
    
    // Obtener el nombre del usuario para la alerta
    $stmt_user = $conn->prepare("SELECT nombre_completo FROM usuarios WHERE id_usuario = :id_usuario");
    $stmt_user->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt_user->execute();
    $usuario = $stmt_user->fetch(PDO::FETCH_ASSOC);
    $nombre_usuario = $usuario['nombre_completo'];

    // Eliminar el usuario
    $stmt_delete = $conn->prepare("DELETE FROM usuarios WHERE id_usuario = :id_usuario");
    $stmt_delete->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt_delete->execute();
    
    // Redireccionar a la misma página para evitar el reenvío del formulario
    header("Location: admin.php?eliminado=1&nombre=$nombre_usuario&id=$id_usuario");
    exit();
}

// Obtener todos los usuarios registrados
$stmt = $conn->prepare("SELECT id_usuario, nombre_completo, email, fecha_nacimiento, contraseña, estado, fecha_registro FROM usuarios");
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./estilos.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
    <title>Panel de Administración</title>
</head>
<body>
    <div class="containerAdmin">
        <h2>Panel de Administración</h2>
        <a href="logout.php" class="logout-button">Cerrar Sesión</a> 
        
        <div>
            <table>
                <thead>
                    <tr>
                        <th>ID Usuario</th>
                        <th>Nombre Completo</th>
                        <th>Email</th>
                        <th>Fecha de Nacimiento</th>
                        <th>Estado</th>
                        <th>Fecha de Registro</th>
                        <th>Contraseña</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $row): ?>
                        <tr>
                            <td><?php echo $row['id_usuario']; ?></td>
                            <td><?php echo htmlspecialchars($row['nombre_completo']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['fecha_nacimiento']); ?></td>
                            <td><?php echo htmlspecialchars($row['estado']); ?></td>
                            <td><?php echo htmlspecialchars($row['fecha_registro']); ?></td>
                            <td>******</td>
                            <td>
                                <a href="user.php?id=<?php echo $row['id_usuario']; ?>"><img class="icono" src="https://cdn-icons-png.flaticon.com/128/8373/8373337.png" alt="Ir al perfil"></a>
                                <a href="edit.php?id=<?php echo $row['id_usuario']; ?>"><img class="icono" src="https://cdn-icons-png.flaticon.com/128/84/84380.png" alt="Editar"></a>
                                <a href="#" onclick="confirmDelete('<?php echo htmlspecialchars($row['nombre_completo']); ?>', <?php echo $row['id_usuario']; ?>); return false;"><img class="icono" src="https://cdn-icons-png.flaticon.com/128/10221/10221510.png" alt="Eliminar"></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function confirmDelete(nombre, id) {
            Swal.fire({
                title: '¿Está seguro que desea eliminar al usuario ' + nombre + ' (ID: ' + id + ')?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirigir a la misma página con la acción de eliminar
                    window.location.href = 'admin.php?eliminar=' + id;
                }
            });
        }

        // Mostrar la alerta de eliminación exitosa
        <?php if (isset($_GET['eliminado'])): ?>
            Swal.fire({
                title: 'Usuario eliminado correctamente',
                text: 'El usuario <?php echo htmlspecialchars($_GET['nombre']); ?> (ID: <?php echo htmlspecialchars($_GET['id']); ?>) ha sido eliminado correctamente.',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            });
        <?php endif; ?>
    </script>
</body>
</html>

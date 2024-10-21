<?php
session_start();

// Verificar si el usuario tiene rol de administrador
if (!isset($_SESSION['user_id']) || $_SESSION['perfil'] != 'admin') {
    header("Location: index.php");
    exit();
}

include('db.php');

// Obtener el registro de accesos
$result = $conn->query("SELECT * FROM accesos");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Panel de Administración</title>
</head>
<body>
    <div class="container">
        <h2>Panel de Administración</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Usuario</th>
                    <th>Fecha de Acceso</th>
                    <th>Status</th>
                    <th>IP</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id_usuario']; ?></td>
                        <td><?php echo $row['fecha']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td><?php echo $row['ip']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

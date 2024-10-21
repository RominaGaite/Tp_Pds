<?php
function registrar_acceso($id_usuario, $status) {
    include('db.php');
    $ip = $_SERVER['REMOTE_ADDR'];
    $stmt = $conn->prepare("INSERT INTO accesos (id_usuario, fecha, status, ip) VALUES (?, NOW(), ?, ?)");
    $stmt->bind_param("iss", $id_usuario, $status, $ip);
    $stmt->execute();
}
?>

<?php
$host = "localhost";
$user = "root";      // Por defecto es root
$password = "";      // Por defecto no hay contraseña
$db = "sistemalogin"; // Nombre de la base de datos

// Crear conexión
$conn = new mysqli($host, $user, $password, $db);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>

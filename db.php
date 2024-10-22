<?php
$host = "localhost";
$db = "sistemalogin";  
$user = "root";       
$password = "";        

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $password);
     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
       die("Error de conexión: " . $e->getMessage());
}
?>


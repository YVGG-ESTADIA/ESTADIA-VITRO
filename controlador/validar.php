<?php
session_start();
include '../modelo/config.php';

if (!isset($pdo)) {
    die("Error: No se pudo conectar a la base de datos.");
}

$stmt = $pdo->query("SELECT * FROM usuarios");
var_dump($stmt->fetchAll());

if (isset($_POST['usuario']) && isset($_POST['contrasena'])) {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    $sql = "SELECT * FROM usuarios WHERE usuario = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$usuario]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($contrasena, $user['contrasena'])) {
        $_SESSION['usuario'] = $user['usuario'];
        $_SESSION['tipo'] = $user['tipo'];
        header("Location: ../vista/inicio.php");
        exit;
    } else {
        // Redirige con error visible
        header("Location: ../vista/login.php?error=1");
        exit;
    }
} else {
    header("Location: ../vista/login.php");
    exit;
}
?>

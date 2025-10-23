<?php
session_start();

// Redirigir si ya está logueado
if (isset($_SESSION['usuario'])) {
    header("Location: inicio.php");
    exit;
}

// Detectar error
$error = isset($_GET['error']) ? true : false;

include 'includes/head.php';
include 'includes/navbar.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vitro - Login</title>
    <link href="css/bootstrap/bootstrap-4.3.1.css" rel="stylesheet">
    <style>
        .login-card {
            max-width: 400px;
            margin: 80px auto;
            border-radius: 10px;
            background: linear-gradient(to right, #00B1D9, #005077);
            color: white;
            padding: 30px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
        }

        .login-card h3 {
            margin-bottom: 20px;
            text-align: center;
        }

        .form-control {
            margin-bottom: 15px;
        }

        .login-btn {
            background-color: white;
            color: #005077;
            font-weight: bold;
            border: none;
            width: 100%;
        }

        .login-btn:hover {
            background-color: #e6e6e6;
        }

        .recover-password {
            text-align: center;
            margin-top: 10px;
        }

        .recover-password a {
            color: white;
            text-decoration: underline;
        }

        .recover-password a:hover {
            color: #ffeb3b;
        }

        .error-msg {
            text-align: center;
            background-color: #ff4d4d;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            color: white;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="login-card">
            <h3>Iniciar Sesión</h3>

            <?php if ($error): ?>
                <div class="error-msg">Usuario o contraseña incorrectos</div>
            <?php endif; ?>

            <form action="../controlador/validar.php" method="post">
                <input type="text" name="usuario" class="form-control" placeholder="Usuario" required>
                <input type="password" name="contrasena" class="form-control" placeholder="Contraseña" required>
                <button type="submit" name="ingresar" class="btn login-btn">Ingresar</button>
                <!-- <div class="recover-password">
      <a href="#">Recuperar contraseña</a>
    </div> -->
            </form>
        </div>
    </div>

    <script src="css/bootstrap/js/jquery-3.3.1.min.js"></script>
    <script src="css/bootstrap/js/popper.min.js"></script>
    <script src="css/bootstrap/js/bootstrap-4.3.1.js"></script>
</body>

</html>
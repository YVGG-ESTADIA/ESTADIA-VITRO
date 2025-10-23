<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$page = basename($_SERVER['PHP_SELF']);
?>

<style>
    .navbar-custom {
        background: linear-gradient(to right, #00B1D9, #005077);
    }

    .navbar-custom .nav-link {
        color: white !important;
        font-weight: bold;
    }

    .navbar-custom .nav-link:hover {
        color: #ffeb3b !important;
    }

    .navbar-custom .active {
        border-bottom: 3px solid #ffeb3b;
    }

    .navbar-custom .nav-item {
        margin-left: 15px;
        margin-right: 15px;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid px-3">
        <a class="navbar-brand" href="inicio.php">
            <img src="images/Logo.png" style="width:90px;">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarPrincipal"
            aria-controls="navbarPrincipal" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarPrincipal">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?= ($page == "inicio.php") ? "active" : "" ?>" href="inicio.php">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($page == "clientes.php") ? "active" : "" ?>" href="clientes.php">Clientes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($page == "parabrisas.php") ? "active" : "" ?>" href="parabrisas.php">Parabrisas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($page == "cultura.php") ? "active" : "" ?>" href="cultura.php">Cultura</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($page == "organigrama_procesos.php") ? "active" : "" ?>" href="organigrama_procesos.php">Organigrama / Procesos</a>
                </li>

                <?php if (!isset($_SESSION["usuario"])): ?>
                    <li class="nav-item">
                        <a class="nav-link text-success <?= ($page == "login.php") ? "active" : "" ?>" href="login.php">Iniciar Sesión</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="../logout.php">Cerrar Sesión</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
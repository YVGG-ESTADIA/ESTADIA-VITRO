<?php
session_start();
include '../modelo/config.php';

// Leer los textos actuales
$stmt = $pdo->query("SELECT * FROM inicio WHERE id = 1");
$inicio = $stmt->fetch(PDO::FETCH_ASSOC);


if (isset($_POST['guardar']) && isset($_SESSION['usuario'])) {
    $sql = "UPDATE inicio SET 
        titulo_principal = ?, 
        texto_principal = ?, 
        historia_titulo = ?, 
        historia_texto = ?, 
        mision_titulo = ?, 
        mision_texto = ?, 
        global_titulo = ?, 
        global_texto = ? 
        WHERE id = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['titulo_principal'],
        $_POST['texto_principal'],
        $_POST['historia_titulo'],
        $_POST['historia_texto'],
        $_POST['mision_titulo'],
        $_POST['mision_texto'],
        $_POST['global_titulo'],
        $_POST['global_texto']
    ]);
    header("Location: inicio.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Vitro - Inicio</title>
    <link href="css/bootstrap/bootstrap-4.3.1.css" rel="stylesheet">
    <style>
        .navbar-custom {
            background: linear-gradient(to right, #00B1D9, #005077);
        }

        .edit-btn {
            position: absolute;
            top: 90px;
            right: 30px;
            z-index: 100;
        }
    </style>
</head>

<body>

    <?php include 'includes/head.php'; ?>
    <?php include 'includes/navbar.php'; ?>

    <?php if (isset($_SESSION['usuario'])): ?>
        <a href="#" class="btn btn-warning edit-btn" id="editarBtn">Editar</a>
    <?php endif; ?>

    <div class="container my-5">
        <form method="POST" id="formEdicion" style="display:none;">
            <div class="form-group">
                <label>Título Principal</label>
                <input type="text" name="titulo_principal" class="form-control" value="<?= htmlspecialchars($inicio['titulo_principal']) ?>">
            </div>
            <div class="form-group">
                <label>Texto Principal</label>
                <textarea name="texto_principal" class="form-control" rows="2"><?= htmlspecialchars($inicio['texto_principal']) ?></textarea>
            </div>

            <div class="form-group">
                <label>Título Historia</label>
                <input type="text" name="historia_titulo" class="form-control" value="<?= htmlspecialchars($inicio['historia_titulo']) ?>">
            </div>
            <div class="form-group">
                <label>Texto Historia</label>
                <textarea name="historia_texto" class="form-control" rows="4"><?= htmlspecialchars($inicio['historia_texto']) ?></textarea>
            </div>

            <div class="form-group">
                <label>Título Misión y Visión</label>
                <input type="text" name="mision_titulo" class="form-control" value="<?= htmlspecialchars($inicio['mision_titulo']) ?>">
            </div>
            <div class="form-group">
                <label>Texto Misión y Visión</label>
                <textarea name="mision_texto" class="form-control" rows="4"><?= htmlspecialchars($inicio['mision_texto']) ?></textarea>
            </div>

            <div class="form-group">
                <label>Título Presencia Global</label>
                <input type="text" name="global_titulo" class="form-control" value="<?= htmlspecialchars($inicio['global_titulo']) ?>">
            </div>
            <div class="form-group">
                <label>Texto Presencia Global</label>
                <textarea name="global_texto" class="form-control" rows="4"><?= htmlspecialchars($inicio['global_texto']) ?></textarea>
            </div>

            <button type="submit" name="guardar" class="btn btn-success">Guardar Cambios</button>
        </form>

        <!-- VISTA NORMAL (sin edición) -->
        <div id="vistaNormal">
            <header class="jumbotron text-center bg-light">
                <h1 class="display-4"><?= htmlspecialchars($inicio['titulo_principal']) ?></h1>
                <p class="lead"><?= htmlspecialchars($inicio['texto_principal']) ?></p>
            </header>

            <div class="row text-center">
                <div class="col-md-4">
                    <h3><?= htmlspecialchars($inicio['historia_titulo']) ?></h3>
                    <p><?= htmlspecialchars($inicio['historia_texto']) ?></p>
                </div>
                <div class="col-md-4">
                    <h3><?= htmlspecialchars($inicio['mision_titulo']) ?></h3>
                    <p><?= htmlspecialchars($inicio['mision_texto']) ?></p>
                </div>
                <div class="col-md-4">
                    <h3><?= htmlspecialchars($inicio['global_titulo']) ?></h3>
                    <p><?= htmlspecialchars($inicio['global_texto']) ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- CAROUSEL -->
    <div class="container my-5">
        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="d-block w-100" src="images/Marinela.png" alt="Slide 1">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" src="images/Oxxo.png" alt="Slide 2">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" src="images/Coca.jpg" alt="Slide 3">
                </div>
            </div>
            <!-- Controles para cambiar slide -->
            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                <span class="carousel-control-next-icon"></span>
            </a>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer-custom">
        <footer class="text-center text-dark p-2">
            <p>
                <a href="https://www.google.com/maps/place/Vitroflex/@25.7672327,-100.5686756,17z/data=!3m1!4b1!4m6!3m5!1s0x86629d1591e5263d:0xe95f58896ad0a825!8m2!3d25.7672327!4d-100.5661007!16s%2Fg%2F11fthcl1vg?entry=ttu&g_ep=EgoyMDI1MDkzMC4wIKXMDSoASAFQAw%3D%3D"
                    class="text-dark"
                    target="_blank"
                    rel="noopener noreferrer">
                    Carretera Km 10, Joaquín García, 66000 García
                </a>
            </p>
        </footer>
    </div>

    <script>
        document.getElementById('editarBtn')?.addEventListener('click', function() {
            document.getElementById('formEdicion').style.display = 'block';
            document.getElementById('vistaNormal').style.display = 'none';
            this.style.display = 'none';
        });
    </script>

    <!-- jQuery, Popper.js y Bootstrap JS -->
    <script src="css/bootstrap/js/jquery-3.3.1.min.js"></script>
    <script src="css/bootstrap/js/popper.min.js"></script>
    <script src="css/bootstrap/js/bootstrap-4.3.1.js"></script>

    <script>
        // Activar el carrusel automáticamente cada 3 segundos
        $('.carousel').carousel({
            interval: 3000, // cambia de imagen cada 3 segundos
            ride: 'carousel'
        });
    </script>
</body>

</html>
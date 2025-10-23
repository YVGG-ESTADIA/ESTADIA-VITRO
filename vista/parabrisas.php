<?php
session_start();
include 'includes/head.php';
include 'includes/navbar.php';

$parabrisasLista = [
    ["id" => 1, "name" => "Parabrisas Alfa", "image" => "images/Logo.png", "text" => "Descripción breve del Parabrisas Alfa."],
    ["id" => 2, "name" => "Parabrisas Beta", "image" => "images/foto.jpg", "text" => "Descripción breve del Parabrisas Beta."],
    ["id" => 3, "name" => "Parabrisas Gamma", "image" => "images/avatar.jpg", "text" => "Descripción breve del Parabrisas Gamma."],
    ["id" => 4, "name" => "Parabrisas Delta", "image" => "images/facebook.png", "text" => "Descripción breve del Parabrisas Delta."]
];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vitro - Parabrisas</title>
    <link href="css/bootstrap/bootstrap-4.3.1.css" rel="stylesheet">

    <style>
        .card-custom {
            border: 2px solid black;
            border-radius: 15px;
            margin: 10px;
            text-align: center;
            transition: transform 0.2s ease;
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 300px;
        }

        .card-custom:hover {
            transform: scale(1.05);
        }

        .card-custom img {
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        .card-text {
            padding: 20px;
            font-size: 1.1rem;
        }

        .row-cards {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .col-custom {
            flex: 1 1 calc(25% - 20px);
            max-width: 300px;
            display: flex;
            justify-content: center;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="container my-5">
            <div class="row row-cards">
                <?php foreach ($parabrisasLista as $parabrisas): ?>
                    <div class="col-custom">
                        <div class="card card-custom">
                            <img src="<?= $parabrisas['image'] ?>" alt="<?= $parabrisas['name'] ?>">
                            <div class="card-text">
                                <h5><?= $parabrisas['name'] ?></h5>
                                <p><?= $parabrisas['text'] ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script src="css/bootstrap/js/jquery-3.3.1.min.js"></script>
    <script src="css/bootstrap/js/popper.min.js"></script>
    <script src="css/bootstrap/js/bootstrap-4.3.1.js"></script>
</body>

</html>
<?php
session_start();
include 'includes/head.php';
include 'includes/navbar.php';

$procesos = [
    ["title" => "CORTE", "image" => "images/Logo.png", "text" => "Descripción del proceso de Corte."],
    ["title" => "PINTURA", "image" => "images/Logo.png", "text" => "Descripción del proceso de Pintura."],
    ["title" => "HORNOS", "image" => "images/Logo.png", "text" => "Descripción del proceso de Hornos."],
    ["title" => "ENSAMBLE", "image" => "images/Logo.png", "text" => "Descripción del proceso de Ensamble."],
    ["title" => "ACABADO", "image" => "images/Logo.png", "text" => "Descripción del proceso de Acabado."]
];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vitro - Organigrama</title>
    <link href="css/bootstrap/bootstrap-4.3.1.css" rel="stylesheet">

    <style>
        body {
            background-color: #f3f3f3;
        }

        .organigrama-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 50px auto;
            margin-bottom: 5px;
            width: 90%;
            position: relative;
        }

        .nivel {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            border-radius: 10px;
            padding: 20px;
            width: 100%;
            margin-bottom: 60px;
            position: relative;
            z-index: 2;
        }

        /* Fondo gris para todos los niveles */
        .nivel-1,
        .nivel-2,
        .nivel-3 {
            background-color: #d3d3d3;
            /* gris claro */
        }

        /* --- Info box (bloque de texto a la izquierda) --- */
        .info-box {
            font-weight: bold;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            min-width: 140px;
            margin-right: 40px;
            border: 2px solid #333;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            position: relative;
            z-index: 3;
        }

        /* Colores específicos para info-box según nivel */
        .nivel-1 .info-box {
            background-color: #ffed4cff;
            /* amarillo  */
            color: #000000;
        }

        .nivel-2 .info-box {
            background-color: #808000;
            /* verde olivo */
            color: #ffffff;
        }

        .nivel-3 .info-box {
            background-color: #000080;
            /* azul oscuro */
            color: #ffffff;
        }

        .fotos-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 100px;
            flex-grow: 1;
            position: relative;
        }

        .foto-box {
            background-color: #19546e;
            border-radius: 10px;
            width: 180px;
            height: 130px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            color: white;
            font-size: 18px;
            font-weight: bold;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            border: 2px solid #000;
        }

        .foto-box img {
            width: 80%;
            height: auto;
            object-fit: contain;
        }

        svg#lineas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        svg path {
            stroke: #444;
            stroke-width: 2.5;
            fill: none;
        }

        @media (max-width: 768px) {
            .nivel {
                flex-direction: column;
                align-items: center;
            }

            .fotos-container {
                gap: 40px;
            }

            .info-box {
                margin-right: 0;
                margin-bottom: 20px;
            }
        }

        .nivel-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .nivel-list li {
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
        }

        .process-container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            flex-wrap: nowrap;
            overflow-x: auto;
            padding: 20px;
            margin-top: 5px;
        }

        .process-step {
            background: white;
            border: 2px solid #800080;
            border-radius: 10px;
            text-align: center;
            margin: 0 15px;
            padding: 10px;
            min-width: 200px;
            max-width: 250px;
            flex-shrink: 0;
            position: relative;
        }

        .process-step h4 {
            background-color: #800080;
            color: white;
            padding: 10px;
            border-radius: 10px 10px 0 0;
            margin: 0;
        }

        .process-step img {
            width: 100%;
            border-radius: 5px;
            margin-top: 10px;
        }

        .process-text {
            padding: 10px;
            font-size: 0.95rem;
        }

        /* Flechas entre procesos */
        .arrow {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 70px;
            color: #ccccf0c7;
            margin: 0 5px;
        }
    </style>

    <script>
        const data = {
            nivel1: ["Veni Garcia"],
            nivel2: ["Enilton Diaz", "Marco Ortiz"],
            nivel3: ["Eduardo Villareal", "Andres Lara", "Abraham Lara"]
        };

        function renderNivelList(id, items) {
            const ul = document.getElementById(id);
            ul.innerHTML = "";
            items.forEach(item => {
                const li = document.createElement("li");
                li.textContent = "● " + item;
                ul.appendChild(li);
            });
        }

        window.addEventListener("load", () => {
            renderNivelList("nivel-1-list", data.nivel1);
            renderNivelList("nivel-2-list", data.nivel2);
            renderNivelList("nivel-3-list", data.nivel3);
            conectarLineasRectas();
        });

        window.addEventListener("resize", conectarLineasRectas);
    </script>
</head>

<body>
    <div>
        <div class="organigrama-container">
            <svg id="lineas"></svg>

            <!-- NIVEL 1 -->
            <div class="nivel nivel-1">
                <div class="info-box">
                    <ul id="nivel-1-list" class="nivel-list"></ul>
                </div>
                <div class="fotos-container">
                    <div class="foto-box" id="n1-1">
                        <img src="images/Logo.png" alt="Logo">
                    </div>
                </div>
            </div>

            <!-- NIVEL 2 -->
            <div class="nivel nivel-2">
                <div class="info-box">
                    <ul id="nivel-2-list" class="nivel-list"></ul>
                </div>
                <div class="fotos-container">
                    <div class="foto-box" id="n2-1">
                        <img src="images/Logo.png" alt="Logo">
                    </div>
                    <div class="foto-box" id="n2-2">
                        <img src="images/Logo.png" alt="Logo">
                    </div>
                </div>
            </div>

            <!-- NIVEL 3 -->
            <div class="nivel nivel-3">
                <div class="info-box">
                    <ul id="nivel-3-list" class="nivel-list"></ul>
                </div>
                <div class="fotos-container">
                    <div class="foto-box" id="n3-1">
                        <img src="images/Logo.png" alt="Logo">
                    </div>
                    <div class="foto-box" id="n3-2">
                        <img src="images/Logo.png" alt="Logo">
                    </div>
                    <div class="foto-box" id="n3-3">
                        <img src="images/Logo.png" alt="Logo">
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="css/bootstrap/js/jquery-3.3.1.min.js"></script>
    <script src="css/bootstrap/js/popper.min.js"></script>
    <script src="css/bootstrap/js/bootstrap-4.3.1.js"></script>

    <script>
        function conectarLineasRectas() {
            const svg = document.getElementById("lineas");
            svg.innerHTML = "";

            const conexiones = [{
                    origen: "n1-1",
                    destino: "n2-1"
                },
                {
                    origen: "n1-1",
                    destino: "n2-2"
                },
                {
                    origen: "n2-1",
                    destino: "n3-1"
                },
                {
                    origen: "n2-1",
                    destino: "n3-2"
                },
                {
                    origen: "n2-2",
                    destino: "n3-3"
                },
            ];

            const svgRect = svg.getBoundingClientRect();

            conexiones.forEach(c => {
                const origen = document.getElementById(c.origen);
                const destino = document.getElementById(c.destino);
                if (!origen || !destino) return;

                const oRect = origen.getBoundingClientRect();
                const dRect = destino.getBoundingClientRect();

                const x1 = oRect.left + oRect.width / 2 - svgRect.left;
                const y1 = oRect.bottom - svgRect.top;
                const x2 = dRect.left + dRect.width / 2 - svgRect.left;
                const y2 = dRect.top - svgRect.top;

                const midY = (y1 + y2) / 2;

                const path = document.createElementNS("http://www.w3.org/2000/svg", "path");
                const d = `
                M ${x1} ${y1}
                V ${midY}
                H ${x2}
                V ${y2}
                `;
                path.setAttribute("d", d.trim());
                svg.appendChild(path);
            });
        }

        window.addEventListener("load", conectarLineasRectas);
        window.addEventListener("resize", conectarLineasRectas);
    </script>
    <div>
        <div class="process-container">
            <?php foreach ($procesos as $index => $proceso): ?>
                <div class="process-step">
                    <h4><?= $proceso['title'] ?></h4>
                    <img src="<?= $proceso['image'] ?>" alt="<?= $proceso['title'] ?>">
                    <div class="process-text"><?= $proceso['text'] ?></div>
                    <!-- aqui deberia ir el nomnbre del encargado de los que estan en organigrama-->
                </div>

                <?php if ($index < count($procesos) - 1): ?>
                    <div class="arrow">&#8594;</div> <!-- Flecha -->
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>
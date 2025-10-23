<?php
session_start();
include 'includes/head.php';
include 'includes/navbar.php';
include '../modelo/config.php';

$usuario_logueado = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;

// Traer datos de la tabla cultura
$stmt = $pdo->query("SELECT * FROM cultura");
$cultura_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$js_items = [];
foreach ($cultura_items as $item) {
    $obj = [
        'type' => $item['tipo'],
        'top' => (int)$item['top'],
        'left' => (int)$item['left']
    ];

    if ($item['tipo'] === 'text') {
        $obj['text'] = $item['contenido'] ?? '';
        $obj['font'] = $item['font'] ?? 'Arial';
        $obj['size'] = $item['size'] ?? '14px';
        $obj['color'] = $item['color'] ?? '#000000';
        $obj['bold'] = (bool)($item['bold'] ?? false);
        $obj['italic'] = (bool)($item['italic'] ?? false);
        $obj['underline'] = (bool)($item['underline'] ?? false);
    } else {
        // Ajustar ruta de imagen
        $src = $item['contenido'] ?? '';
        if (strpos($src, 'vista/') === 0) {
            $src = substr($src, strlen('vista/'));
        }
        $obj['src'] = '' . $src;
        $obj['width'] = (int)($item['width'] ?? 150);
        $obj['height'] = (int)($item['height'] ?? 150);
    }

    $js_items[] = $obj;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vitro - Cultura</title>
    <link href="css/bootstrap/bootstrap-4.3.1.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
        }

        .editor-container {
            border: 3px solid #001f54;
            height: 90vh;
            width: 95vw;
            margin: auto;
            position: relative;
            overflow: hidden;
        }

        .draggable {
            position: absolute;
            cursor: move;
            border: 1px dashed transparent;
        }

        .draggable.editing {
            border: 1px dashed blue;
        }

        .draggable img {
            display: block;
        }

        .text-box {
            position: absolute;
            display: inline-block;
        }

        .text-content {
            display: inline-block;
            min-width: 30px;
            min-height: 20px;
            padding-right: 18px;
        }

        .text-box .delete-btn {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ff4444;
            border: none;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            line-height: 16px;
            font-size: 14px;
            cursor: pointer;
            display: none;
        }

        .text-box.editing .delete-btn {
            display: block;
        }

        .controls {
            margin: 10px;
        }

        .control-btn {
            margin-right: 5px;
        }

        .delete-btn {
            position: absolute;
            top: -5px;
            right: -5px;
            background: red;
            color: white;
            border: none;
            font-size: 14px;
            padding: 2px 5px;
            cursor: pointer;
            display: none;
            z-index: 10;
        }

        .resize-handle {
            position: absolute;
            width: 12px;
            height: 12px;
            background: blue;
            bottom: 0;
            right: 0;
            cursor: se-resize;
            display: none;
        }

        .draggable.editing .resize-handle,
        .draggable.editing .delete-btn {
            display: block;
        }

        #text-editor-panel {
            position: fixed;
            right: 10px;
            top: 100px;
            width: 250px;
            padding: 15px;
            background: white;
            border: 2px solid #001f54;
            border-radius: 8px;
            display: none;
            z-index: 1000;
        }

        #text-editor-panel label {
            font-size: 14px;
            margin-top: 5px;
        }

        #text-editor-panel select,
        #text-editor-panel input[type=color] {
            width: 100%;
            margin-bottom: 10px;
        }

        #text-editor-panel button {
            border: 1px solid black;
            background-color: transparent;
            color: black;
            margin-right: 2px;
            min-width: 60px;
            transition: background-color 0.2s, color 0.2s;
        }

        #text-editor-panel button.active {
            background-color: black;
            color: white;
            border-color: black;
        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="controls">
            <?php if ($usuario_logueado): ?>
                <button id="edit-btn" class="btn btn-primary control-btn">Editar</button>
                <button id="save-btn" class="btn btn-success control-btn" style="display:none;">Guardar</button>
                <button id="cancel-btn" class="btn btn-danger control-btn" style="display:none;">Cancelar</button>
                <button id="add-image-btn" class="btn btn-info control-btn" style="display:none;">Agregar Imagen</button>
                <button id="add-text-btn" class="btn btn-warning control-btn" style="display:none;">Agregar Texto</button>
            <?php endif; ?>
        </div>

        <div class="editor-container" id="editor"></div>
    </div>

    <div id="text-editor-panel">
        <h5>Editar Texto</h5>
        <label>Fuente:</label>
        <select id="font-select">
            <option value="Arial">Arial</option>
            <option value="Verdana">Verdana</option>
            <option value="Times New Roman">Times New Roman</option>
            <option value="Courier New">Courier New</option>
            <option value="Georgia">Georgia</option>
        </select>
        <label>Tamaño:</label>
        <select id="size-select">
            <option value="12px">12px</option>
            <option value="14px" selected>14px</option>
            <option value="16px">16px</option>
            <option value="18px">18px</option>
            <option value="20px">20px</option>
            <option value="24px">24px</option>
        </select>
        <label>Color:</label>
        <input type="color" id="color-select" value="#000000">
        <div>
            <button id="btn-bold" class="btn btn-sm btn-secondary">Negrita</button>
            <button id="btn-italic" class="btn btn-sm btn-secondary">Cursiva</button>
            <button id="btn-underline" class="btn btn-sm btn-secondary">Subrayar</button>
        </div>
    </div>

    <!-- Modal selección imagen -->
    <?php
    $images_cultura = [];
    $dir = __DIR__ . '/images-cultura/';
    if (is_dir($dir)) {
        $files = scandir($dir);
        foreach ($files as $f) {
            if ($f !== '.' && $f !== '..' && preg_match('/\.(png|jpg|jpeg|gif)$/i', $f)) {
                $images_cultura[] = 'images-cultura/' . $f;
            }
        }
    }
    ?>
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Seleccionar Imagen</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <label>Selecciona una imagen:</label>
                    <select id="image-select" class="form-control">
                        <?php foreach ($images_cultura as $img): ?>
                            <option value="<?= $img ?>"><?= basename($img) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div id="image-preview" style="margin-top:10px;text-align:center;">
                        <img src="<?= $images_cultura[0] ?? '' ?>" id="preview-img" style="max-width:100%;height:auto;">
                    </div>
                    <hr>
                    <label>O subir nueva imagen:</label>
                    <input type="file" id="upload-image">
                </div>
                <div class="modal-footer">
                    <button type="button" id="confirm-add-image" class="btn btn-primary">Agregar Imagen</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="css/bootstrap/js/jquery-3.3.1.min.js"></script>
    <script src="css/bootstrap/js/popper.min.js"></script>
    <script src="css/bootstrap/js/bootstrap-4.3.1.js"></script>

    <script>
        let culturaData = <?= json_encode($js_items, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;
        let originalData = JSON.parse(JSON.stringify(culturaData));
        let editing = false;
        let selectedTextBox = null;
        let uploadedImage = null;
        let availableImages = <?= json_encode($images_cultura, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;

        function createElement(item, index) {
            let el;
            if (item.type === "image") {
                el = $(`
            <div class="draggable ${editing ? "editing" : ""}" data-index="${index}" 
                style="top:${item.top}px; left:${item.left}px;">
                <button class="delete-btn">×</button>
                <img src="${item.src}" style="width:${item.width}px; height:${item.height}px;">
                <div class="resize-handle"></div>
            </div>`);
            } else {
                el = $(`
            <div class="draggable text-box ${editing ? "editing" : ""}" data-index="${index}"
                style="top:${item.top}px; left:${item.left}px;
                font-family:${item.font};
                font-size:${item.size};
                color:${item.color};
                font-weight:${item.bold ? "bold" : "normal"};
                font-style:${item.italic ? "italic" : "normal"};
                text-decoration:${item.underline ? "underline" : "none"};">
                
                <div class="text-content" contenteditable="${editing}">${item.text}</div>
                <button class="delete-btn" type="button">×</button>
            </div>`);
            }

            $("#editor").append(el);
            initElement(el);

            if (item.type === "text") {
                // Captura solo el contenido del div.text-content (sin el botón)
                el.find(".text-content").on("input", function() {
                    let idx = parseInt($(this).closest(".draggable").attr("data-index"));
                    culturaData[idx].text = $(this).html(); // usa .html() para conservar saltos de línea
                });
            }
        }

        function initElement(el) {
            el.on("mousedown", function(e) {
                if (!editing) return;
                if ($(e.target).hasClass("resize-handle")) return;
                const dragItem = $(this);
                let startX = e.clientX,
                    startY = e.clientY;
                const origX = dragItem.position().left,
                    origY = dragItem.position().top;
                $(document).on("mousemove.drag", function(e) {
                    let dx = e.clientX - startX,
                        dy = e.clientY - startY;
                    dragItem.css({
                        left: origX + dx,
                        top: origY + dy
                    });
                });
                $(document).on("mouseup.drag", function() {
                    $(document).off("mousemove.drag mouseup.drag");
                    let idx = parseInt(dragItem.attr("data-index"));
                    culturaData[idx].left = dragItem.position().left;
                    culturaData[idx].top = dragItem.position().top;
                });
            });

            el.find(".delete-btn").on("click", function(e) {
                if (!editing) return;
                e.stopPropagation();
                let idx = parseInt(el.attr("data-index"));
                culturaData.splice(idx, 1);
                renderEditorFromData();
            });

            el.find(".resize-handle").on("mousedown", function(e) {
                if (!editing) return;
                e.stopPropagation();
                const parent = $(this).parent();
                let startX = e.clientX,
                    startY = e.clientY;
                const img = parent.find("img"),
                    startWidth = img.width(),
                    startHeight = img.height();
                $(document).on("mousemove.resize", function(e) {
                    let dx = e.clientX - startX,
                        dy = e.clientY - startY;
                    img.css({
                        width: startWidth + dx,
                        height: startHeight + dy
                    });
                    let idx = parseInt(parent.attr("data-index"));
                    culturaData[idx].width = startWidth + dx;
                    culturaData[idx].height = startHeight + dy;
                });
                $(document).on("mouseup.resize", function() {
                    $(document).off("mousemove.resize mouseup.resize");
                });
            });

            el.on("click", function(e) {
                if (!editing) return;
                if ($(this).hasClass("text-box")) {
                    selectedTextBox = $(this);
                    $("#text-editor-panel").show();
                    let idx = parseInt($(this).attr("data-index"));
                    $("#font-select").val(culturaData[idx].font);
                    $("#size-select").val(culturaData[idx].size);
                    $("#color-select").val(culturaData[idx].color);
                    $("#btn-bold").toggleClass("active", culturaData[idx].bold);
                    $("#btn-italic").toggleClass("active", culturaData[idx].italic);
                    $("#btn-underline").toggleClass("active", culturaData[idx].underline);
                }
            });
        }

        function renderEditorFromData() {
            $("#editor").empty();
            culturaData.forEach((item, index) => createElement(item, index));
        }

        $(document).ready(function() {
            renderEditorFromData();

            $("#edit-btn").click(function() {
                editing = true;
                originalData = JSON.parse(JSON.stringify(culturaData));
                renderEditorFromData();
                $("#edit-btn").hide();
                $("#save-btn,#cancel-btn,#add-image-btn,#add-text-btn").show();
            });

            $("#save-btn").click(function() {
                editing = false;
                renderEditorFromData();
                $("#text-editor-panel").hide();
                $("#edit-btn").show();
                $("#save-btn,#cancel-btn,#add-image-btn,#add-text-btn").hide();

                $.ajax({
                    url: 'guardar_cultura.php',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(culturaData),
                    success: function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Guardado exitosamente',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error al guardar',
                            text: xhr.responseText || 'Ocurrió un problema al guardar'
                        });
                    }
                });
            });

            $("#cancel-btn").click(function() {
                editing = false;
                culturaData = JSON.parse(JSON.stringify(originalData));
                renderEditorFromData();
                $("#text-editor-panel").hide();
                $("#edit-btn").show();
                $("#save-btn,#cancel-btn,#add-image-btn,#add-text-btn").hide();
            });

            $("#add-text-btn").click(function() {
                culturaData.push({
                    type: "text",
                    text: "Nuevo Texto",
                    top: 100,
                    left: 100,
                    font: "Arial",
                    size: "14px",
                    color: "#000000",
                    bold: false,
                    italic: false,
                    underline: false
                });
                renderEditorFromData();
            });

            $("#add-image-btn").click(function() {
                $("#imageModal").modal("show");
                loadImageList();
            });

            function loadImageList() {
                $("#image-select").empty();
                availableImages.forEach(img => $("#image-select").append(`<option value="${img}">${img}</option>`));
                $("#image-select").trigger("change");
            }

            $("#image-select").change(function() {
                $("#preview-img").attr("src", $(this).val());
            });

            $("#upload-image").change(function(e) {
                let file = e.target.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(ev) {
                        uploadedImage = ev.target.result;
                        $("#preview-img").attr("src", uploadedImage);
                    };
                    reader.readAsDataURL(file);
                }
            });

            $("#confirm-add-image").click(function() {
                let src = uploadedImage ? uploadedImage : $("#image-select").val();
                if (src) {
                    culturaData.push({
                        type: "image",
                        src: src,
                        top: 100,
                        left: 100,
                        width: 150,
                        height: 150
                    });
                    renderEditorFromData();
                }
                $("#imageModal").modal("hide");
                uploadedImage = null;
            });

            $("#font-select").on("change", function() {
                if (selectedTextBox) {
                    let idx = parseInt(selectedTextBox.attr("data-index"));
                    selectedTextBox.css("font-family", $(this).val());
                    culturaData[idx].font = $(this).val();
                }
            });
            $("#size-select").on("change", function() {
                if (selectedTextBox) {
                    let idx = parseInt(selectedTextBox.attr("data-index"));
                    selectedTextBox.css("font-size", $(this).val());
                    culturaData[idx].size = $(this).val();
                }
            });
            $("#color-select").on("change", function() {
                if (selectedTextBox) {
                    let idx = parseInt(selectedTextBox.attr("data-index"));
                    selectedTextBox.css("color", $(this).val());
                    culturaData[idx].color = $(this).val();
                }
            });
            $("#btn-bold").click(function() {
                if (!selectedTextBox) return;
                let idx = parseInt(selectedTextBox.attr("data-index"));
                culturaData[idx].bold = !culturaData[idx].bold;
                selectedTextBox.css("font-weight", culturaData[idx].bold ? "bold" : "normal");
                $(this).toggleClass("active");
            });
            $("#btn-italic").click(function() {
                if (!selectedTextBox) return;
                let idx = parseInt(selectedTextBox.attr("data-index"));
                culturaData[idx].italic = !culturaData[idx].italic;
                selectedTextBox.css("font-style", culturaData[idx].italic ? "italic" : "normal");
                $(this).toggleClass("active");
            });
            $("#btn-underline").click(function() {
                if (!selectedTextBox) return;
                let idx = parseInt(selectedTextBox.attr("data-index"));
                culturaData[idx].underline = !culturaData[idx].underline;
                selectedTextBox.css("text-decoration", culturaData[idx].underline ? "underline" : "none");
                $(this).toggleClass("active");
            });
        });
    </script>

</body>

</html>
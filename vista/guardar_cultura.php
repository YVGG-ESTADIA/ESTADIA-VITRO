<?php
session_start();
include '../modelo/config.php';

if (!isset($_SESSION['usuario'])) {
    http_response_code(403);
    echo json_encode(["error" => "No autorizado"]);
    exit;
}

// Recibe datos JSON
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !is_array($data)) {
    http_response_code(400);
    echo json_encode(["error" => "Datos inválidos"]);
    exit;
}

try {
    // Carpeta para guardar imágenes subidas
    $uploadDir = __DIR__ . '/images-cultura/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    // Limpiar tabla
    $pdo->exec("TRUNCATE TABLE cultura");

    $stmt = $pdo->prepare("INSERT INTO cultura 
        (tipo, contenido, top, `left`, width, height, `font`, size, color, bold, italic, underline)
        VALUES (:tipo, :contenido, :top, :left, :width, :height, :font, :size, :color, :bold, :italic, :underline)");

    foreach ($data as $item) {
        $contenido = $item['type'] === 'text' ? $item['text'] : $item['src'];

        // Si es imagen en base64, guardarla en images-cultura
        if ($item['type'] === 'image' && str_starts_with($contenido, 'data:image/')) {
            preg_match('/^data:image\/(\w+);base64,/', $contenido, $tipo);
            $ext = $tipo[1] ?? 'png';
            $base64 = preg_replace('/^data:image\/\w+;base64,/', '', $contenido);
            $base64 = str_replace(' ', '+', $base64);
            $filename = 'img_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
            file_put_contents($uploadDir . $filename, base64_decode($base64));
            $contenido = 'images-cultura/' . $filename;
        }

        $stmt->execute([
            ':tipo' => $item['type'],
            ':contenido' => $contenido,
            ':top' => $item['top'],
            ':left' => $item['left'],
            ':width' => $item['type'] === 'image' ? $item['width'] : null,
            ':height' => $item['type'] === 'image' ? $item['height'] : null,
            ':font' => $item['type'] === 'text' ? $item['font'] : null,
            ':size' => $item['type'] === 'text' ? $item['size'] : null,
            ':color' => $item['type'] === 'text' ? $item['color'] : null,
            ':bold' => $item['type'] === 'text' ? ($item['bold'] ? 1 : 0) : null,
            ':italic' => $item['type'] === 'text' ? ($item['italic'] ? 1 : 0) : null,
            ':underline' => $item['type'] === 'text' ? ($item['underline'] ? 1 : 0) : null
        ]);
    }

    echo json_encode(["success" => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}

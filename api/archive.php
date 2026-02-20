<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$action = isset($_GET['action']) ? $_GET['action'] : '';
$baseDir = __DIR__ . '/../archives/';

if (!file_exists($baseDir)) {
    mkdir($baseDir, 0777, true);
}

if ($action === 'save') {
    // Read input
    $input = json_decode(file_get_contents("php://input"), true);

    if (!isset($input['name']) || !isset($input['data'])) {
        echo json_encode(["status" => "error", "message" => "Nama arsip atau data tidak valid."]);
        exit;
    }

    $filename = preg_replace('/[^a-zA-Z0-9 _-]/', '', $input['name']); // Sanitize
    $filepath = $baseDir . $filename . '.json';

    // Save Data
    $saveData = [
        "meta" => [
            "created_at" => date("Y-m-d H:i:s"),
            "name" => $input['name'],
            "total_rows" => count($input['data'])
        ],
        "data" => $input['data']
    ];

    if (file_put_contents($filepath, json_encode($saveData, JSON_PRETTY_PRINT))) {
        echo json_encode(["status" => "success", "message" => "Arsip berhasil disimpan."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal menyimpan file arsip."]);
    }

} elseif ($action === 'list') {
    $files = glob($baseDir . '*.json');
    $list = [];

    foreach ($files as $file) {
        $content = json_decode(file_get_contents($file), true);
        $list[] = [
            "filename" => basename($file), // e.g. "Februari 2026.json"
            "name" => $content['meta']['name'] ?? basename($file, '.json'),
            "date" => $content['meta']['created_at'] ?? date("Y-m-d H:i:s", filemtime($file)),
            "rows" => $content['meta']['total_rows'] ?? 0
        ];
    }

    // Sort by date new to old
    usort($list, function ($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });

    echo json_encode(["status" => "success", "data" => $list]);

} elseif ($action === 'read') {
    $file = isset($_GET['file']) ? $_GET['file'] : '';
    $filepath = $baseDir . basename($file); // Security: ensure basename

    if (file_exists($filepath)) {
        echo file_get_contents($filepath);
    } else {
        echo json_encode(["status" => "error", "message" => "File tidak ditemukan."]);
    }

} else {
    echo json_encode(["status" => "error", "message" => "Action tidak valid."]);
}
?>
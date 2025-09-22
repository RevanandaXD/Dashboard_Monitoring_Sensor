<?php
// api_receive.php
header('Content-Type: application/json');
require_once 'db.php';

// ambil payload JSON/body
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    $data = $_POST;
}

// validasi sederhana
if (!isset($data['temperature']) || !isset($data['humidity']) || !isset($data['ldr'])) {
    http_response_code(400);
    echo json_encode(['status'=>'error','msg'=>'Missing fields. Required: temperature, humidity, ldr']);
    exit;
}

$temperature = floatval($data['temperature']);
$humidity = floatval($data['humidity']);
$ldr_raw = intval($data['ldr']);

// fungsi mapping kondisi LDR (samakan dengan threshold di ESP32)
function map_ldr_condition($v) {
    // Sesuaikan threshold kalau perlu
    if ($v > 2000) return 'Gelap';
    if ($v > 1500) return 'Redup';
    if ($v > 1000) return 'Cerah';
    return 'Terang';
}

$kondisi = map_ldr_condition($ldr_raw);

try {
    // simpan suhu
    $stmt = $pdo->prepare("INSERT INTO sensor_suhu (temperature, humidity) VALUES (?, ?)");
    $stmt->execute([$temperature, $humidity]);

    // simpan ldr
    $stmt = $pdo->prepare("INSERT INTO sensor_lumen (ldr_raw, kondisi) VALUES (?, ?)");
    $stmt->execute([$ldr_raw, $kondisi]);

    echo json_encode(['status'=>'ok','msg'=>'Data saved','kondisi'=>$kondisi]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status'=>'error','msg'=>'DB error: '.$e->getMessage()]);
}

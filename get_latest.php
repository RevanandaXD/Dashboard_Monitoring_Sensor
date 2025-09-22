<?php
header('Content-Type: application/json');
require_once 'db.php';

try {
    $s = $pdo->query("SELECT * FROM sensor_suhu ORDER BY ts DESC LIMIT 1")->fetch();
    $l = $pdo->query("SELECT * FROM sensor_lumen ORDER BY ts DESC LIMIT 1")->fetch();

    echo json_encode([
        'status' => 'ok',
        'suhu' => $s ?: null,
        'ldr' => $l ?: null
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status'=>'error','msg'=>$e->getMessage()]);
}

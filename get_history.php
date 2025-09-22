<?php
header("Content-Type: application/json");
require_once "db.php";

$limit = isset($_GET["limit"]) ? intval($_GET["limit"]) : 50;

try {
  $stmt = $pdo->prepare("SELECT ts, temperature, humidity FROM sensor_suhu ORDER BY ts DESC LIMIT ?");
  $stmt->bindParam(1, $limit, PDO::PARAM_INT);
  $stmt->execute();
  $suhuRows = $stmt->fetchAll();

  $stmt = $pdo->prepare("SELECT ts, ldr_raw FROM sensor_lumen ORDER BY ts DESC LIMIT ?");
  $stmt->bindValue(1, $limit, PDO::PARAM_INT);
  $stmt->execute();
  $ldrRows = $stmt->fetchAll();

  echo json_encode([
    'suhu' => array_reverse($suhuRows),
    'ldr' => array_reverse($ldrRows)
  ]);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['status'=>'error','msg'=>$e->getMessage()]);
}
?>
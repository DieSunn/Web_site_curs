<?php
// delete_record.php
session_start();
include 'db_connect.php'; // Подключение к базе данных
header('Content-Type: application/json');

$postData = json_decode(file_get_contents('php://input'), true);


$tab = $_GET['tab'] ?? 'tab-1';

$primaryKey=getPk($tab);
$tableName=getTable($tab);
$record = $postData['record'];
$pdo = getDbConnection($_SESSION['username'], $_SESSION['password']);

try {
  $sql = "DELETE FROM $tableName WHERE $primaryKey = :id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(['id' => $record[$primaryKey]]);
  
  echo json_encode(['status' => 'success', 'message' => 'Record deleted successfully']);
} catch (PDOException $e) {
  http_response_code(500); // Установка HTTP-статуса сервера в случае ошибки
  echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>

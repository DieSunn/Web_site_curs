<?php
session_start();
include 'db_connect.php';
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['status' => 'error', 'message' => 'Нет данных']);
    exit;
}

$tab = $_GET['tab'] ?? 'tab-1';

$tableName=getTable($tab);

$columns = array_keys($data);
$values = array_values($data);
$pdo = getDbConnection($_SESSION['username'], $_SESSION['password']);

try {
    $placeholders = implode(', ', array_fill(0, count($columns), '?'));
    $columnsFormatted = implode(', ', $columns);
    $sql = "INSERT INTO $tableName ($columnsFormatted) VALUES ($placeholders)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($values);
    echo json_encode(['status' => 'success', 'message' => 'Record added successfully.']);
} catch (PDOException $e) {
    http_response_code(500); // Установка HTTP-статуса сервера в случае ошибки
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>

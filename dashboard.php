<!-- dashboard.php -->
<?php
session_start(); // Начало сессии
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
include 'db_connect.php'; // Подключение к базе данных

// Проверка, аутентифицирован ли пользователь
if (!isset($_SESSION['username'])) {
  header('Location: login.php');
  exit();
}

$username = $_SESSION['username'];
$password = $_SESSION['password']; 

try {
  $pdo = getDbConnection($username, $password);

  // Начало буферизации вывода
  ob_start();
?>

<div class="background">
</div>
  <div class="search-box">
    <input type="text" id="search-input" class="search-input" placeholder="Поиск..." disabled>
    <button class="search-button" onclick="executeSearch()">
      <i class="fas fa-search custom-icon" aria-hidden="true"></i>
    </button>
  </div>
  <!-- Вкладки для навигации -->
  <ul class="tabs">
    <li class="tab-link" data-tab="tab-1">Материалы</li>
    <li class="tab-link" data-tab="tab-2">Наличие</li>
    <li class="tab-link" data-tab="tab-3">Заказы</li>
  </ul>

  <!-- Контент для каждой вкладки -->
  <div id="tab-1" class="tab-content">
  </div>

  <div id="tab-2" class="tab-content">
  </div>

  <div id="tab-3" class="tab-content">
  </div>
  </div>



<?php
  $content = ob_get_clean();
} catch (PDOException $e) {
  die("Ошибка подключения: " . $e->getMessage());
}

$title = 'Домашняя страница';
$activePage = 'dashboard';
include 'base.php';
?>
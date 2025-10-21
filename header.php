<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>社區活動報名系統</title>

  <!-- 字型與樣式 -->
  <link rel="stylesheet" href="custom.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- ✅ DataTables CSS (Bootstrap 5 樣式) -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
</head>

<body>
  <nav class="navbar navbar-expand-lg custom-bg">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      
      <!-- 左側：系統名稱 + 導覽項目 -->
      <div class="d-flex align-items-center">
        <a class="navbar-brand text-white me-3" href="index.php">
          社區活動報名系統
        </a>

        <ul class="navbar-nav flex-row">
          <li class="nav-item me-3">
            <a class="nav-link px-2 <?= ($current === 'index.php') ? 'active' : 'text-white'; ?>" href="index.php">首頁</a>
          </li>
          <li class="nav-item me-3">
            <a class="nav-link px-2 <?= ($current === 'status.php') ? 'active' : 'text-white'; ?>" href="status.php">迎新茶會</a>
          </li>
          <li class="nav-item me-3">
            <a class="nav-link px-2 <?= ($current === 'conference.php') ? 'active' : 'text-white'; ?>" href="conference.php">資管一日遊</a>
          </li>
          <li class="nav-item me-3">
            <a class="nav-link px-2 <?= ($current === 'job-js.php') ? 'active' : 'text-white'; ?>" href="job-js.php">求才資訊(js套件)</a>
          </li>
          <li class="nav-item me-3">
            <a class="nav-link px-2 <?= ($current === 'job-post.php') ? 'active' : 'text-white'; ?>" href="job-post.php">求才資訊(post)</a>
          </li>
        </ul>
      </div>

      <!-- 右側登入/登出 -->
      <div>
        <?php if (isset($_SESSION['username'])): ?>
          <a href="logout.php" class="nav-link text-white">登出</a>
        <?php else: ?>
          <a href="login.php" class="nav-link text-white">登入</a>
        <?php endif; ?>
      </div>
    </div>
  </nav>

  <!-- ✅ jQuery（一定要在 DataTables 前） -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <!-- ✅ Bootstrap + DataTables -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>


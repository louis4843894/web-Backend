<?php
session_start();
require_once "db.php";
require_once "header.php";

// 🚫 僅管理員可新增
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'M') {
  echo '<div class="container mt-5"><div class="alert alert-danger text-center fs-5">❌ 您沒有新增活動的權限！</div></div>';
  require_once "footer.php"; exit;
}

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $title = trim($_POST["title"] ?? "");
  $description = trim($_POST["description"] ?? "");
  $pdate = trim($_POST["pdate"] ?? ""); // 來自 datetime-local: 2025-11-05T14:00

  // 轉 MySQL datetime
  if ($pdate !== "" && strpos($pdate, 'T') !== false) {
    $pdate = str_replace('T', ' ', $pdate);
    if (strlen($pdate) === 16) $pdate .= ':00';
  }

  if ($title && $description && $pdate) {
    $sql = "INSERT INTO activity (title, description, pdate) VALUES (?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
      mysqli_stmt_bind_param($stmt, "sss", $title, $description, $pdate);
      $ok = mysqli_stmt_execute($stmt);
      if ($ok) { header("Location: index.php"); exit; }
      else { $msg = "❌ 新增失敗：" . mysqli_error($conn); }
    }
  } else {
    $msg = "⚠️ 所有欄位皆為必填！";
  }
}
?>
<div class="container mt-4">
  <h3 class="mb-4">新增活動</h3>

  <?php if ($msg): ?>
    <div class="alert alert-warning"><?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>

  <form action="index-insert.php" method="post">
    <div class="mb-3 row">
      <label for="_title" class="col-sm-2 col-form-label">標題</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" name="title" id="_title" placeholder="輸入活動標題" required>
      </div>
    </div>

    <div class="mb-3 row">
      <label for="_description" class="col-sm-2 col-form-label">內容</label>
      <div class="col-sm-10">
        <textarea class="form-control" name="description" id="_description" rows="6" placeholder="請輸入活動內容" required></textarea>
      </div>
    </div>

    <div class="mb-3 row">
      <label for="_pdate" class="col-sm-2 col-form-label">日期時間</label>
      <div class="col-sm-4">
        <input type="datetime-local" class="form-control" name="pdate" id="_pdate" required>
      </div>
    </div>

    <input class="btn btn-primary" type="submit" value="送出">
  </form>
</div>
<?php require_once "footer.php"; ?>

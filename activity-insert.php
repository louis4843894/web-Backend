<?php
session_start();
require_once "db.php";

// ✅ 不在最上面 require header，避免 header() 無法執行
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'M') {
  require_once "header.php";
  echo '<div class="container mt-5">
          <div class="alert alert-danger text-center fs-5">
            ❌ 您沒有新增活動的權限！
          </div>
        </div>';
  require_once "footer.php";
  exit;
}

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $title = trim($_POST["title"] ?? "");
  $description = trim($_POST["description"] ?? "");
  $pdate = trim($_POST["pdate"] ?? "");

  if ($title && $description && $pdate) {
    $sql = "INSERT INTO activity (title, description, pdate) VALUES (?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $title, $description, $pdate);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
      header("Location: activity.php");
      exit;
    } else {
      $msg = "❌ 新增失敗：" . mysqli_error($conn);
    }
  } else {
    $msg = "⚠️ 所有欄位皆為必填！";
  }
}

require_once "header.php";
?>

<div class="container mt-4">
  <h3 class="mb-4">新增活動</h3>

  <?php if ($msg): ?>
    <div class="alert alert-warning"><?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>

  <form action="activity-insert.php" method="post">
    <div class="mb-3 row">
      <label for="_title" class="col-sm-2 col-form-label">活動名稱</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" name="title" id="_title" placeholder="輸入活動名稱" required>
      </div>
    </div>

    <div class="mb-3 row">
      <label for="_description" class="col-sm-2 col-form-label">活動說明</label>
      <div class="col-sm-10">
        <textarea class="form-control" name="description" id="_description" rows="6" placeholder="請輸入活動說明" required></textarea>
      </div>
    </div>

    <div class="mb-3 row">
      <label for="_pdate" class="col-sm-2 col-form-label">活動日期</label>
      <div class="col-sm-4">
        <input type="datetime-local" class="form-control" name="pdate" id="_pdate" required>
      </div>
    </div>

    <div class="text-center">
      <input type="submit" value="送出" class="btn btn-primary px-4">
      <a href="activity.php" class="btn btn-secondary px-4">返回</a>
    </div>
  </form>
</div>

<?php
require_once "footer.php";
?>

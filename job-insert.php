<?php
session_start();
require_once "db.php";
require_once "header.php";

// 🚫 非管理員直接跳提示頁
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'M') {
  echo '<div class="container mt-5">
          <div class="alert alert-danger text-center fs-5">
            ❌ 您沒有新增職缺的權限！
          </div>
        </div>';
  require_once "footer.php";
  exit;
}

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $company = trim($_POST["company"] ?? "");
  $content = trim($_POST["content"] ?? "");
  $pdate = trim($_POST["pdate"] ?? "");

  if ($company && $content && $pdate) {
    $sql = "INSERT INTO job (company, content, pdate) VALUES (?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
      mysqli_stmt_bind_param($stmt, "sss", $company, $content, $pdate);
      $result = mysqli_stmt_execute($stmt);
      if ($result) {
        header("Location: job-post.php");
        exit;
      } else {
        $msg = "❌ 新增失敗：" . mysqli_error($conn);
      }
    }
  } else {
    $msg = "⚠️ 所有欄位皆為必填！";
  }
}
?>

<div class="container mt-4">
  <h3 class="mb-4">新增職缺</h3>

  <?php if ($msg): ?>
    <div class="alert alert-warning"><?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>

  <form action="job-insert.php" method="post">
    <div class="mb-3 row">
      <label for="_company" class="col-sm-2 col-form-label">求才廠商</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" name="company" id="_company" placeholder="輸入公司名稱" required>
      </div>
    </div>

    <div class="mb-3 row">
      <label for="_content" class="col-sm-2 col-form-label">求才內容</label>
      <div class="col-sm-10">
        <textarea class="form-control" name="content" id="_content" rows="6" placeholder="請輸入求才內容" required></textarea>
      </div>
    </div>

    <div class="mb-3 row">
      <label for="_pdate" class="col-sm-2 col-form-label">刊登日期</label>
      <div class="col-sm-4">
        <input type="datetime-local" class="form-control" name="pdate" id="_pdate" required>
      </div>
    </div>

    <div class="text-center">
      <input type="submit" value="送出" class="btn btn-primary px-4">
      <a href="job-post.php" class="btn btn-secondary px-4">返回</a>
    </div>
  </form>
</div>

<?php
require_once "footer.php";
?>

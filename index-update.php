<?php
session_start();
require_once "db.php";
require_once "header.php";

// 🚫 僅管理員可編輯
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'M') {
  echo '<div class="container mt-5"><div class="alert alert-danger text-center fs-5">❌ 您沒有修改活動的權限！</div></div>';
  require_once "footer.php"; exit;
}

$msg = "";

// 參數初始化
$action = $_GET['action'] ?? '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$title = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';
$pdate = $_POST['pdate'] ?? ''; // 會是 datetime-local 值

if ($action === "confirmed") {
  if ($pdate !== '' && strpos($pdate, 'T') !== false) {
    $pdate = str_replace('T', ' ', $pdate);
    if (strlen($pdate) === 16) $pdate .= ':00';
  }

  if ($id > 0 && $title !== '' && $description !== '' && $pdate !== '') {
    $sql="UPDATE activity SET title=?, description=?, pdate=? WHERE id=?";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "sssi", $title, $description, $pdate, $id);
    $ok = mysqli_stmt_execute($stmt);
    if ($ok) { header("Location: index.php"); exit; }
    else { $msg = "❌ 修改失敗：" . mysqli_error($conn); }
  } else {
    $msg = "⚠️ 所有欄位皆為必填！";
  }
} else {
  // 初次載入：撈資料帶入表單
  if ($id > 0) {
    $sql = "SELECT title, description, pdate FROM activity WHERE id=?";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $title, $description, $pdate);
    if (!mysqli_stmt_fetch($stmt)) {
      $msg = "⚠️ 查無此活動。";
      $title = $description = $pdate = '';
    }
    mysqli_stmt_close($stmt);
  } else {
    $msg = "⚠️ 缺少有效的 id。";
  }
}
?>
<div class="container">
  <h3 class="mb-4 mt-4">編輯活動</h3>
  <?php if ($msg): ?><div class="alert alert-warning"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

  <form action="index-update.php?id=<?= (int)$id ?>&action=confirmed" method="post">
    <div class="mb-3 row">
      <label for="_title" class="col-sm-2 col-form-label">標題</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" name="title" id="_title"
               value="<?= htmlspecialchars($title, ENT_QUOTES) ?>" required>
      </div>
    </div>

    <div class="mb-3 row">
      <label for="_description" class="col-sm-2 col-form-label">內容</label>
      <div class="col-sm-10">
        <textarea class="form-control" name="description" id="_description" rows="8" required><?= htmlspecialchars($description) ?></textarea>
      </div>
    </div>

    <div class="mb-3 row">
      <label for="_pdate" class="col-sm-2 col-form-label">日期時間</label>
      <div class="col-sm-4">
        <input type="datetime-local" class="form-control" name="pdate" id="_pdate"
               value="<?= $pdate ? htmlspecialchars(str_replace(' ', 'T', substr($pdate,0,16)), ENT_QUOTES) : '' ?>" required>
      </div>
    </div>

    <input class="btn btn-primary" type="submit" value="送出">
  </form>
</div>
<?php require_once "footer.php"; ?>

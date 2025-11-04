<?php
session_start();
require_once "db.php";
require_once "header.php";

// 🚫 非管理員直接跳提示頁
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'M') {
  echo '<div class="container mt-5">
          <div class="alert alert-danger text-center fs-5">
            ❌ 您沒有修改職缺的權限！
          </div>
        </div>';
  require_once "footer.php";
  exit;
}

// ---- 安全初始化請求參數（避免 Undefined notices）----
$action  = $_GET['action']  ?? '';                // 來源為網址列 ?action=confirmed
$id      = isset($_GET['id']) ? (int)$_GET['id'] : 0; // 給表單 action 用，且確保是整數
$company = $_POST['company'] ?? '';               // 首次載入時為空字串即可
$content = $_POST['content'] ?? '';
// -------------------------------------------------
$msg = "";
    if ($action=="confirmed"){
  // update data
  $id = (int)($_GET["id"] ?? 0);
  $company = $_POST["company"] ?? '';
  $content = $_POST["content"] ?? '';

  $sql="UPDATE job SET company=?, content=? WHERE id=?";
  $stmt = mysqli_stmt_init($conn);
  mysqli_stmt_prepare($stmt, $sql);
  mysqli_stmt_bind_param($stmt, "ssi", $company, $content, $id);
  $result = mysqli_stmt_execute($stmt);

  if ($result) {
    // 這裡最好在未輸出任何 HTML 前就 redirect（若未來遇到 header already sent 再處理）
    header("Location: job-post.php");
    mysqli_close($conn);
    exit;
  } else {
    $msg = "❌ 新增失敗：" . mysqli_error($conn);
  }
}
else {
  // 🔎 初次載入或非 confirmed：用 id 撈舊資料填表單
  if ($id > 0) {
    $sql = "SELECT company, content FROM job WHERE id=?";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $company, $content);
    if (!mysqli_stmt_fetch($stmt)) {
      $msg = "⚠️ 查無此職缺（id=$id）。";
      // 查不到就讓 $company/$content 維持空字串
    }
    mysqli_stmt_close($stmt);
  } else {
    $msg = "⚠️ 缺少有效的 id。";
  }
}

?>

<div class="container mt-4">
  <h3 class="mb-4">修改職缺</h3>

<div class="container">
  <form action="job-update.php?id=<?=$id?>&action=confirmed" method="post">
  <div class="mb-3 row">
    <label for="_company" class="col-sm-2 col-form-label">求才廠商</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="company" id="_company" 
        placeholder="公司名稱" value="<?=$company?>" required>
    </div>
  </div>
  <div class="mb-3">
    <label for="_content" class="form-label">求才內容</label>
    <textarea class="form-control" id="_content" name="content" 
      rows="10" required><?=$content?></textarea>
  </div>
  <input class="btn btn-primary" type="submit" value="送出">
  </form>
</div>
</div>
<?php
require_once "footer.php";
?>
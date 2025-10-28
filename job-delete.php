<?php
session_start();
require_once "db.php";
require_once "header.php";

// 🚫 未登入
if (!isset($_SESSION['username'])) {
  echo '<div class="container mt-5">
          <div class="alert alert-danger text-center fs-5">⚠️ 請先登入再進行操作。</div>
        </div>';
  require_once "footer.php";
  exit;
}

// ✅ 抓取要刪除的職缺 ID
$id = $_GET["id"] ?? "";

if (!$id) {
  echo '<div class="container mt-5">
          <div class="alert alert-warning text-center fs-5">⚠️ 未指定要刪除的職缺。</div>
        </div>';
  require_once "footer.php";
  exit;
}

// 🔹 查詢該筆職缺資料
$id_safe = mysqli_real_escape_string($conn, $id);
$sql = "SELECT * FROM job WHERE id = '$id_safe'";
$result = mysqli_query($conn, $sql);
$target = mysqli_fetch_assoc($result);

if (!$target) {
  echo '<div class="container mt-5">
          <div class="alert alert-warning text-center fs-5">⚠️ 查無此職缺。</div>
        </div>';
  require_once "footer.php";
  exit;
}


// 🚫 非管理員只能看資料，不允許刪除
if ($_SESSION['role'] !== 'M') {
  echo "<div class='alert alert-danger text-center fs-5 mt-4'>
          ❌ 您沒有刪除職缺的權限！
        </div>
        <div class='text-center'>
          <a href='job-post.php' class='btn btn-secondary px-4 mt-3'>返回</a>
        </div>";
  require_once "footer.php";
  exit;
}

// ✅ 管理員 → 顯示刪除確認表單
if (!isset($_POST['confirm'])) {
  // 🔹 顯示職缺資料表格
echo "<div class='container mt-4'>
        <h3 class='text-center mb-4 text-danger'>⚠️ 確定要刪除這筆職缺嗎？</h3>";

echo "<table class='table table-bordered table-striped mx-auto' style='max-width:800px'>
        <thead class='table-secondary'>
          <tr>
            <th>求才廠商</th>
            <th>求才內容</th>
            <th>刊登日期</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>" . htmlspecialchars($target['company']) . "</td>
            <td>" . nl2br(htmlspecialchars($target['content'])) . "</td>
            <td>" . htmlspecialchars($target['pdate']) . "</td>
          </tr>
        </tbody>
      </table>";
  echo "<form method='post' class='text-center mt-4'>
          <input type='hidden' name='confirm' value='yes'>
          <button type='submit' class='btn btn-danger px-4 me-2'>確認刪除</button>
          <a href='job-post.php' class='btn btn-secondary px-4'>取消</a>
        </form>
      </div>";
} 
// ✅ 確認刪除後執行刪除
else {
  $delete_sql = "DELETE FROM job WHERE id = '$id_safe'";
  $result = mysqli_query($conn, $delete_sql);

  echo "<div class='container mt-5'>";
  if ($result) {
    echo "<div class='alert alert-success text-center fs-5'>
            ✅ 已成功刪除職缺：「" . htmlspecialchars($target['company']) . "」
          </div>
          <script>
            setTimeout(() => { window.location='job-post.php'; }, 1500);
          </script>";
  } else {
    echo "<div class='alert alert-danger text-center fs-5'>
            ❌ 刪除失敗：" . mysqli_error($conn) . "
          </div>";
  }
  echo "</div>";
}

require_once "footer.php";
mysqli_close($conn);
?>

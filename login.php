<?php
session_start();
require_once "db.php";
require_once "header.php"; // ✅ 共用導覽列

$redirect = $_GET['redirect'] ?? 'index.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $account = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  // 防止 SQL Injection
  $account = mysqli_real_escape_string($conn, $account);
  $password = mysqli_real_escape_string($conn, $password);

  // 查詢帳號
  $sql = "SELECT * FROM user WHERE account = '$account'";
  $result = mysqli_query($conn, $sql);

  if ($result && mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);
    if ($row['password'] === $password) {
      $_SESSION['username'] = $row['account'];
      $_SESSION['name'] = $row['name'];
      $_SESSION['role'] = ($row['role'] === 'T') ? '教師' : '學生';
      header("Location: $redirect");
      exit;
    } else {
      $message = "❌ 密碼錯誤，請再試一次。";
    }
  } else {
    $message = "❌ 查無此帳號。";
  }

  mysqli_close($conn);
}
?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow-sm">
        <div class="card-header text-center bg-primary text-white">
          <h4 class="mb-0">登入系統</h4>
        </div>
        <div class="card-body">
          <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-warning text-center p-2">
              <?= htmlspecialchars($_GET['msg']) ?>
            </div>
          <?php endif; ?>

          <?php if ($message): ?>
            <div class="alert alert-danger text-center p-2">
              <?= htmlspecialchars($message) ?>
            </div>
          <?php endif; ?>

          <form method="POST">
            <div class="mb-3">
              <label for="username" class="form-label">帳號：</label>
              <input type="text" id="username" name="username" class="form-control" required>
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">密碼：</label>
              <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">登入</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once "footer.php"; ?>

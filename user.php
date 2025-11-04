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

$username = $_SESSION['username'];

// 讀取使用者基本資料
$user = null;
$pwd_in_db = '';

if ($stmt = mysqli_prepare($conn, "SELECT account, name, password FROM user WHERE account=?")) {
  mysqli_stmt_bind_param($stmt, "s", $username);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $acc, $name, $pwd_in_db);
  if (mysqli_stmt_fetch($stmt)) {
    $user = ['account' => $acc, 'name' => $name];
  }
  mysqli_stmt_close($stmt);
}

if (!$user) {
  echo '<div class="container mt-5">
          <div class="alert alert-warning text-center fs-5">⚠️ 查無此使用者資料。</div>
        </div>';
  require_once "footer.php";
  exit;
}

$msg = "";
$ok  = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $old_password     = trim($_POST['old_password'] ?? '');
  $new_password     = trim($_POST['new_password'] ?? '');
  $confirm_password = trim($_POST['confirm_password'] ?? '');

  // 基本檢查
  if ($old_password === '' || $new_password === '' || $confirm_password === '') {
    $msg = "⚠️ 所有欄位皆為必填！";
  } elseif ($new_password !== $confirm_password) {
    $msg = "⚠️ 新密碼與確認新密碼不一致。";
  } else {
    // 驗證舊密碼：同時相容 password_hash 與純文字/舊制
    $is_hashed = password_get_info($pwd_in_db)['algo'] !== 0; // true 代表是經 password_hash 儲存
    $old_ok = false;

    if ($is_hashed) {
      $old_ok = password_verify($old_password, $pwd_in_db);
    } else {
      // 純文字或舊制；與資料庫內容直接比對（也順帶容許 md5 舊資料）
      $old_ok = ($pwd_in_db === $old_password) || ($pwd_in_db === md5($old_password));
    }

    if (!$old_ok) {
      $msg = "⚠️ 舊密碼不正確。";
    } else {
      // 禁止新舊密碼相同（以使用者實際輸入的舊密碼來比）
      if ($new_password === $old_password) {
        $msg = "⚠️ 新密碼不可與舊密碼相同。";
      } else {
        // 檢查強度（可自行放寬/加嚴）
        if (strlen($new_password) < 3) {
          $msg = "⚠️ 新密碼長度至少需 3 個字元。";
        } else {
          // 依目前資料格式決定寫回方式：若原本是 hash，就用 hash；否則維持純文字（避免影響你現有登入邏輯）
          $new_to_save = $is_hashed ? password_hash($new_password, PASSWORD_DEFAULT) : $new_password;

          if ($stmt = mysqli_prepare($conn, "UPDATE user SET password=? WHERE account=?")) {
            mysqli_stmt_bind_param($stmt, "ss", $new_to_save, $username);
            $ok = mysqli_stmt_execute($stmt);
            $err = mysqli_error($conn);
            mysqli_stmt_close($stmt);

            if ($ok) {
              $msg = "✅ 密碼已成功更新！";
              // 更新記憶中的密碼字串，讓後續再次修改時檢查正確
              $pwd_in_db = $new_to_save;
            } else {
              $msg = "❌ 更新密碼時出錯：".$err;
            }
          } else {
            $msg = "❌ 資料庫操作失敗。";
          }
        }
      }
    }
  }
}
?>

<div class="container mt-5">
  <h3 class="mb-4">個人資料</h3>

  <?php if ($msg): ?>
    <div class="alert <?= $ok ? 'alert-success' : 'alert-warning' ?>"><?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>

  <form action="user.php" method="post" autocomplete="off">
    <div class="mb-3 row">
      <label for="account" class="col-sm-2 col-form-label">帳號</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" id="account"
               value="<?= htmlspecialchars($user['account']) ?>" disabled>
      </div>
    </div>

    <div class="mb-3 row">
      <label for="name" class="col-sm-2 col-form-label">姓名</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" id="name"
               value="<?= htmlspecialchars($user['name']) ?>" disabled>
      </div>
    </div>

    <hr class="my-4">

    <h5 class="mb-3">修改密碼</h5>

    <div class="mb-3 row">
      <label for="old_password" class="col-sm-2 col-form-label">舊密碼</label>
      <div class="col-sm-10">
        <input type="password" class="form-control" id="old_password" name="old_password"
               placeholder="請輸入舊密碼" required>
      </div>
    </div>

    <div class="mb-3 row">
      <label for="new_password" class="col-sm-2 col-form-label">新密碼</label>
      <div class="col-sm-10">
        <input type="password" class="form-control" id="new_password" name="new_password"
               placeholder="請輸入新密碼" required>
      </div>
    </div>

    <div class="mb-3 row">
      <label for="confirm_password" class="col-sm-2 col-form-label">確認新密碼</label>
      <div class="col-sm-10">
        <input type="password" class="form-control" id="confirm_password" name="confirm_password"
               placeholder="請再輸入一次新密碼" required>
      </div>
    </div>

    <input class="btn btn-primary" type="submit" value="送出">
  </form>
</div>

<?php require_once "footer.php"; ?>

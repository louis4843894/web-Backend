<?php
session_start();
if (!isset($_SESSION['username'])) {
  $redirect = basename($_SERVER['PHP_SELF']);
  header("Location: login.php?redirect=$redirect&msg=" . urlencode("請先登入以繼續"));
  exit;
}

include "header.php";

$file = 'freshers.json';
if (!file_exists($file)) file_put_contents($file, '[]');

$message = ''; // 提示訊息

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name   = $_SESSION['name'];
  $role   = $_SESSION['role'];
  $dinner = $_POST['dinner'] ?? '';
  $fee    = 0;

  // 若是教師 → 免費
  if ($role === '教師') {
    $message = "{$name}（老師），免繳費";
  } else {
    if ($dinner === '需要晚餐') {
      $fee = 60;
      $message = "{$name}，需要晚餐，請繳 {$fee} 元";
    } else {
      $message = "{$name}，不需晚餐，無需繳費。";
    }
  }

  $record = [
    'name'   => $name,
    'role'   => $role,
    'dinner' => $dinner,
    'fee'    => $fee,
    'time'   => date('Y-m-d H:i:s')
  ];

  $data = json_decode(file_get_contents($file), true);
  $data[] = $record;
  file_put_contents($file, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

// 讀取資料
$data = json_decode(file_get_contents($file), true);
?>
<main class="container my-4">
  <h2 class="mb-3">迎新茶會</h2>

  <!-- 提示訊息 -->
  <?php if ($message): ?>
    <div class="alert alert-primary text-center" role="alert">
      <?= htmlspecialchars($message) ?>
    </div>
  <?php endif; ?>

  <!-- 表單：未送出時顯示 -->
  <?php if (!$message): ?>
  <form method="POST" class="card shadow-sm mb-4">
    <div class="card-body">
      <h5 class="card-title mb-3">餐點需求</h5>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="dinner" id="need" value="需要晚餐" required>
        <label class="form-check-label" for="need">需要晚餐（60 元）</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="dinner" id="noneed" value="不需要晚餐" required>
        <label class="form-check-label" for="noneed">不需要晚餐</label>
      </div>
      <div class="mt-3">
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </div>
  </form>
  <?php endif; ?>
</main>
<?php include "footer.php"; ?>

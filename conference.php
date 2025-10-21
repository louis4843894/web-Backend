<?php
session_start();
if (!isset($_SESSION['username'])) {
  $redirect = basename($_SERVER['PHP_SELF']);
  header("Location: login.php?redirect=$redirect&msg=" . urlencode("請先登入以繼續"));
  exit;
}

include "header.php";

$file = 'camp.json';
if (!file_exists($file)) file_put_contents($file, '[]');

$message = ''; // 上方提示訊息

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name    = $_SESSION['name'];
  $role    = $_SESSION['role'];
  $options = $_POST['options'] ?? [];
  $total   = 0;

  // ✅ 若是教師，直接免繳費
  if ($role === '教師') {
    $total = 0;
    $message = "{$name}（老師），免繳費";
  } else {
    if (!empty($options)) {
      foreach ($options as $opt) {
        if ($opt === '上午場') $total += 150;
        if ($opt === '下午場') $total += 100;
        if ($opt === '午餐')   $total += 60;
      }
      $message = "{$name}，您要繳交 {$total} 元";
    } else {
      $message = "{$name}，未勾選任何項目，無需繳費。";
    }
  }

  // 寫入紀錄
  $record = [
    'name'    => $name,
    'role'    => $role,
    'options' => empty($options) ? '-' : implode(',', $options),
    'total'   => $total,
    'time'    => date('Y-m-d H:i:s')
  ];

  $data = json_decode(file_get_contents($file), true);
  $data[] = $record;
  file_put_contents($file, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

// 讀取紀錄
$data = json_decode(file_get_contents($file), true);
?>
<main class="container my-4">
  <h2 class="mb-3">資管一日營</h2>

  <!-- 顯示提示訊息 -->
  <?php if ($message): ?>
    <div class="alert alert-primary text-center" role="alert">
      <?= htmlspecialchars($message) ?>
    </div>
  <?php endif; ?>

  <!-- 表單：只有在未送出時顯示 -->
  <?php if (!$message): ?>
  <form method="POST" class="card shadow-sm mb-4">
    <div class="card-body">
      <h5 class="card-title mb-3">選擇參加項目（可複選）</h5>

      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" name="options[]" id="am" value="上午場">
        <label class="form-check-label" for="am">上午場（150 元）</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" name="options[]" id="pm" value="下午場">
        <label class="form-check-label" for="pm">下午場（100 元）</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" name="options[]" id="lunch" value="午餐">
        <label class="form-check-label" for="lunch">午餐（60 元）</label>
      </div>

      <div class="mt-3">
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </div>
  </form>
  <?php endif; ?>
</main>
<?php include "footer.php"; ?>

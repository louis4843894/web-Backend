<?php
require_once "header.php";
require_once "db.php";

// 1️⃣ 接收表單送出的欄位值
$order = $_POST["order"] ?? "";
$searchtxt = $_POST["searchtxt"] ?? "";
$date_start = $_POST["date_start"] ?? "";
$date_end = $_POST["date_end"] ?? "";

// 2️⃣ 建立 SQL 語法
$where = [];

// 🔹 日期區間相反時自動交換
if ($date_start && $date_end && $date_start > $date_end) {
  [$date_start, $date_end] = [$date_end, $date_start];
}

// 🔹 關鍵字搜尋條件
if ($searchtxt) {
  $safeSearch = mysqli_real_escape_string($conn, $searchtxt);
  $where[] = "(company LIKE '%$safeSearch%' OR content LIKE '%$safeSearch%')";
}

// 🔹 日期區間條件
if ($date_start) {
  $safeStart = mysqli_real_escape_string($conn, $date_start);
  $where[] = "pdate >= '$safeStart'";
}
if ($date_end) {
  $safeEnd = mysqli_real_escape_string($conn, $date_end);
  $where[] = "pdate <= '$safeEnd'";
}

// 🔹 組合 WHERE 與 ORDER BY
$sql = "SELECT * FROM job";
if (count($where) > 0) {
  $sql .= " WHERE " . implode(' AND ', $where);
}
if ($order) {
  $sql .= " ORDER BY $order";
}

$result = mysqli_query($conn, $sql);
if (!$result) {
  die("SQL 錯誤：" . mysqli_error($conn) . "<br>語法：" . $sql);
}
?>

<div class="container mt-4">
  <!-- 🔹 搜尋 + 篩選表單 -->
  <form action="job-post.php" method="post" class="mb-3 row g-2 align-items-center">
    <div class="col-auto">
      <select name="order" id="order" class="form-select w-auto">
        <option value="" <?= ($order=='') ? 'selected' : '' ?>>選擇排序欄位</option>
        <option value="company" <?= ($order=="company") ? "selected" : "" ?>>求才廠商</option>
        <option value="content" <?= ($order=="content") ? "selected" : "" ?>>求才內容</option>
        <option value="pdate" <?= ($order=="pdate") ? "selected" : "" ?>>刊登日期</option>
      </select>
    </div>

    <div class="col-auto">
      <input type="text" name="searchtxt" class="form-control"
             placeholder="搜尋廠商或內容"
             value="<?= htmlspecialchars($searchtxt) ?>">
    </div>

    <div class="col-auto">
      <input type="date" name="date_start" class="form-control"
             value="<?= htmlspecialchars($date_start) ?>" placeholder="起始日期">
    </div>

    <div class="col-auto">
      <input type="date" name="date_end" class="form-control"
             value="<?= htmlspecialchars($date_end) ?>" placeholder="結束日期">
    </div>

    <div class="col-auto">
      <input type="submit" class="btn btn-primary" value="篩選">
    </div>
  </form>

  <!-- 🔹 顯示資料表 -->
  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>求才廠商</th>
        <th>求才內容</th>
        <th>刊登日期</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($result)) { ?>
      <tr>
        <td><?= htmlspecialchars($row["company"]) ?></td>
        <td><?= htmlspecialchars($row["content"]) ?></td>
        <td><?= htmlspecialchars($row["pdate"]) ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

<?php
mysqli_close($conn);
require_once "footer.php";
?>

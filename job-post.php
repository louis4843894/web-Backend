<?php
session_start();
if (!isset($_SESSION['username'])) {
  $redirect = basename($_SERVER['PHP_SELF']);
  header("Location: login.php?redirect=$redirect&msg=" . urlencode("請先登入以繼續"));
  exit;
}

require_once "header.php";
require_once "db.php";

// 接收篩選欄位
$order = $_POST["order"] ?? "";
$searchtxt = $_POST["searchtxt"] ?? "";
$date_start = $_POST["date_start"] ?? "";
$date_end = $_POST["date_end"] ?? "";

// 日期檢查
if ($date_start && $date_end && $date_start > $date_end) {
  [$date_start, $date_end] = [$date_end, $date_start];
}

// 查詢條件
$where = [];
if ($searchtxt) {
  $safeSearch = mysqli_real_escape_string($conn, $searchtxt);
  $where[] = "(company LIKE '%$safeSearch%' OR content LIKE '%$safeSearch%')";
}
if ($date_start) $where[] = "pdate >= '$date_start'";
if ($date_end) $where[] = "pdate <= '$date_end'";

$sql = "SELECT * FROM job";
if (count($where) > 0) $sql .= " WHERE " . implode(" AND ", $where);
if ($order) $sql .= " ORDER BY $order";
$result = mysqli_query($conn, $sql);
?>

<div class="container mt-4">
  <form action="job-post.php" method="post" class="mb-3 row g-2 align-items-center">
    <div class="col-auto">
      <select name="order" class="form-select w-auto">
        <option value="" <?= ($order=='')?'selected':'' ?>>選擇排序欄位</option>
        <option value="company" <?= ($order=='company')?'selected':'' ?>>求才廠商</option>
        <option value="content" <?= ($order=='content')?'selected':'' ?>>求才內容</option>
        <option value="pdate" <?= ($order=='pdate')?'selected':'' ?>>刊登日期</option>
      </select>
    </div>
    <div class="col-auto">
      <input type="text" name="searchtxt" class="form-control" placeholder="搜尋廠商或內容" value="<?= htmlspecialchars($searchtxt) ?>">
    </div>
    <div class="col-auto">
      <input type="date" name="date_start" class="form-control" value="<?= htmlspecialchars($date_start) ?>">
    </div>
    <div class="col-auto">
      <input type="date" name="date_end" class="form-control" value="<?= htmlspecialchars($date_end) ?>">
    </div>
    <div class="col-auto">
      <input type="submit" class="btn btn-primary" value="篩選">
    </div>

    <!-- 🔹 所有人都能看到新增按鈕 -->
    <div class="col text-end">
      <a href="job-insert.php" class="btn btn-primary">新增</a>
    </div>
  </form>

  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>求才廠商</th>
        <th>求才內容</th>
        <th>刊登日期</th>
        <th>操作</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($result)) { ?>
      <tr>
        <td><?= htmlspecialchars($row["company"]) ?></td>
        <td><?= htmlspecialchars($row["content"]) ?></td>
        <td><?= date('Y-m-d H:i', strtotime($row["pdate"])) ?></td>
        <td>
          <a href="job-delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">刪除</a>
        </td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

<?php
mysqli_close($conn);
require_once "footer.php";
?>

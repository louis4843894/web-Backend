<?php
session_start();
if (!isset($_SESSION['username'])) {
  $redirect = basename($_SERVER['PHP_SELF']);
  header("Location: login.php?redirect=$redirect&msg=" . urlencode("請先登入以繼續"));
  exit;
}

require_once "header.php";
require_once "db.php";

// 篩選
$order = $_POST["order"] ?? "";
$searchtxt = $_POST["searchtxt"] ?? "";
$date_start = $_POST["date_start"] ?? "";
$date_end = $_POST["date_end"] ?? "";

$where = [];
if ($date_start && $date_end && $date_start > $date_end) {
  [$date_start, $date_end] = [$date_end, $date_start];
}
if ($searchtxt) {
  $safeSearch = mysqli_real_escape_string($conn, $searchtxt);
  $where[] = "(title LIKE '%$safeSearch%' OR description LIKE '%$safeSearch%')";
}
if ($date_start) $where[] = "pdate >= '$date_start'";
if ($date_end) $where[] = "pdate <= '$date_end'";

$sql = "SELECT * FROM activity";
if (count($where) > 0) $sql .= " WHERE " . implode(" AND ", $where);
if ($order) $sql .= " ORDER BY $order";
$result = mysqli_query($conn, $sql);
?>

<div class="container mt-4">
  <form action="activity.php" method="post" class="mb-3 row g-2 align-items-center">
    <div class="col-auto">
      <select name="order" class="form-select w-auto">
        <option value="" <?= ($order=='')?'selected':'' ?>>選擇排序欄位</option>
        <option value="title" <?= ($order=='title')?'selected':'' ?>>活動名稱</option>
        <option value="pdate" <?= ($order=='pdate')?'selected':'' ?>>活動日期</option>
      </select>
    </div>
    <div class="col-auto">
      <input type="text" name="searchtxt" class="form-control" placeholder="搜尋活動名稱或內容" value="<?= htmlspecialchars($searchtxt) ?>">
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

    <div class="col text-end">
      <a href="activity-insert.php" class="btn btn-primary">新增</a>
    </div>
  </form>

  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>活動名稱</th>
        <th>活動說明</th>
        <th>活動日期</th>
        <th>操作</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($result)) { ?>
      <tr>
        <td><?= htmlspecialchars($row["title"]) ?></td>
        <td><?= htmlspecialchars($row["description"]) ?></td>
        <td><?= date('Y-m-d H:i', strtotime($row["pdate"])) ?></td>
        <td>
          <a href="activity-delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">刪除</a>
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

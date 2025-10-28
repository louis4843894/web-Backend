<?php
session_start();
if (!isset($_SESSION['username'])) {
  $redirect = basename($_SERVER['PHP_SELF']);
  header("Location: login.php?redirect=$redirect&msg=" . urlencode("請先登入以繼續"));
  exit;
}

require_once "header.php";

try {
  require_once 'db.php';
  $sql = "SELECT * FROM job";
  $result = mysqli_query($conn, $sql);
?>
<div class="container mt-4">
  <table class="table table-bordered table-striped" id="job_table">
    <thead>
      <tr>
        <th>求才廠商</th>
        <th>求才內容</th>
        <th>日期</th>
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
} catch (Exception $e) {
  echo 'Message: ' . $e->getMessage();
}

require_once "footer.php";
?>

<!-- ✅ DataTable 初始化（防止重複初始化） -->
<script>
$(document).ready(function() {
  // 若已經有 DataTable，先銷毀舊的
  if ($.fn.DataTable.isDataTable('#job_table')) {
    $('#job_table').DataTable().destroy();
  }

  // 重新初始化 DataTable
  $('#job_table').DataTable({
    order: [[2, 'desc']], // 第3欄日期降冪排列
    language: {
      url: "https://cdn.datatables.net/plug-ins/1.13.8/i18n/zh-HANT.json"
    }
  });
});
</script>

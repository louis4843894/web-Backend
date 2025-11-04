<?php include("header.php"); ?>
<?php require_once "db.php"; ?>

<div class="container my-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="m-0">活動公告</h3>
    <!-- 不論身分都顯示「新增活動」 -->
    <a href="index-insert.php" class="btn btn-primary">＋ 新增活動</a>
  </div>

  <?php
  $sql = "SELECT id, title, description, pdate FROM activity ORDER BY pdate DESC, id DESC";
  $res = mysqli_query($conn, $sql);
  ?>

  <div class="row g-4">
    <?php if ($res && mysqli_num_rows($res) > 0): ?>
      <?php while ($row = mysqli_fetch_assoc($res)): ?>
        <div class="col-md-6">
          <div class="card h-100 bg-white text-dark">
            <div class="card-body d-flex flex-column">
              <h3 class="card-title mb-1"><?= htmlspecialchars($row['title']) ?></h3>
              <div class="text-muted small mb-2">
                <?= htmlspecialchars(date('Y-m-d H:i', strtotime($row['pdate']))) ?>
              </div>
              <p class="card-text">
                <?= nl2br(htmlspecialchars($row['description'])) ?>
              </p>

              <div class="mt-auto d-flex justify-content-between align-items-center">
                <!-- 不論身分都顯示「編輯／刪除」 -->
                <div class="btn-group">
                  <a href="index-update.php?id=<?= (int)$row['id'] ?>" class="btn btn-primary">編輯</a>
                  <a href="index-delete.php?id=<?= (int)$row['id'] ?>"class="btn btn-danger">刪除</a>
                </div>
                <!-- 右側可留空或放你的報名/詳情按鈕 -->
                <div></div>
              </div>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="col-12">
        <div class="text-center text-muted py-5">目前沒有活動</div>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php include("footer.php"); ?>

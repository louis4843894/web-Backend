<?php
$servername = "127.0.0.1";  // 改成 IPv4，避免 "::1" 連線問題
$dbname = "practice";
$dbUsername = "root";
$dbPassword = ""; // XAMPP 預設 root 無密碼

$conn = mysqli_connect($servername, $dbUsername, $dbPassword, $dbname);

if (!$conn) {
  die("無法連線: " . mysqli_connect_error());
} else {
  echo "✅ 成功連線到資料庫！";
}
?>

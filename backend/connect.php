<?php
$servername = "localhost";
$username = "root";
$password = ""; // mặc định để trống
$dbname = "qlbandoannhanh3"; // tên database trong phpMyAdmin

// Kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra
if ($conn->connect_error) {
    die("❌ Kết nối thất bại: " . $conn->connect_error);
}

// echo "✅ Kết nối thành công!";
?>

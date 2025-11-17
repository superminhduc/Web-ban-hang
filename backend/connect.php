<?php
// Thông tin kết nối đến MySQL
$servername = "localhost";   // Địa chỉ server MySQL
$username = "root";          // Tài khoản đăng nhập MySQL (mặc định là root)
$password = "";              // Mật khẩu MySQL (XAMPP thường để trống)
$dbname = "qlbandoannhanh3"; // Tên database được tạo trong phpMyAdmin

// Khởi tạo kết nối với MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối có thành công hay không
if ($conn->connect_error) {
    // Nếu lỗi → dừng chương trình và báo lỗi
    die("❌ Kết nối thất bại: " . $conn->connect_error);
}

// echo "✅ Kết nối thành công!"; // Dùng để kiểm tra kết nối (ẩn khi chạy thực tế)
?>

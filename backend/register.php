<?php
// Bật hiển thị lỗi
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'connect.php';

// Chỉ xử lý khi có POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: /Web-ban-hang/login.html");
    exit();
}

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');
$confirm  = trim($_POST['password_confirm'] ?? '');

// ========== KIỂM TRA DỮ LIỆU ==========
if ($username === '' || $password === '' || $confirm === '') {
    echo "<script>
            alert('Vui lòng nhập đầy đủ thông tin!');
            window.location.href = '/Web-ban-hang/login.html';
          </script>";
    exit();
}

if ($password !== $confirm) {
    echo "<script>
            alert('Mật khẩu nhập lại không khớp!');
            window.location.href = '/Web-ban-hang/login.html';
          </script>";
    exit();
}

if (strlen($username) < 3) {
    echo "<script>
            alert('Tên đăng nhập phải có ít nhất 3 ký tự!');
            window.location.href = '/Web-ban-hang/login.html';
          </script>";
    exit();
}

// ========== KIỂM TRA USERNAME TỒN TẠI ==========
$sql_check = "SELECT id FROM users WHERE username = ? LIMIT 1";
$stmt = $conn->prepare($sql_check);
$stmt->bind_param("s", $username);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    echo "<script>
            alert('Tên đăng nhập đã tồn tại!');
            window.location.href = '/Web-ban-hang/login.html';
          </script>";
    exit();
}

// ========== INSERT USER ==========
$sql_insert = "INSERT INTO users (username, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql_insert);
$stmt->bind_param("ss", $username, $password);

if ($stmt->execute()) {
    echo "<script>
            alert('Tạo tài khoản thành công! Hãy đăng nhập nhé 🎅🎄');
            window.location.href = '/Web-ban-hang/login.html';
          </script>";
    exit();
} else {
    echo "<script>
            alert('Lỗi hệ thống! Không thể đăng ký.');
            window.location.href = '/Web-ban-hang/login.html';
          </script>";
    exit();
}
?>

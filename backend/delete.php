<?php
// Hiển thị lỗi khi cần
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Kết nối DB
include 'connect.php';

// Kiểm tra id có hợp lệ không
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID không hợp lệ!");
}

$id = (int)$_GET['id'];

// Xóa sản phẩm
$sql = "DELETE FROM sanpham WHERE maSP = $id";

if ($conn->query($sql) === TRUE) {
    // Xóa thành công → quay về trang chủ
    header("Location: ../index.html");
    exit();
} else {
    echo "❌ Lỗi khi xóa: " . $conn->error;
}
?>

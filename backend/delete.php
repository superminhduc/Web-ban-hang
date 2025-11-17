<?php
include 'connect.php';
$id = $_GET['id'];
$sql = "DELETE FROM sanpham WHERE maSP=$id";
if ($conn->query($sql) === TRUE) {
    header("Location: /trangweb/index.html");
} else {
    echo "❌ Lỗi: " . $conn->error;
}
?>
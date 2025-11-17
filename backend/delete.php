<?php
include 'connect.php';
$id = $_GET['id'];
$sql = "DELETE FROM sanpham WHERE maSP=$id";
if ($conn->query($sql) === TRUE) {
    header("Location: ../index.html");
} else {
    echo "❌ Lỗi: " . $conn->error;
}
?>
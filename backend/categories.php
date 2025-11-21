<?php
// categories.php
// File này chỉ dùng để chuyển hướng sang trang danh mục mới (danhmuc.php)

// Hiện lỗi nếu có (cho dễ debug)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Chuyển sang giao diện quản lý danh mục mới
header("Location: danhmuc.php");
exit;

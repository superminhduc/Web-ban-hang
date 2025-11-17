<?php
// HIỂN LỖI RA MÀN HÌNH (rất quan trọng để fix trắng màn hình)
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/connect.php';

// kiểm tra kết nối DB
if ($conn->connect_error) {
    die("Lỗi kết nối database: " . $conn->connect_error);
}

// Hàm chạy query + bắt lỗi
function getRow($conn, $sql) {
    $result = $conn->query($sql);
    if (!$result) {
        die("Lỗi SQL: " . $conn->error . "<br> Câu lệnh: " . $sql);
    }
    return $result->fetch_assoc();
}

// --- CÁC THỐNG KÊ ---
$kq1 = getRow($conn, "SELECT COUNT(*) AS tongSP FROM sanpham");
$kq2 = getRow($conn, "SELECT SUM(soLuong) AS tongSL FROM sanpham");
$kq3 = getRow($conn, "SELECT SUM(gia * soLuong) AS tongGiaTri FROM sanpham");
$kq4 = getRow($conn, "SELECT tenSP, gia FROM sanpham ORDER BY gia DESC LIMIT 1");
$kq5 = getRow($conn, "SELECT tenSP, soLuong FROM sanpham ORDER BY soLuong DESC LIMIT 1");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thống kê sản phẩm</title>
    <link rel="stylesheet" href="../css/thongke.css">
</head>
<body>
<div class="container">

    <!-- Header + nút back -->
    <div class="header">
        <h2>📊 Thống kê sản phẩm</h2>
        <a href="../index.html" class="btn-back">⬅ Quay lại</a>
    </div>

    <!-- Các card thống kê -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Tổng số sản phẩm</div>
            <div class="stat-value"><?= $kq1['tongSP'] ?></div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Tổng số lượng tồn</div>
            <div class="stat-value"><?= $kq2['tongSL'] ?></div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Tổng giá trị hàng tồn</div>
            <div class="stat-value"><?= number_format($kq3['tongGiaTri']) ?>₫</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Sản phẩm giá cao nhất</div>
            <div class="stat-value">
                <?= $kq4['tenSP'] ?> (<?= number_format($kq4['gia']) ?>₫)
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Sản phẩm tồn kho nhiều nhất</div>
            <div class="stat-value">
                <?= $kq5['tenSP'] ?> (<?= $kq5['soLuong'] ?> cái)
            </div>
        </div>
    </div>

    <!-- Bảng chi tiết -->
    <table>
        <tr>
            <th>Mục</th>
            <th>Kết quả</th>
        </tr>
        <tr>
            <td>Tổng số sản phẩm</td>
            <td><?= $kq1['tongSP'] ?></td>
        </tr>
        <tr>
            <td>Tổng số lượng tồn</td>
            <td><?= $kq2['tongSL'] ?></td>
        </tr>
        <tr>
            <td>Tổng giá trị hàng tồn</td>
            <td><?= number_format($kq3['tongGiaTri']) ?>₫</td>
        </tr>
        <tr>
            <td>Sản phẩm giá cao nhất</td>
            <td><?= $kq4['tenSP'] ?> (<?= number_format($kq4['gia']) ?>₫)</td>
        </tr>
        <tr>
            <td>Sản phẩm tồn kho nhiều nhất</td>
            <td><?= $kq5['tenSP'] ?> (<?= $kq5['soLuong'] ?> cái)</td>
        </tr>
    </table>

</div>
</body>
</html>

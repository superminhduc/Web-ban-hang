<?php
// HIỆN LỖI RA MÀN HÌNH (để dễ debug)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Kết nối DB (connect.php nằm ở thư mục gốc)
require_once __DIR__ . '/connect.php';

// Hàm lấy 1 dòng
function getRow($conn, $sql) {
    $result = $conn->query($sql);
    if (!$result) {
        die("Lỗi SQL: " . $conn->error . "<br> Câu lệnh: " . $sql);
    }
    $row = $result->fetch_assoc();
    return $row ?: [];
}

// ================== CÁC THỐNG KÊ DOANH THU CHÍNH ==================

// Tổng số đơn hàng
$kq1 = getRow($conn, "
    SELECT COUNT(*) AS tongDH 
    FROM donhang
");

// Tổng doanh thu
$kq2 = getRow($conn, "
    SELECT IFNULL(SUM(tongTien), 0) AS tongDoanhThu
    FROM donhang
");

// Doanh thu hôm nay
$kq3 = getRow($conn, "
    SELECT IFNULL(SUM(tongTien), 0) AS doanhThuHomNay
    FROM donhang
    WHERE DATE(ngayDat) = CURDATE()
");

// Doanh thu tháng này
$kq4 = getRow($conn, "
    SELECT IFNULL(SUM(tongTien), 0) AS doanhThuThangNay
    FROM donhang
    WHERE YEAR(ngayDat) = YEAR(CURDATE())
      AND MONTH(ngayDat) = MONTH(CURDATE())
");

// Giá trị trung bình mỗi đơn
$kq5 = getRow($conn, "
    SELECT IFNULL(AVG(tongTien), 0) AS giaTriTB
    FROM donhang
");

// Gán biến
$tongDH           = $kq1['tongDH'] ?? 0;
$tongDoanhThu     = $kq2['tongDoanhThu'] ?? 0;
$doanhThuHomNay   = $kq3['doanhThuHomNay'] ?? 0;
$doanhThuThangNay = $kq4['doanhThuThangNay'] ?? 0;
$giaTriTB         = $kq5['giaTriTB'] ?? 0;

// ================== BẢNG DOANH THU THEO NGÀY ==================
$sqlNgay = "
    SELECT 
        DATE(ngayDat) AS ngay,
        COUNT(*) AS soDon,
        SUM(tongTien) AS doanhThu
    FROM donhang
    GROUP BY DATE(ngayDat)
    ORDER BY ngay DESC
    LIMIT 15
";
$resultNgay = $conn->query($sqlNgay);

// ================== BẢNG DOANH THU THEO THÁNG ==================
$sqlThang = "
    SELECT 
        DATE_FORMAT(ngayDat, '%Y-%m') AS thang,
        COUNT(*) AS soDon,
        SUM(tongTien) AS doanhThu
    FROM donhang
    GROUP BY DATE_FORMAT(ngayDat, '%Y-%m')
    ORDER BY thang DESC
    LIMIT 12
";
$resultThang = $conn->query($sqlThang);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thống kê doanh thu – Cartoon Cute</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;500;600;700&display=swap');

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 20px;
            padding-bottom: 40px;
            font-family: "Baloo 2", cursive;
            background: linear-gradient(135deg, #ffdde1, #ee9ca7);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        .container {
            max-width: 1150px;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            color: #b31217;
            text-shadow: 0 2px 4px rgba(0,0,0,0.15);
            margin-bottom: 8px;
        }

        .sub-title {
            text-align: center;
            color: #7f8c8d;
            margin-bottom: 22px;
        }

        /* ACTION BAR */
        .actions {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 18px;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.2);
            border: none;
        }

        .btn-back {
            background: #ffffff;
            color: #e74c3c;
        }

        .btn-back:hover {
            filter: brightness(0.96);
        }

        .btn-refresh {
            background: #e74c3c;
            color: #fff;
        }

        .btn-refresh:hover {
            filter: brightness(0.96);
        }

        .actions-spacer {
            flex: 1;
        }

        /* CARD THỐNG KÊ */
        .stat-bar {
            margin-bottom: 18px;
            padding: 14px 18px;
            border-radius: 18px;
            background: rgba(255,255,255,0.92);
            box-shadow: 0 8px 20px rgba(0,0,0,0.16);
        }
        .stat-bar h2 {
            margin: 0 0 10px;
            font-size: 20px;
            color: #c0392b;
        }
        .stat-items {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .stat-box {
            flex: 1 1 180px;
            padding: 10px 12px;
            border-radius: 12px;
            background: #fff7f7;
            border: 1px solid #f5b7b1;
        }
        .stat-label {
            font-size: 13px;
            color: #7f8c8d;
        }
        .stat-value {
            margin-top: 4px;
            font-size: 18px;
            font-weight: 700;
            color: #e74c3c;
        }

        .stat-box.main {
            background: #ffe0e0;
            border-color: #f1948a;
        }

        /* BẢNG */
        .tables-wrap {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 16px;
            margin-top: 10px;
        }

        @media (max-width: 900px) {
            .tables-wrap {
                grid-template-columns: 1fr;
            }
        }

        .table-card {
            background: rgba(255,255,255,0.93);
            border-radius: 16px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.12);
            overflow: hidden;
        }

        .table-card-header {
            padding: 10px 14px;
            background: #c0392b;
            color: #fff;
            font-size: 16px;
        }

        .table-card-body {
            padding: 8px 10px 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        thead {
            background: #fceaea;
        }

        th, td {
            padding: 6px 8px;
            text-align: left;
        }

        tbody tr:nth-child(even) {
            background: #fff7f7;
        }
        tbody tr:hover {
            background: #ffeaea;
        }

        .td-right {
            text-align: right;
        }

        /* TUYẾT RƠI CUTE */
        .snowflakes {
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 5;
        }

        .snowflake {
            position: fixed;
            top: -10px;
            color: #fff;
            font-size: 18px;
            opacity: 0.9;
            animation: snow 9s linear infinite;
        }

        @keyframes snow {
            0% {
                transform: translateY(-10px) translateX(0);
                opacity: 1;
            }
            100% {
                transform: translateY(110vh) translateX(40px);
                opacity: 0;
            }
        }
    </style>
</head>
<body>

<!-- SNOWFLAKES -->
<div class="snowflakes">
    <div class="snowflake" style="left:10%; animation-delay:0s;">❄</div>
    <div class="snowflake" style="left:25%; animation-delay:1.5s;">✼</div>
    <div class="snowflake" style="left:40%; animation-delay:0.8s;">❅</div>
    <div class="snowflake" style="left:55%; animation-delay:2.2s;">✻</div>
    <div class="snowflake" style="left:70%; animation-delay:1s;">❄</div>
    <div class="snowflake" style="left:85%; animation-delay:2.8s;">✼</div>
</div>

<div class="container">
    <h1>📈 Thống kê doanh thu – Cartoon Cute</h1>
    <div class="sub-title">Xem nhanh tình hình đơn hàng & doanh thu theo ngày / theo tháng</div>

    <!-- ACTION BAR -->
    <div class="actions">
        <a href="/Web-ban-hang/index.html" class="btn btn-back">⬅ Quay lại trang sản phẩm</a>
        <a href="thongkedoanhthu.php" class="btn btn-refresh">🔄 Làm mới</a>
        <div class="actions-spacer"></div>
    </div>

    <!-- CARD THỐNG KÊ TỔNG QUAN -->
    <div class="stat-bar">
        <h2>💰 Tổng quan doanh thu</h2>
        <div class="stat-items">
            <div class="stat-box main">
                <div class="stat-label">Tổng doanh thu</div>
                <div class="stat-value">
                    <?php echo number_format($tongDoanhThu, 0, ',', '.'); ?> đ
                </div>
            </div>

            <div class="stat-box">
                <div class="stat-label">Tổng số đơn hàng</div>
                <div class="stat-value">
                    <?php echo $tongDH; ?> đơn
                </div>
            </div>

            <div class="stat-box">
                <div class="stat-label">Doanh thu hôm nay</div>
                <div class="stat-value">
                    <?php echo number_format($doanhThuHomNay, 0, ',', '.'); ?> đ
                </div>
            </div>

            <div class="stat-box">
                <div class="stat-label">Doanh thu tháng này</div>
                <div class="stat-value">
                    <?php echo number_format($doanhThuThangNay, 0, ',', '.'); ?> đ
                </div>
            </div>

            <div class="stat-box">
                <div class="stat-label">Giá trị trung bình mỗi đơn</div>
                <div class="stat-value">
                    <?php echo number_format($giaTriTB, 0, ',', '.'); ?> đ
                </div>
            </div>
        </div>
    </div>

    <!-- BẢNG THEO NGÀY & THEO THÁNG -->
    <div class="tables-wrap">
        <!-- BẢNG DOANH THU THEO NGÀY -->
        <div class="table-card">
            <div class="table-card-header">📅 Doanh thu theo ngày (15 ngày gần nhất)</div>
            <div class="table-card-body">
                <table>
                    <thead>
                    <tr>
                        <th>Ngày</th>
                        <th class="td-right">Số đơn</th>
                        <th class="td-right">Doanh thu</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($resultNgay && $resultNgay->num_rows > 0): ?>
                        <?php while ($row = $resultNgay->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['ngay']); ?></td>
                                <td class="td-right"><?php echo (int)$row['soDon']; ?></td>
                                <td class="td-right">
                                    <?php echo number_format($row['doanhThu'], 0, ',', '.'); ?> đ
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">Chưa có dữ liệu đơn hàng.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- BẢNG DOANH THU THEO THÁNG -->
        <div class="table-card">
            <div class="table-card-header">📆 Doanh thu theo tháng (12 tháng gần nhất)</div>
            <div class="table-card-body">
                <table>
                    <thead>
                    <tr>
                        <th>Tháng</th>
                        <th class="td-right">Số đơn</th>
                        <th class="td-right">Doanh thu</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($resultThang && $resultThang->num_rows > 0): ?>
                        <?php while ($row = $resultThang->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['thang']); ?></td>
                                <td class="td-right"><?php echo (int)$row['soDon']; ?></td>
                                <td class="td-right">
                                    <?php echo number_format($row['doanhThu'], 0, ',', '.'); ?> đ
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">Chưa có dữ liệu đơn hàng.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
</body>
</html>

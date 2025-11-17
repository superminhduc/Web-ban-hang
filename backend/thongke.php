<?php
// Hiện lỗi để dễ debug nếu có vấn đề
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/connect.php';

// Hàm chạy query 1 dòng
function getRow($conn, $sql) {
    $result = $conn->query($sql);
    if (!$result) {
        die("Lỗi SQL: " . $conn->error . "<br>Câu lệnh: " . $sql);
    }
    return $result->fetch_assoc();
}

// --- CÁC THỐNG KÊ CHÍNH ---
$kq1 = getRow($conn, "SELECT COUNT(*) AS tongSP FROM sanpham");
$kq2 = getRow($conn, "SELECT SUM(soLuong) AS tongSL FROM sanpham");
$kq3 = getRow($conn, "SELECT SUM(gia * soLuong) AS tongGiaTri FROM sanpham");

// Sản phẩm giá cao nhất
$kq4 = getRow($conn, "
    SELECT tenSP, gia 
    FROM sanpham 
    ORDER BY gia DESC 
    LIMIT 1
");

// Sản phẩm tồn kho nhiều nhất
$kq5 = getRow($conn, "
    SELECT tenSP, soLuong 
    FROM sanpham 
    ORDER BY soLuong DESC 
    LIMIT 1
");

// Lấy toàn bộ sản phẩm để in bảng chi tiết (nếu muốn)
$listSP = $conn->query("SELECT maSP, tenSP, gia, soLuong FROM sanpham");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thống kê sản phẩm – Cartoon Cute</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;500;600;700&display=swap');

        * { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 20px;
            font-family: "Baloo 2", cursive;
            background: linear-gradient(135deg, #ffdde1, #ee9ca7);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* ===== SNOW ===== */
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
            opacity: 0.95;
            font-size: 20px;
            animation: snow 9s linear infinite;
        }
        @keyframes snow {
            0% {
                transform: translateX(0) translateY(-10px) rotate(0deg);
                opacity: 0;
            }
            10% { opacity: 1; }
            50% {
                transform: translateX(25px) translateY(50vh) rotate(180deg);
            }
            100% {
                transform: translateX(-25px) translateY(110vh) rotate(360deg);
                opacity: 0;
            }
        }
        .snowflake:nth-child(1){left:5%; animation-duration:9s;}
        .snowflake:nth-child(2){left:15%;animation-duration:7s;}
        .snowflake:nth-child(3){left:30%;animation-duration:10s;}
        .snowflake:nth-child(4){left:50%;animation-duration:8s;}
        .snowflake:nth-child(5){left:70%;animation-duration:9s;}
        .snowflake:nth-child(6){left:85%;animation-duration:11s;}

        /* ===== KHUNG CHÍNH ===== */
        .main-card {
            max-width: 1100px;
            margin: 20px auto 60px;
            background: rgba(255, 248, 248, 0.65);
            backdrop-filter: blur(16px);
            border-radius: 28px;
            padding: 24px 28px 32px;
            border: 2px solid rgba(255,255,255,0.6);
            box-shadow: 0 16px 40px rgba(0,0,0,0.25);
            position: relative;
            z-index: 10;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .title {
            font-size: 30px;
            display: flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(90deg, #b31217, #ff6b81);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .subtitle {
            font-size: 14px;
            color: #7f8c8d;
            margin-bottom: 18px;
        }

        .back-btn {
            padding: 8px 18px;
            border-radius: 20px;
            background: #2ecc71;
            color: #ffffff;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.2);
        }
        .back-btn:hover { transform: translateY(-1px); }

        /* DÒNG EMOJI */
        .emoji-row {
            text-align: center;
            font-size: 26px;
            margin: 8px 0 18px;
        }

        /* ===== CARD THỐNG KÊ ===== */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 24px;
        }
        @media (max-width: 900px) {
            .stats-row { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 600px) {
            .stats-row { grid-template-columns: 1fr; }
        }

        .stat-card {
            background: linear-gradient(135deg, #ff758c, #ff7eb3);
            border-radius: 20px;
            padding: 16px 14px;
            color: #ffecec;
            box-shadow: 0 6px 16px rgba(0,0,0,0.2);
            transition: 0.22s;
        }
        .stat-card:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 10px 22px rgba(0,0,0,0.22);
        }
        .stat-title {
            font-size: 14px;
            opacity: 0.95;
            margin-bottom: 6px;
        }
        .stat-value {
            font-size: 18px;
            font-weight: 700;
            color: #ffffff;
        }

        /* ===== BẢNG THỐNG KÊ ===== */
        .section-title {
            margin-top: 10px;
            font-size: 20px;
            color: #b31217;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }

        thead tr {
            background: linear-gradient(90deg, #ff6b81, #ff4757);
            color: #fff;
        }

        th, td {
            padding: 10px 12px;
            font-size: 15px;
        }

        tbody tr:nth-child(odd)  { background: rgba(255, 236, 239, 0.95); }
        tbody tr:nth-child(even) { background: rgba(255, 225, 230, 0.95); }
        tbody tr:hover { background: #ffd6dc; }

        /* Santa chạy ở CUỐI TRANG, không theo màn hình */
.santa {
    position: absolute;      /* ❗ đổi từ fixed -> absolute */
    left: 0;
    bottom: 8px;             /* nằm sát cuối trang */
    width: 100%;
    text-align: center;
    font-size: 32px;
    pointer-events: none;    /* không chặn click */
}

.santa-track {
    display: inline-block;
    white-space: nowrap;
    animation: santaMove 3s linear infinite; /* chạy đều từ phải sang trái */
}

@keyframes santaMove {
    0%   { transform: translateX(100%); }
    100% { transform: translateX(-100%); }
}
    </style>
</head>
<body>

<!-- SNOW -->
<div class="snowflakes">
    <div class="snowflake">❄️</div><div class="snowflake">✨</div><div class="snowflake">❄️</div>
    <div class="snowflake">✨</div><div class="snowflake">❄️</div><div class="snowflake">✨</div>
</div>

<!-- KHUNG CHÍNH -->
<div class="main-card">
    <!-- TOPBAR -->
    <div class="topbar">
        <div class="title">🎄 Thống kê sản phẩm kho hàng 🎁</div>
        <a href="/Web-ban-hang/index.html" class="back-btn">⬅ Về trang chủ</a>
    </div>
    <div class="subtitle">Không khí Giáng Sinh lan tỏa khắp kho hàng 🎅</div>

    <!-- EMOJI HÀNG TRÊN -->
    <div class="emoji-row">
        🎅🎁🦌🎄⭐ 🎅🎁🦌🎄⭐ 🎅🎁🦌🎄⭐
    </div>

    <!-- DÃY CARD THỐNG KÊ -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-title">📦 Tổng số sản phẩm</div>
            <div class="stat-value">
                <?= number_format($kq1['tongSP'] ?? 0) ?>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-title">🎁 Tổng số lượng tồn</div>
            <div class="stat-value">
                <?= number_format($kq2['tongSL'] ?? 0) ?>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-title">💰 Tổng giá trị hàng tồn</div>
            <div class="stat-value">
                <?= number_format($kq3['tongGiaTri'] ?? 0, 0, ',', '.') ?>đ
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-title">⭐ Sản phẩm giá cao nhất</div>
            <div class="stat-value">
                <?= isset($kq4['tenSP']) ? $kq4['tenSP'] . ' (' . number_format($kq4['gia'], 0, ',', '.') . 'đ)' : '—' ?>
            </div>
        </div>
    </div>

    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-title">🎄 Sản phẩm tồn kho nhiều nhất</div>
            <div class="stat-value">
                <?= isset($kq5['tenSP']) ? $kq5['tenSP'] . ' (' . number_format($kq5['soLuong']) . ' cái)' : '—' ?>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-title">📊 Trung bình giá sản phẩm</div>
            <div class="stat-value">
                <?php
                $avg = getRow($conn, "SELECT AVG(gia) AS avgGia FROM sanpham");
                echo number_format($avg['avgGia'] ?? 0, 0, ',', '.') . 'đ';
                ?>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-title">🧮 Số mặt hàng còn tồn</div>
            <div class="stat-value">
                <?php
                $kq6 = getRow($conn, "SELECT COUNT(*) AS soMatHang FROM sanpham WHERE soLuong > 0");
                echo number_format($kq6['soMatHang'] ?? 0);
                ?>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-title">🚨 Hàng sắp hết (&lt;= 3)</div>
            <div class="stat-value">
                <?php
                $kq7 = getRow($conn, "SELECT COUNT(*) AS sapHet FROM sanpham WHERE soLuong <= 3");
                echo number_format($kq7['sapHet'] ?? 0) . " mặt hàng";
                ?>
            </div>
        </div>
    </div>

    <!-- BẢNG CHI TIẾT THỐNG KÊ -->
    <div class="section-title">📄 Bảng chi tiết thống kê</div>
    <table>
        <thead>
            <tr>
                <th>Mục</th>
                <th>Kết quả</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Tổng số sản phẩm</td>
                <td><?= number_format($kq1['tongSP'] ?? 0) ?></td>
            </tr>
            <tr>
                <td>Tổng số lượng tồn</td>
                <td><?= number_format($kq2['tongSL'] ?? 0) ?></td>
            </tr>
            <tr>
                <td>Tổng giá trị hàng tồn</td>
                <td><?= number_format($kq3['tongGiaTri'] ?? 0, 0, ',', '.') ?>đ</td>
            </tr>
            <tr>
                <td>Sản phẩm giá cao nhất</td>
                <td>
                    <?= isset($kq4['tenSP']) ? $kq4['tenSP'] . ' (' . number_format($kq4['gia'], 0, ',', '.') . 'đ)' : '—' ?>
                </td>
            </tr>
            <tr>
                <td>Sản phẩm tồn kho nhiều nhất</td>
                <td>
                    <?= isset($kq5['tenSP']) ? $kq5['tenSP'] . ' (' . number_format($kq5['soLuong']) . ' cái)' : '—' ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- SANTA (đặt SAU main-card, CỐ ĐỊNH DƯỚI MÀN HÌNH) -->
<div class="santa">
    <div class="santa-track">
        🎅🦌🦌 &nbsp; 🎅🦌🦌 &nbsp; 🎅🦌🦌 &nbsp; 🎅🦌🦌
    </div>
</div>

</body>
</html>

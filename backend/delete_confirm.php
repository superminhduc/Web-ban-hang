<?php
// Kết nối database
include 'connect.php';

// 1. Kiểm tra id hợp lệ chưa
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID sản phẩm không hợp lệ!");
}

$id = (int)$_GET['id'];

// 2. Lấy thông tin sản phẩm (để hiện tên trong bảng xác nhận)
$sql  = "SELECT tenSP FROM sanpham WHERE maSP = $id";
$result = $conn->query($sql);

if (!$result || $result->num_rows == 0) {
    die("Không tìm thấy sản phẩm cần xóa!");
}

$row   = $result->fetch_assoc();
$tenSP = $row['tenSP'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác nhận xóa sản phẩm</title>
    <style>
        /* ===== RESET ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", Arial, sans-serif;
        }

        /* NỀN NOEL */
        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(
                180deg,
                #ffffff 0%,
                #ffecec 25%,
                #ff9f9f 50%,
                #ff4b4b 75%,
                #b30000 100%
            );
            overflow: hidden;
            color: #1f2933;
        }

        /* TUYẾT */
        .snowflakes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .snowflake {
            position: fixed;
            top: -10px;
            color: #ffffff;
            font-size: 18px;
            opacity: 0.9;
            animation: snow 9s linear infinite;
        }

        .snowflake:nth-child(1) { left: 10%; font-size: 20px; animation-delay: 0s; }
        .snowflake:nth-child(2) { left: 25%; font-size: 16px; animation-delay: 2s; }
        .snowflake:nth-child(3) { left: 40%; font-size: 22px; animation-delay: 1s; }
        .snowflake:nth-child(4) { left: 60%; font-size: 18px; animation-delay: 3s; }
        .snowflake:nth-child(5) { left: 80%; font-size: 20px; animation-delay: 1.5s; }

        @keyframes snow {
            0%   { transform: translateY(0); opacity: 0.95; }
            100% { transform: translateY(110vh); opacity: 0; }
        }

        /* ===== HỘP XÁC NHẬN ===== */

        /* ⭐ Hiệu ứng RUNG */
        @keyframes shake {
            0% { transform: translateX(0); }
            20% { transform: translateX(-6px); }
            40% { transform: translateX(6px); }
            60% { transform: translateX(-4px); }
            80% { transform: translateX(4px); }
            100% { transform: translateX(0); }
        }

        .box {
            position: relative;
            z-index: 5;
            background: #ffffff;
            padding: 26px 28px 24px;
            border-radius: 16px;
            box-shadow: 0 18px 40px rgba(0,0,0,0.35);
            max-width: 460px;
            width: 100%;
            text-align: center;
            border: 1px solid rgba(248, 250, 252, 0.9);

            /* ⭐ RUNG KHI LOAD TRANG */
            animation: shake 0.45s ease;
        }

        .icon {
            font-size: 34px;
            margin-bottom: 6px;
        }

        h2 {
            margin: 0 0 10px;
            font-size: 22px;
            color: #b91c1c;
        }

        .product-name {
            font-weight: bold;
            color: #e11d48;
            margin-bottom: 18px;
        }

        .btns {
            display: flex;
            justify-content: center;
            gap: 14px;
        }

        .btn {
            padding: 9px 20px;
            border-radius: 999px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            min-width: 90px;
            font-size: 14px;
            transition: 0.15s;
        }

        .btn-cancel {
            background: #6b7280;
            color: white;
        }

        .btn-delete {
            background: #ef4444;
            color: white;
        }

        .btn:hover {
            transform: translateY(-1px);
            opacity: 0.9;
        }

        .hint {
            margin-top: 10px;
            font-size: 13px;
            color: #6b7280;
        }
    </style>
</head>

<body>

<!-- Tuyết -->
<div class="snowflakes">
    <div class="snowflake">❄</div>
    <div class="snowflake">✻</div>
    <div class="snowflake">❅</div>
    <div class="snowflake">✼</div>
    <div class="snowflake">❆</div>
</div>

<div class="box">
    <div class="icon">❌</div>
    <h2>Xác nhận xóa</h2>
    <p>Bạn có chắc chắn muốn xóa sản phẩm:</p>

    <p class="product-name">
        "<?php echo htmlspecialchars($tenSP); ?>" (ID: <?php echo $id; ?>)
    </p>

    <div class="btns">
        <a href="../index.html" class="btn btn-cancel">Hủy</a>
        <a href="delete.php?id=<?php echo $id; ?>" class="btn btn-delete">Xóa</a>
    </div>

    <div class="hint">Hành động này không thể hoàn tác.</div>
</div>

</body>
</html>

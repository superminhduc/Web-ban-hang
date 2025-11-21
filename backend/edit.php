<?php
// HIỆN LỖI RA MÀN HÌNH (để tránh trắng trang)
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/connect.php';

// Lấy ID sản phẩm từ URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID sản phẩm không hợp lệ.");
}
$id = (int)$_GET['id'];

// Nếu bấm nút Cập nhật (submit form)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tenSP   = $_POST['tenSP']   ?? '';
    $gia     = $_POST['gia']     ?? 0;
    $moTa    = $_POST['moTa']    ?? '';
    $hinhAnh = $_POST['hinhAnh'] ?? '';
    $soLuong = $_POST['soLuong'] ?? 0;

    // Escape đơn giản để tránh lỗi câu lệnh
    $tenSP   = $conn->real_escape_string($tenSP);
    $gia     = $conn->real_escape_string($gia);
    $moTa    = $conn->real_escape_string($moTa);
    $hinhAnh = $conn->real_escape_string($hinhAnh);
    $soLuong = (int)$soLuong;

    // Update DB
    $sql = "
        UPDATE sanpham
        SET tenSP   = '$tenSP',
            gia     = '$gia',
            moTa    = '$moTa',
            hinhAnh = '$hinhAnh',
            soLuong = '$soLuong'
        WHERE maSP = $id
    ";

    if ($conn->query($sql) === TRUE) {
        // ==========================
        // TRANG XÁC NHẬN ĐẸP SAU KHI CẬP NHẬT
        // ==========================
        ?>
        <!DOCTYPE html>
        <html lang="vi">
        <head>
            <meta charset="UTF-8">
            <title>Cập nhật thành công – Cartoon Cute</title>
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
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }

                .card {
                    width: 100%;
                    max-width: 550px;
                    background: #fff8f8;
                    border-radius: 26px;
                    padding: 30px 32px 34px;
                    text-align: center;
                    box-shadow: 0 14px 32px rgba(0,0,0,0.18);
                }

                .card h1 {
                    font-size: 28px;
                    color: #e74c3c;
                    margin-bottom: 10px;
                }

                .card p {
                    font-size: 16px;
                    color: #7f8c8d;
                    margin-bottom: 24px;
                }

                .btn {
                    display: block;
                    width: 100%;
                    border-radius: 999px;
                    padding: 11px 0;
                    font-size: 16px;
                    font-weight: 700;
                    text-decoration: none;
                    border: none;
                    cursor: pointer;
                    margin-bottom: 12px;
                    box-shadow: 0 8px 18px rgba(0,0,0,0.18);
                }

                .btn-primary {
                    background: #ff6b81;
                    color: #fff;
                }

                .btn-secondary {
                    background: #ffb347;
                    color: #fff;
                }

                .btn:hover {
                    transform: translateY(-2px);
                }

                .emoji-top {
                    font-size: 40px;
                    margin-bottom: 8px;
                }
            </style>
        </head>
        <body>
            <div class="card">
                <div class="emoji-top">✨🎄</div>
                <h1>Cập nhật sản phẩm thành công!</h1>
                <p>Sản phẩm của bạn đã được cập nhật trong hệ thống 💖</p>

                <a href="/Web-ban-hang/index.html" class="btn btn-primary">
                    ⬅ Về trang chủ
                </a>
                <a href="/Web-ban-hang/backend/edit.php?id=<?php echo $id; ?>" class="btn btn-secondary">
                    ✏️ Sửa lại sản phẩm này
                </a>
            </div>
        </body>
        </html>
        <?php
        exit; // kết thúc tại đây, không render form nữa
    } else {
        die("❌ Lỗi cập nhật: " . $conn->error);
    }
}

// ======================
// LẤY DỮ LIỆU SẢN PHẨM ĐỂ ĐỔ VÀO FORM
// ======================
$sql  = "SELECT * FROM sanpham WHERE maSP = $id LIMIT 1";
$spRs = $conn->query($sql);
if (!$spRs || $spRs->num_rows == 0) {
    die("Không tìm thấy sản phẩm với ID $id");
}
$sp = $spRs->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Sửa sản phẩm – Cartoon Cute</title>

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
            position: relative; /* cho Santa absolute bám theo trang */
        }

        /* CUTE SNOW (giống index/add) */
        .snowflakes {
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 20;
        }
        .snowflake {
            position: fixed;
            top: -10px;
            color: #fff;
            opacity: 0.95;
            font-size: 20px;
            animation: snow 8s linear infinite;
        }
        @keyframes snow {
            0%   { transform: translateY(-10px) rotate(0deg); opacity: 0; }
            10%  { opacity: 1; }
            100% { transform: translateY(105vh) rotate(360deg); opacity: 0; }
        }
        .snowflake:nth-child(1){left:5%;animation-duration:9s;}
        .snowflake:nth-child(2){left:15%;animation-duration:7s;}
        .snowflake:nth-child(3){left:30%;animation-duration:10s;}
        .snowflake:nth-child(4){left:50%;animation-duration:8s;}
        .snowflake:nth-child(5){left:70%;animation-duration:9s;}
        .snowflake:nth-child(6){left:85%;animation-duration:11s;}

        /* TOPBAR */
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .title {
            font-size: 34px;
            color: #b31217;
            display: flex;
            align-items: center;
            gap: 8px;
            text-shadow: 0 3px 6px rgba(0,0,0,0.18);
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

        /* BANNER */
        .banner {
            margin-top: 18px;
            background: #ffecec;
            padding: 12px 20px;
            border-radius: 20px;
            font-size: 17px;
            color: #d63031;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        /* FORM CARD (giống add nhưng để chữ Sửa) */
        .wrapper {
            display: flex;
            justify-content: center;
            margin-top: 30px;
            margin-bottom: 80px; /* chừa chỗ cho Santa */
        }
        .form-card {
            width: 100%;
            max-width: 520px;
            background: #fff8f8;
            border-radius: 24px;
            padding: 24px 26px 28px;
            box-shadow: 0 10px 24px rgba(0,0,0,0.18);
        }
        .form-title {
            text-align: center;
            font-size: 26px;
            color: #e74c3c;
            margin-bottom: 6px;
        }
        .form-subtitle {
            text-align: center;
            font-size: 14px;
            color: #7f8c8d;
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-size: 15px;
            color: #c0392b;
            margin-bottom: 4px;
        }
        .input,
        .textarea {
            width: 100%;
            border-radius: 16px;
            border: 2px solid #ffd6dc;
            padding: 8px 12px;
            font-size: 15px;
            font-family: inherit;
            outline: none;
            background: #ffffff;
            transition: 0.15s;
        }
        .input:focus,
        .textarea:focus {
            border-color: #ff6b81;
            box-shadow: 0 0 0 2px rgba(255, 107, 129, 0.2);
        }
        .textarea {
            resize: vertical;
            min-height: 80px;
        }
        .field {
            margin-bottom: 14px;
        }

        /* BUTTONS */
        .button-row {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        .submit-btn {
            flex: 1;
            border: none;
            border-radius: 999px;
            background: linear-gradient(135deg, #ffb347, #ff6b6b);
            color: white;
            font-size: 17px;
            font-weight: 700;
            padding: 10px 0;
            cursor: pointer;
            box-shadow: 0 6px 16px rgba(0,0,0,0.18);
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }
        .submit-btn:hover { transform: translateY(-2px); }

        .cancel-btn {
            padding: 10px 18px;
            border-radius: 999px;
            border: 2px solid #bdc3c7;
            background: transparent;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            color: #555;
        }
        .cancel-btn:hover {
            background: #ecf0f1;
        }

        .note {
            font-size: 12px;
            color: #7f8c8d;
            margin-top: 4px;
        }

        /* SANTA ở cuối trang */
        .santa {
            position: absolute;
            left: 0;
            bottom: 8px;
            width: 100%;
            text-align: center;
            font-size: 32px;
            pointer-events: none;
        }
        .santa-track {
            display: inline-block;
            white-space: nowrap;
            animation: santaMove 3s linear infinite;
        }
        @keyframes santaMove {
            from { transform: translateX(100%); }
            to   { transform: translateX(-100%); }
        }
    </style>
</head>

<body>

<!-- CUTE SNOW -->
<div class="snowflakes">
    <div class="snowflake">❄️</div><div class="snowflake">✨</div><div class="snowflake">❄️</div>
    <div class="snowflake">✨</div><div class="snowflake">❄️</div><div class="snowflake">✨</div>
</div>

<!-- TOPBAR -->
<div class="topbar">
    <div class="title">✏️ Sửa Sản Phẩm Noel</div>
    <a href="/Web-ban-hang/index.html" class="back-btn">⬅ Quay lại danh sách</a>
</div>

<!-- BANNER -->
<div class="banner">
    🎄 Cập nhật lại món ăn cho kịp mùa Giáng Sinh 🎅
</div>

<!-- FORM SỬA -->
<div class="wrapper">
    <form class="form-card" method="post">
        <div class="form-title">+ Cập nhật sản phẩm</div>
        <div class="form-subtitle">Chỉnh sửa thông tin sản phẩm cho thật chuẩn nhé 💖</div>

        <div class="field">
            <label for="tenSP">Tên sản phẩm</label>
            <input class="input" type="text" id="tenSP" name="tenSP"
                   value="<?= htmlspecialchars($sp['tenSP']) ?>" required>
        </div>

        <div class="field">
            <label for="gia">Giá</label>
            <input class="input" type="number" id="gia" name="gia"
                   value="<?= htmlspecialchars($sp['gia']) ?>" step="1000" min="0" required>
        </div>

        <div class="field">
            <label for="moTa">Mô tả</label>
            <textarea class="textarea" id="moTa" name="moTa"><?= htmlspecialchars($sp['moTa']) ?></textarea>
        </div>

        <div class="field">
            <label for="hinhAnh">Hình ảnh (URL / tên file)</label>
            <input class="input" type="text" id="hinhAnh" name="hinhAnh"
                   value="<?= htmlspecialchars($sp['hinhAnh']) ?>">
            <div class="note">Có thể để trống nếu chưa có hình.</div>
        </div>

        <div class="field">
            <label for="soLuong">Số lượng</label>
            <input class="input" type="number" id="soLuong" name="soLuong"
                   value="<?= htmlspecialchars($sp['soLuong']) ?>" min="0">
        </div>

        <div class="button-row">
            <button type="submit" class="submit-btn">🎄 Cập nhật sản phẩm</button>
            <button type="button" class="cancel-btn" onclick="window.location.href='/Web-ban-hang/index.html'">
                Hủy
            </button>
        </div>
    </form>
</div>

<!-- SANTA CUỐI TRANG -->
<div class="santa">
    <div class="santa-track">🎅🦌🦌 &nbsp; 🎅🦌🦌 &nbsp; 🎅🦌🦌</div>
</div>

</body>
</html>

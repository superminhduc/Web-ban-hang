<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'connect.php';
session_start();

$success = false;
$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tenSP   = trim($_POST['tenSP'] ?? '');
    $gia     = trim($_POST['gia'] ?? '');
    $moTa    = trim($_POST['moTa'] ?? '');
    $hinhAnh = trim($_POST['hinhAnh'] ?? '');
    $maDM    = trim($_POST['maDM'] ?? '');
    $soLuong = trim($_POST['soLuong'] ?? '0');

    $sql = "INSERT INTO sanpham (tenSP, gia, moTa, hinhAnh, maDM, soLuong)
            VALUES ('$tenSP', '$gia', '$moTa', '$hinhAnh', '$maDM', '$soLuong')";

    if ($conn->query($sql) === TRUE) {
        $success = true; // đánh dấu thành công
    } else {
        $errorMsg = "❌ Lỗi SQL: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Thêm Sản Phẩm – Cute</title>

<style>
@import url('https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;600;700&display=swap');
body {
    margin: 0; padding: 20px;
    background: linear-gradient(135deg,#ffdde1,#ee9ca7);
    font-family: "Baloo 2", cursive;
    min-height: 100vh;
}

/* Form */
.container{
    width:100%; max-width:520px; margin:40px auto;
    background:#fff8f8; padding:28px;
    border-radius:24px;
    box-shadow:0 10px 24px rgba(0,0,0,0.18);
}

h2{text-align:center;color:#e74c3c;margin-bottom:6px;}
.sub{text-align:center;font-size:14px;color:#7f8c8d;margin-bottom:20px;}

label{color:#c0392b;font-size:15px;}
.input,.textarea{
    width:100%;padding:10px 12px;border-radius:14px;border:2px solid #ffd6dc;
    margin-bottom:14px;font-size:15px;outline:none;
}
.input:focus,.textarea:focus{border-color:#ff6b81;box-shadow:0 0 0 2px rgba(255,107,129,0.2);}
.textarea{resize:vertical;height:80px;}

.btn-submit{
    width:100%;border:none;padding:12px;
    background:linear-gradient(135deg,#ffb347,#ff6b6b);
    border-radius:999px;color:white;font-size:17px;font-weight:700;
    cursor:pointer;box-shadow:0 6px 16px rgba(0,0,0,0.18);
}
.btn-submit:hover{transform:translateY(-2px);}

/* Success Box */
.success-box{
    text-align:center;
    background:#fff0f4;
    padding:30px;
    border-radius:24px;
    box-shadow:0 8px 20px rgba(0,0,0,0.18);
    animation:pop .4s ease;
}
@keyframes pop{
    from{transform:scale(.7);opacity:0;}
    to{transform:scale(1);opacity:1;}
}

.success-box h3{
    color:#e84393;font-size:24px;margin-bottom:10px;
}
.success-btn{
    display:block;
    margin-top:14px;
    padding:10px;
    background:#ff6b81;
    color:white;
    border-radius:18px;
    text-decoration:none;
    font-weight:600;
}
.success-btn:hover{background:#ff415c;}
</style>
</head>

<body>

<?php if ($success): ?>

    <!-- CARD THÔNG BÁO THÀNH CÔNG -->
    <div class="container success-box">
        <h3>🎉 Thêm sản phẩm thành công!</h3>
        <p>Sản phẩm đã được thêm vào hệ thống 💖</p>

        <a class="success-btn" href="/Web-ban-hang/index.html">⬅ Về trang chủ</a>
        <a class="success-btn" href="add.php" style="background:#ffa502;">➕ Thêm sản phẩm khác</a>
    </div>

<?php else: ?>

    <!-- FORM -->
    <div class="container">
        <h2>➕ Thêm Sản Phẩm</h2>
        <div class="sub">Nhập thông tin sản phẩm cho mùa Giáng Sinh 🎄</div>

        <?php if ($errorMsg): ?>
            <div style="background:#ffd6d6;padding:10px;border-radius:14px;color:#e74c3c;margin-bottom:12px;">
                <?= $errorMsg ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <label>Tên sản phẩm:</label>
            <input class="input" name="tenSP" required>

            <label>Giá (VNĐ):</label>
            <input class="input" type="number" name="gia" required>

            <label>Mô tả:</label>
            <textarea class="textarea" name="moTa"></textarea>

            <label>Hình ảnh:</label>
            <input class="input" name="hinhAnh">

            <label>Mã danh mục:</label>
            <input class="input" type="number" name="maDM" required>

            <label>Số lượng:</label>
            <input class="input" type="number" name="soLuong" value="0">

            <button class="btn-submit">🎁 Thêm Sản Phẩm</button>
        </form>
    </div>

<?php endif; ?>

</body>
</html>

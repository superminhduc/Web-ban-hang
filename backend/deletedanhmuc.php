<?php
// deletedanhmuc.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/connect.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID danh mục không hợp lệ.");
}
$id = (int)$_GET['id'];

$sql = "DELETE FROM danhmuc WHERE maDM = $id";

if ($conn->query($sql) === TRUE) {
    // XÓA THÀNH CÔNG → hiện card đẹp
    ?>
    <!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <title>Đã xóa danh mục</title>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;500;600;700&display=swap');

            * { box-sizing:border-box; }
            body {
                margin:0;
                padding:20px;
                font-family:"Baloo 2",cursive;
                background:linear-gradient(135deg,#ffdde1,#ee9ca7);
                min-height:100vh;
                display:flex;
                justify-content:center;
                align-items:center;
            }
            .card{
                width:100%;
                max-width:480px;
                background:#fff8f8;
                border-radius:26px;
                padding:26px 30px;
                text-align:center;
                box-shadow:0 14px 32px rgba(0,0,0,0.18);
            }
            .card h1{
                font-size:26px;
                color:#e74c3c;
                margin-bottom:10px;
            }
            .card p{
                font-size:16px;
                color:#7f8c8d;
                margin-bottom:22px;
            }
            .btn{
                display:block;
                width:100%;
                border-radius:999px;
                padding:11px 0;
                font-size:16px;
                font-weight:700;
                text-decoration:none;
                border:none;
                cursor:pointer;
                box-shadow:0 8px 18px rgba(0,0,0,0.18);
                background:#ff6b81;
                color:#fff;
            }
        </style>
    </head>
    <body>
        <div class="card">
            <h1>🗑 Đã xóa danh mục!</h1>
            <p>Danh mục đã được xóa khỏi hệ thống.</p>
            <a href="/Web-ban-hang/backend/categories.php" class="btn">⬅ Về trang danh mục</a>
        </div>
    </body>
    </html>
    <?php
    exit;
} else {
    echo "Không thể xóa danh mục. Có thể danh mục đang được sử dụng ở bảng sản phẩm.<br>";
    echo "Lỗi: " . $conn->error . "<br>";
    echo '<a href="categories.php">⬅ Quay lại danh mục</a>';
}

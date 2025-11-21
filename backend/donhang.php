<?php
// Hiện lỗi để tránh trắng trang
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'connect.php';

$message = "";
$success = false;

// =====================
// THÊM ĐƠN HÀNG
// =====================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    $maNguoiDung = (int)($_POST['maNguoiDung'] ?? 0);
    $maSP        = (int)($_POST['maSP'] ?? 0);           // <-- lấy maSP từ form
    $ngayDat     = trim($_POST['ngayDat'] ?? '');
    $tongTien    = (float)($_POST['tongTien'] ?? 0);
    $trangThai   = trim($_POST['trangThai'] ?? '');

    if ($maNguoiDung <= 0 || $maSP <= 0 || $ngayDat === '' || $tongTien < 0 || $trangThai === '') {
        $message = "❌ Vui lòng nhập đầy đủ thông tin đơn hàng!";
    } else {
        // CHÚ Ý: đã thêm cột maSP vào danh sách cột
        $sql = "INSERT INTO donhang (maNguoiDung, maSP, ngayDat, tongTien, trangThai)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            // maNguoiDung (int), maSP (int), ngayDat (string), tongTien (double), trangThai (string)
            $stmt->bind_param("iisds", $maNguoiDung, $maSP, $ngayDat, $tongTien, $trangThai);
            if ($stmt->execute()) {
                $message = "✅ Thêm đơn hàng mới thành công!";
                $success = true;
            } else {
                $message = "❌ Lỗi khi thêm: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "❌ Lỗi prepare: " . $conn->error;
        }
    }
}

// =====================
// LẤY DANH SÁCH ĐƠN HÀNG
// =====================
$list = [];
$sql = "SELECT * FROM donhang ORDER BY maDH DESC";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $list[] = $row;
    }
}

// =====================
// LẤY DS SẢN PHẨM CHO COMBOBOX maSP
// =====================
$dsSanPham = [];
$resSP = $conn->query("SELECT maSP, tenSP FROM sanpham ORDER BY tenSP");
if ($resSP) {
    while ($row = $resSP->fetch_assoc()) {
        $dsSanPham[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Đơn hàng</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;500;600;700&display=swap');

        body{
            margin:0;
            padding:20px 20px 70px;
            font-family:"Baloo 2",cursive;
            background:linear-gradient(135deg,#ffdde1,#ee9ca7);
            min-height:100vh;
            overflow-x:hidden;
            position:relative;
        }

        /* Snow giống index */
        .snowflakes{position:fixed;top:0;left:0;width:100%;pointer-events:none;z-index:20;}
        .snowflake{
            position:fixed;
            top:-10px;
            color:#fff;
            opacity:.95;
            font-size:22px;
            animation:snow 8s linear infinite;
        }
        @keyframes snow{
            0%{transform:translateY(-10px) rotate(0deg);opacity:0;}
            10%{opacity:1;}
            100%{transform:translateY(105vh) rotate(360deg);opacity:0;}
        }
        .snowflake:nth-child(1){left:5%;animation-duration:9s;}
        .snowflake:nth-child(2){left:15%;animation-duration:7s;}
        .snowflake:nth-child(3){left:30%;animation-duration:10s;}
        .snowflake:nth-child(4){left:50%;animation-duration:8s;}
        .snowflake:nth-child(5){left:70%;animation-duration:9s;}
        .snowflake:nth-child(6){left:85%;animation-duration:11s;}

        .topbar{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:15px;
        }
        .page-title{
            font-size:36px;
            color:#b31217;
            text-shadow:0 3px 6px rgba(0,0,0,.18);
            display:flex;
            align-items:center;
            gap:8px;
        }
        .back-btn{
            padding:8px 18px;
            border-radius:999px;
            text-decoration:none;
            background:#ff6b81;
            color:#fff;
            font-weight:600;
            box-shadow:0 3px 10px rgba(0,0,0,.25);
        }
        .back-btn:hover{background:#ff4757;}

        .message{
            margin-bottom:12px;
            padding:10px 14px;
            border-radius:14px;
            background:rgba(255,255,255,.9);
            box-shadow:0 4px 10px rgba(0,0,0,.15);
            font-size:16px;
            color:#c0392b;
        }
        .message.success{color:#27ae60;}

        .card{
            background:rgba(255,255,255,.96);
            border-radius:18px;
            padding:18px 20px;
            box-shadow:0 6px 18px rgba(0,0,0,.15);
            margin-bottom:18px;
        }
        .card h2{
            margin:0 0 10px;
            font-size:22px;
            color:#b31217;
        }
        .form-group{margin-bottom:10px;}
        label{display:block;margin-bottom:4px;font-size:15px;color:#555;}
        input[type="number"],input[type="text"],input[type="date"], select{
            width:100%;
            padding:7px 10px;
            border-radius:10px;
            border:1px solid:#ddd;
            font-size:15px;
            box-sizing:border-box;
        }
        button[type="submit"]{
            margin-top:6px;
            padding:8px 18px;
            border-radius:999px;
            border:none;
            background:#ff6b81;
            color:#fff;
            font-weight:600;
            cursor:pointer;
            box-shadow:0 3px 10px rgba(0,0,0,.2);
        }
        button[type="submit"]:hover{background:#ff4757;}

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:10px;
            background:#fff8f8;
            border-radius:16px;
            overflow:hidden;
            box-shadow:0 6px 18px rgba(0,0,0,.12);
        }
        th,td{
            padding:10px;
            text-align:center;
            font-size:15px;
        }
        th{
            background:#ff6b81;
            color:#fff;
        }
        tr:nth-child(even){background:#fff2f4;}

        .action-link{
            display:inline-flex;
            align-items:center;
            gap:4px;
            padding:5px 12px;
            margin:0 3px;
            border-radius:999px;
            font-size:13px;
            text-decoration:none;
            box-shadow:0 3px 7px rgba(0,0,0,.15);
        }
        .detail-link{background:#ffe0e9;color:#b31217;}
        .detail-link:hover{background:#ffd1e0;}
    </style>
</head>
<body>

<div class="snowflakes">
    <div class="snowflake">❄️</div><div class="snowflake">✨</div><div class="snowflake">❄️</div>
    <div class="snowflake">✨</div><div class="snowflake">❄️</div><div class="snowflake">✨</div>
</div>

<div class="topbar">
    <div class="page-title">🧾 Quản lý Đơn hàng</div>
    <a class="back-btn" href="../index.html">⬅ Quay lại trang chính</a>
</div>

<?php if ($message !== ""): ?>
    <div class="message <?php echo $success ? 'success' : ''; ?>">
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<!-- FORM THÊM ĐƠN HÀNG -->
<div class="card">
    <h2>➕ Thêm đơn hàng mới</h2>
    <form method="post">
        <input type="hidden" name="action" value="add">

        <div class="form-group">
            <label>Mã người dùng (maNguoiDung):</label>
            <input type="number" name="maNguoiDung" required>
        </div>

        <!-- CHỌN SẢN PHẨM (maSP) -->
        <div class="form-group">
            <label>Mã sản phẩm (maSP):</label>
            <select name="maSP" required>
                <option value="">-- Chọn sản phẩm --</option>
                <?php foreach ($dsSanPham as $sp): ?>
                    <option value="<?= $sp['maSP']; ?>">
                        <?= $sp['maSP']; ?> - <?= htmlspecialchars($sp['tenSP']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Ngày đặt:</label>
            <input type="date" name="ngayDat" required>
        </div>

        <div class="form-group">
            <label>Tổng tiền:</label>
            <input type="number" step="0.01" name="tongTien" required>
        </div>

        <div class="form-group">
            <label>Trạng thái (VD: Đang xử lý / Hoàn tất / Hủy):</label>
            <input type="text" name="trangThai" required>
        </div>

        <button type="submit">✅ Thêm đơn hàng</button>
    </form>
</div>

<!-- DANH SÁCH ĐƠN HÀNG -->
<div class="card">
    <h2>📃 Danh sách đơn hàng</h2>
    <table>
        <thead>
        <tr>
            <th>maDH</th>
            <th>maNguoiDung</th>
            <th>maSP</th>
            <th>ngayDat</th>
            <th>tongTien</th>
            <th>trangThai</th>
            <th>Chi tiết</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($list)): ?>
            <tr><td colspan="7">Chưa có đơn hàng.</td></tr>
        <?php else: ?>
            <?php foreach ($list as $r): ?>
                <tr>
                    <td><?php echo $r['maDH']; ?></td>
                    <td><?php echo $r['maNguoiDung']; ?></td>
                    <td><?php echo $r['maSP']; ?></td>
                    <td><?php echo $r['ngayDat']; ?></td>
                    <td><?php echo $r['tongTien']; ?></td>
                    <td><?php echo htmlspecialchars($r['trangThai']); ?></td>
                    <td>
                        <!-- NÚT XEM CHI TIẾT: TRUYỀN maDH SANG chitietdonhang.php -->
                        <a class="action-link detail-link"
                           href="chitietdonhang.php?maDH=<?php echo $r['maDH']; ?>">
                           🔍 Xem chi tiết
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>

<?php
// HIỆN LỖI (tránh trắng trang)
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'connect.php';

$message = "";
$success  = false; // để biết có thao tác thành công hay không

// =====================
// XỬ LÝ THÊM MỚI
// =====================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    $maDH    = (int)($_POST['maDH'] ?? 0);
    $maSP    = (int)($_POST['maSP'] ?? 0);
    $soLuong = (int)($_POST['soLuong'] ?? 0);
    $donGia  = (float)($_POST['donGia'] ?? 0);

    if ($maDH <= 0 || $maSP <= 0 || $soLuong <= 0 || $donGia <= 0) {
        $message = "❌ Vui lòng nhập đầy đủ và đúng các trường!";
    } else {
        $sql = "INSERT INTO chitietdonhang (maDH, maSP, soLuong, donGia)
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("iiid", $maDH, $maSP, $soLuong, $donGia);
            if ($stmt->execute()) {
                $message = "✅ Thêm chi tiết đơn hàng thành công!";
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
// XỬ LÝ SỬA
// =====================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update') {
    $maCT    = (int)($_POST['maCT'] ?? 0);
    $maDH    = (int)($_POST['maDH'] ?? 0);
    $maSP    = (int)($_POST['maSP'] ?? 0);
    $soLuong = (int)($_POST['soLuong'] ?? 0);
    $donGia  = (float)($_POST['donGia'] ?? 0);

    if ($maCT <= 0 || $maDH <= 0 || $maSP <= 0 || $soLuong <= 0 || $donGia <= 0) {
        $message = "❌ Dữ liệu không hợp lệ!";
    } else {
        $sql = "UPDATE chitietdonhang
                SET maDH = ?, maSP = ?, soLuong = ?, donGia = ?
                WHERE maCT = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("iiidi", $maDH, $maSP, $soLuong, $donGia, $maCT);
            if ($stmt->execute()) {
                $message = "✅ Cập nhật chi tiết đơn hàng thành công!";
                $success = true;
            } else {
                $message = "❌ Lỗi khi cập nhật: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "❌ Lỗi prepare: " . $conn->error;
        }
    }
}

// =====================
// XỬ LÝ XÓA
// =====================
if (isset($_GET['delete_id'])) {
    $maCT_del = (int)$_GET['delete_id'];
    if ($maCT_del > 0) {
        $sql = "DELETE FROM chitietdonhang WHERE maCT = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $maCT_del);
            if ($stmt->execute()) {
                $message = "✅ Đã xóa chi tiết đơn hàng (maCT = $maCT_del).";
                $success = true;
            } else {
                $message = "❌ Lỗi khi xóa: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "❌ Lỗi prepare: " . $conn->error;
        }
    }
}

// =====================
// LẤY DỮ LIỆU ĐỂ SỬA
// =====================
$editRow = null;
if (isset($_GET['edit_id'])) {
    $edit_id = (int)$_GET['edit_id'];
    if ($edit_id > 0) {
        $sql = "SELECT * FROM chitietdonhang WHERE maCT = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $edit_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $editRow = $result->fetch_assoc();
            $stmt->close();
        }
    }
}

// =====================
// LẤY DANH SÁCH CHI TIẾT
// =====================
$list = [];
$sql = "SELECT * FROM chitietdonhang ORDER BY maCT DESC";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $list[] = $row;
    }
}

// =====================
// LẤY DS ĐƠN HÀNG & SẢN PHẨM CHO COMBOBOX
// =====================
$dsDonHang = [];
$resDH = $conn->query("SELECT maDH FROM donhang ORDER BY maDH DESC");
if ($resDH) {
    while ($row = $resDH->fetch_assoc()) {
        $dsDonHang[] = $row;
    }
}

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
    <title>Quản lý Chi tiết đơn hàng</title>
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
        input[type="number"], select{
            width:100%;
            padding:7px 10px;
            border-radius:10px;
            border:1px solid #ddd;
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
        .edit-link{background:#ffe0e9;color:#b31217;}
        .edit-link:hover{background:#ffd1e0;}
        .delete-link{background:#ff7979;color:#fff;}
        .delete-link:hover{background:#ff5c5c;}
    </style>
</head>
<body>

<div class="snowflakes">
    <div class="snowflake">❄️</div><div class="snowflake">✨</div><div class="snowflake">❄️</div>
    <div class="snowflake">✨</div><div class="snowflake">❄️</div><div class="snowflake">✨</div>
</div>

<div class="topbar">
    <div class="page-title">📑 Quản lý Chi tiết đơn hàng</div>
    <!-- NÚT BACK VỀ INDEX -->
    <a class="back-btn" href="../index.html">⬅ Quay lại trang chính</a>
</div>

<?php if ($message !== ""): ?>
    <div class="message <?php echo $success ? 'success' : ''; ?>">
        <?php echo htmlspecialchars($message); ?>
        <?php if ($success): ?>
            &nbsp;|&nbsp; <a href="../index.html" style="color:#2980b9;text-decoration:none;font-weight:600;">Quay lại trang chính</a>
        <?php endif; ?>
    </div>
<?php endif; ?>

<!-- FORM THÊM / SỬA -->
<div class="card">
    <?php if ($editRow): ?>
        <h2>✏️ Sửa chi tiết đơn hàng (maCT = <?php echo $editRow['maCT']; ?>)</h2>
        <form method="post">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="maCT" value="<?php echo $editRow['maCT']; ?>">

            <div class="form-group">
                <label>Mã đơn hàng (maDH):</label>
                <select name="maDH" required>
                    <?php foreach ($dsDonHang as $dh): ?>
                        <option value="<?= $dh['maDH']; ?>"
                            <?= ($dh['maDH'] == $editRow['maDH']) ? 'selected' : '' ?>>
                            Đơn hàng #<?= $dh['maDH']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Mã sản phẩm (maSP):</label>
                <select name="maSP" required>
                    <?php foreach ($dsSanPham as $sp): ?>
                        <option value="<?= $sp['maSP']; ?>"
                            <?= ($sp['maSP'] == $editRow['maSP']) ? 'selected' : '' ?>>
                            <?= $sp['maSP']; ?> - <?= htmlspecialchars($sp['tenSP']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Số lượng:</label>
                <input type="number" name="soLuong"
                       value="<?php echo (int)$editRow['soLuong']; ?>" min="1" required>
            </div>

            <div class="form-group">
                <label>Đơn giá:</label>
                <input type="number" step="0.01" name="donGia"
                       value="<?php echo (float)$editRow['donGia']; ?>" min="0" required>
            </div>

            <button type="submit">💾 Lưu cập nhật</button>
        </form>
    <?php else: ?>
        <h2>➕ Thêm chi tiết đơn hàng mới</h2>
        <form method="post">
            <input type="hidden" name="action" value="add">

            <div class="form-group">
                <label>Mã đơn hàng (maDH):</label>
                <select name="maDH" required>
                    <option value="">-- Chọn đơn hàng --</option>
                    <?php foreach ($dsDonHang as $dh): ?>
                        <option value="<?= $dh['maDH']; ?>">
                            Đơn hàng #<?= $dh['maDH']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

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
                <label>Số lượng:</label>
                <input type="number" name="soLuong" min="1" required>
            </div>

            <div class="form-group">
                <label>Đơn giá:</label>
                <input type="number" step="0.01" name="donGia" min="0" required>
            </div>

            <button type="submit">✅ Thêm</button>
        </form>
    <?php endif; ?>
</div>

<!-- DANH SÁCH -->
<div class="card">
    <h2>📃 Danh sách chi tiết đơn hàng</h2>
    <table>
        <thead>
        <tr>
            <th>maCT</th>
            <th>maDH</th>
            <th>maSP</th>
            <th>Số lượng</th>
            <th>Đơn giá</th>
            <th>Thao tác</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($list)): ?>
            <tr><td colspan="6">Chưa có dữ liệu.</td></tr>
        <?php else: ?>
            <?php foreach ($list as $r): ?>
                <tr>
                    <td><?php echo $r['maCT']; ?></td>
                    <td><?php echo $r['maDH']; ?></td>
                    <td><?php echo $r['maSP']; ?></td>
                    <td><?php echo $r['soLuong']; ?></td>
                    <td><?php echo $r['donGia']; ?></td>
                    <td>
                        <a class="action-link edit-link"
                           href="chitietdonhang.php?edit_id=<?php echo $r['maCT']; ?>">Sửa</a>
                        <a class="action-link delete-link"
                           href="chitietdonhang.php?delete_id=<?php echo $r['maCT']; ?>"
                           onclick="return confirm('Xóa chi tiết maCT = <?php echo $r['maCT']; ?> ?');">
                           Xóa
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

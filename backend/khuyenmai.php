<?php
// HIỆN LỖI
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'connect.php';

$message = "";
$success = false;

// =====================
// XỬ LÝ THÊM / SỬA
// =====================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action      = $_POST['action'] ?? '';
    $maKM        = (int)($_POST['maKM'] ?? 0);
    $tenKM       = trim($_POST['tenKM'] ?? '');
    $ngayBatDau  = trim($_POST['ngayBatDau'] ?? '');
    $ngayKetThuc = trim($_POST['ngayKetThuc'] ?? '');

    if ($tenKM === '' || $ngayBatDau === '' || $ngayKetThuc === '') {
        $message = "❌ Vui lòng nhập đầy đủ thông tin khuyến mãi!";
    } else {
        // THÊM
        if ($action === 'add') {
            $sql = "INSERT INTO khuyenmai (tenKM, ngayBatDau, ngayKetThuc)
                    VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("sss", $tenKM, $ngayBatDau, $ngayKetThuc);
                if ($stmt->execute()) {
                    $message = "✅ Thêm khuyến mãi thành công!";
                    $success = true;
                } else {
                    $message = "❌ Lỗi khi thêm: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $message = "❌ Lỗi prepare: " . $conn->error;
            }

        // SỬA
        } elseif ($action === 'update' && $maKM > 0) {
            $sql = "UPDATE khuyenmai
                    SET tenKM = ?, ngayBatDau = ?, ngayKetThuc = ?
                    WHERE maKM = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("sssi", $tenKM, $ngayBatDau, $ngayKetThuc, $maKM);
                if ($stmt->execute()) {
                    $message = "✅ Cập nhật khuyến mãi thành công!";
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
}

// =====================
// XÓA
// =====================
if (isset($_GET['delete_id'])) {
    $del = (int)$_GET['delete_id'];
    if ($del > 0) {
        $stmt = $conn->prepare("DELETE FROM khuyenmai WHERE maKM = ?");
        if ($stmt) {
            $stmt->bind_param("i", $del);
            if ($stmt->execute()) {
                $message = "✅ Đã xóa khuyến mãi (maKM = $del).";
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
// LẤY THÔNG TIN ĐỂ SỬA
// =====================
$editRow = null;
if (isset($_GET['edit_id'])) {
    $eid = (int)$_GET['edit_id'];
    if ($eid > 0) {
        $stmt = $conn->prepare("SELECT * FROM khuyenmai WHERE maKM = ?");
        if ($stmt) {
            $stmt->bind_param("i", $eid);
            $stmt->execute();
            $res = $stmt->get_result();
            $editRow = $res->fetch_assoc();
            $stmt->close();
        }
    }
}

// =====================
// DANH SÁCH KHUYẾN MÃI
// =====================
$list = [];
$res = $conn->query("SELECT * FROM khuyenmai ORDER BY maKM DESC");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $list[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Khuyến mãi</title>
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
        input[type="text"],input[type="date"]{
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
    <div class="page-title">🎁 Quản lý Khuyến mãi</div>
    <!-- NÚT BACK VỀ INDEX.HTML -->
    <a class="back-btn" href="../index.html">⬅ Quay lại trang chính</a>
</div>

<?php if ($message !== ""): ?>
    <div class="message <?php echo $success ? 'success' : ''; ?>">
        <?php echo htmlspecialchars($message); ?>
        <?php if ($success): ?>
            &nbsp;|&nbsp;<a href="../index.html" style="color:#2980b9;text-decoration:none;font-weight:600;">
                Quay lại trang chính
            </a>
        <?php endif; ?>
    </div>
<?php endif; ?>

<!-- FORM THÊM / SỬA -->
<div class="card">
    <?php if ($editRow): ?>
        <h2>✏️ Sửa khuyến mãi (maKM = <?= $editRow['maKM']; ?>)</h2>
        <form method="post">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="maKM" value="<?= $editRow['maKM']; ?>">

            <div class="form-group">
                <label>Tên khuyến mãi:</label>
                <input type="text" name="tenKM" value="<?= htmlspecialchars($editRow['tenKM']); ?>" required>
            </div>

            <div class="form-group">
                <label>Ngày bắt đầu:</label>
                <input type="date" name="ngayBatDau" value="<?= $editRow['ngayBatDau']; ?>" required>
            </div>

            <div class="form-group">
                <label>Ngày kết thúc:</label>
                <input type="date" name="ngayKetThuc" value="<?= $editRow['ngayKetThuc']; ?>" required>
            </div>

            <button type="submit">💾 Lưu cập nhật</button>
        </form>
    <?php else: ?>
        <h2>➕ Thêm khuyến mãi mới</h2>
        <form method="post">
            <input type="hidden" name="action" value="add">

            <div class="form-group">
                <label>Tên khuyến mãi:</label>
                <input type="text" name="tenKM" required>
            </div>

            <div class="form-group">
                <label>Ngày bắt đầu:</label>
                <input type="date" name="ngayBatDau" required>
            </div>

            <div class="form-group">
                <label>Ngày kết thúc:</label>
                <input type="date" name="ngayKetThuc" required>
            </div>

            <button type="submit">✅ Thêm khuyến mãi</button>
        </form>
    <?php endif; ?>
</div>

<!-- DANH SÁCH KHUYẾN MÃI -->
<div class="card">
    <h2>📃 Danh sách khuyến mãi</h2>
    <table>
        <thead>
        <tr>
            <th>maKM</th>
            <th>tenKM</th>
            <th>ngayBatDau</th>
            <th>ngayKetThuc</th>
            <th>Thao tác</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($list)): ?>
            <tr><td colspan="5">Chưa có khuyến mãi.</td></tr>
        <?php else: ?>
            <?php foreach ($list as $r): ?>
                <tr>
                    <td><?= $r['maKM']; ?></td>
                    <td><?= htmlspecialchars($r['tenKM']); ?></td>
                    <td><?= $r['ngayBatDau']; ?></td>
                    <td><?= $r['ngayKetThuc']; ?></td>
                    <td>
                        <a class="action-link edit-link"
                           href="khuyenmai.php?edit_id=<?= $r['maKM']; ?>">Sửa</a>
                        <a class="action-link delete-link"
                           href="khuyenmai.php?delete_id=<?= $r['maKM']; ?>"
                           onclick="return confirm('Xóa khuyến mãi maKM = <?= $r['maKM']; ?> ?');">
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

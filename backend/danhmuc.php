<?php
// danhmuc.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/connect.php';

$message = "";

// =====================
// XỬ LÝ THÊM DANH MỤC
// =====================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tenDM = trim($_POST['tenDM'] ?? '');
    $moTa  = trim($_POST['moTa']  ?? '');

    if ($tenDM === '') {
        $message = "❌ Tên danh mục không được để trống!";
    } else {
        $sql = "INSERT INTO danhmuc (tenDM, moTa) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ss", $tenDM, $moTa);
            if ($stmt->execute()) {
                $message = "✅ Thêm danh mục mới thành công!";
            } else {
                $message = "❌ Lỗi khi thêm danh mục: " . $conn->error;
            }
            $stmt->close();
        } else {
            $message = "❌ Lỗi prepare statement: " . $conn->error;
        }
    }
}

// Lấy danh sách danh mục (sau khi thêm xong cũng load lại)
$sql  = "SELECT maDM, tenDM, moTa FROM danhmuc ORDER BY maDM ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Danh mục – Cartoon Cute</title>
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

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }
        .title {
            font-size: 34px;
            color: #b31217;
            display: flex;
            align-items: center;
            gap: 8px;
            text-shadow: 0 3px 6px rgba(0,0,0,0.18);
        }
        .top-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .back-btn,
        .btn-add {
            padding: 10px 20px;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.18);
            border: none;
        }
        .back-btn {
            background: #2ecc71;
            color: #ffffff;
        }
        .btn-add {
            background: #ff9f43;
            color: #ffffff;
        }
        .back-btn:hover,
        .btn-add:hover {
            transform: translateY(-1px);
        }

        /* CARD THÊM DANH MỤC */
        .add-card {
            width: 100%;
            max-width: 520px;
            background: #fff8f8;
            border-radius: 24px;
            padding: 16px 20px 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.16);
            margin-bottom: 18px;
        }
        .add-title {
            font-size: 20px;
            color: #e67e22;
            margin-bottom: 6px;
        }
        .add-sub {
            font-size: 13px;
            color: #7f8c8d;
            margin-bottom: 10px;
        }
        label {
            display: block;
            font-size: 14px;
            color: #c0392b;
            margin-bottom: 4px;
        }
        .input, .textarea {
            width: 100%;
            border-radius: 14px;
            border: 2px solid #ffd6dc;
            padding: 6px 10px;
            font-size: 14px;
            font-family: inherit;
            outline: none;
            background: #ffffff;
            transition: 0.15s;
        }
        .textarea {
            resize: vertical;
            min-height: 60px;
        }
        .input:focus, .textarea:focus {
            border-color: #ff6b81;
            box-shadow: 0 0 0 2px rgba(255,107,129,0.2);
        }
        .add-actions {
            margin-top: 10px;
            display: flex;
            gap: 8px;
        }
        .btn-save {
            flex: 1;
            border: none;
            border-radius: 999px;
            background: linear-gradient(135deg,#ffb347,#ff6b6b);
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            padding: 8px 0;
            cursor: pointer;
            box-shadow: 0 5px 14px rgba(0,0,0,0.18);
        }
        .btn-cancel {
            padding: 8px 14px;
            border-radius: 999px;
            border: 2px solid #bdc3c7;
            background: transparent;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            color: #555;
        }

        .message {
            margin-bottom: 10px;
            font-size: 14px;
        }
        .message.ok { color: #27ae60; }
        .message.error { color: #e74c3c; }

        .table-wrapper{
            margin-top: 10px;
        }

        table{
            width: 100%;
            border-collapse: collapse;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 24px rgba(0,0,0,0.15);
        }
        thead{
            background:#ff6b81;
            color:#fff;
        }
        th,td{
            padding:14px 18px;
            font-size:15px;
            text-align:left;
        }
        tbody tr:nth-child(even){
            background:#fff5f6;
        }
        tbody tr:nth-child(odd){
            background:#ffffff;
        }

        .action-btn{
            display:inline-flex;
            align-items:center;
            gap:6px;
            padding:6px 14px;
            border-radius:999px;
            border:none;
            text-decoration:none;
            font-size:14px;
            font-weight:600;
            cursor:pointer;
            box-shadow:0 4px 10px rgba(0,0,0,0.15);
        }
        .btn-edit{background:#ff9f43;color:#fff;}
        .btn-delete{background:#ff4757;color:#fff;margin-left:6px;}
    </style>
</head>
<body>

<div class="topbar">
    <div class="title">📂 Quản lý Danh mục</div>

    <div class="top-right">
        <!-- NÚT THÊM DANH MỤC MỚI: cuộn xuống form -->
        <a href="#add-form" class="btn-add">➕ Thêm danh mục</a>

        <!-- NÚT VỀ TRANG SẢN PHẨM -->
        <a href="../index.html" class="back-btn">⬅ Về trang sản phẩm</a>
    </div>
</div>

<!-- FORM THÊM DANH MỤC -->
<div id="add-form" class="add-card">
    <div class="add-title">➕ Thêm danh mục mới</div>
    <div class="add-sub">Tạo nhóm món ăn / thức uống mới cho cửa hàng 🎄</div>

    <?php if ($message): ?>
        <div class="message <?= (strpos($message,'✅')!==false) ? 'ok' : 'error' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <label for="tenDM">Tên danh mục</label>
        <input class="input" type="text" id="tenDM" name="tenDM" required>

        <label for="moTa">Mô tả</label>
        <textarea class="textarea" id="moTa" name="moTa"
                  placeholder="Ví dụ: Hamburger, Pizza, Gà rán..."></textarea>

        <div class="add-actions">
            <button type="submit" class="btn-save">Lưu danh mục</button>
            <button type="button" class="btn-cancel"
                    onclick="window.location.href='danhmuc.php'">Hủy</button>
        </div>
    </form>
</div>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên danh mục</th>
                <th>Mô tả</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['maDM']; ?></td>
                    <td><?= htmlspecialchars($row['tenDM']); ?></td>
                    <td><?= htmlspecialchars($row['moTa']); ?></td>
                    <td>
                        <a class="action-btn btn-edit"
                           href="edit_danhmuc.php?id=<?= $row['maDM']; ?>">✏️ Sửa</a>
                        <a class="action-btn btn-delete"
                           href="deletedanhmuc.php?id=<?= $row['maDM']; ?>"
                           onclick="return confirm('Bạn có chắc muốn xóa danh mục này không?');">
                           🗑 Xóa
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4">Chưa có danh mục nào.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>

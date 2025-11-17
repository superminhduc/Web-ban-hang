<?php
include 'connect.php';

// 1. Kiểm tra id có tồn tại & hợp lệ không
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("❌ ID không hợp lệ hoặc không được truyền vào!");
}

$id = (int)$_GET['id']; // ép kiểu int cho chắc

// 2. Lấy sản phẩm theo ID
$sql = "SELECT * FROM sanpham WHERE maSP = $id";
$result = $conn->query($sql);

// Kiểm tra lỗi query
if (!$result) {
    die("❌ Lỗi truy vấn: " . $conn->error);
}

// Không có sản phẩm
if ($result->num_rows === 0) {
    die("❌ Không tìm thấy sản phẩm có ID = $id");
}

$row = $result->fetch_assoc();

// 3. Nếu bấm nút Cập nhật
if (isset($_POST['update'])) {
    $tenSP   = $_POST['tenSP'] ?? '';
    $gia     = $_POST['gia'] ?? 0;
    $moTa    = $_POST['moTa'] ?? '';
    $hinhAnh = $_POST['hinhAnh'] ?? '';
    $soLuong = $_POST['soLuong'] ?? 0;

    // Ép kiểu số cho các trường số
    $gia     = (int)$gia;
    $soLuong = (int)$soLuong;

    // Lưu ý: đây là cách đơn giản, làm bài học OK
    // Nếu làm thực tế nên dùng prepared statement để chống SQL Injection
    $sqlUpdate = "
        UPDATE sanpham 
        SET tenSP='$tenSP', gia=$gia, moTa='$moTa', hinhAnh='$hinhAnh', soLuong=$soLuong 
        WHERE maSP=$id
    ";

    if ($conn->query($sqlUpdate) === TRUE) {
        echo "✅ Cập nhật thành công! Đang quay lại danh sách...";
        // Dùng JS redirect thay vì header (vì đã echo ở trên)
        echo "<script>
                setTimeout(function() {
                    window.location.href = '../index.html';
                }, 1000);
              </script>";
    } else {
        echo "❌ Lỗi khi cập nhật: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Sửa sản phẩm</title>
  <link rel="stylesheet" href="../css/edit.css">
</head>
<body>
  <h1>✏️ Sửa sản phẩm</h1>

  <form method="post">
    Tên sản phẩm: 
    <input type="text" name="tenSP" value="<?php echo htmlspecialchars($row['tenSP']); ?>"><br><br>

    Giá: 
    <input type="number" name="gia" value="<?php echo $row['gia']; ?>"><br><br>

    Mô tả: 
    <input type="text" name="moTa" value="<?php echo htmlspecialchars($row['moTa']); ?>"><br><br>

    Hình ảnh: 
    <input type="text" name="hinhAnh" value="<?php echo htmlspecialchars($row['hinhAnh']); ?>"><br><br>

    Số lượng: 
    <input type="number" name="soLuong" value="<?php echo $row['soLuong']; ?>"><br><br>

    <input type="submit" name="update" value="Cập nhật">
  </form>

  <p>
    <a href="../index.html">⬅ Quay lại danh sách sản phẩm</a>
  </p>
</body>
</html>
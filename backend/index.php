<<<<<<< HEAD
<?php include 'connect.php'; ?>

=======
<?php
// Bật hiển thị lỗi
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Kết nối CSDL
require_once 'connect.php';
?>
>>>>>>> 83a4e0f (second commit)
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quản lý Sản phẩm</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>📦 Quản lý Sản phẩm</h1>
  <a href="add.php" class="btn-add">➕ Thêm sản phẩm</a>

  <table border="1" cellpadding="10" cellspacing="0">
    <tr>
      <th>ID</th>
      <th>Tên sản phẩm</th>
      <th>Giá</th>
      <th>Mô tả</th>
      <th>Hình ảnh</th>
      <th>Số lượng</th>
      <th>Thao tác</th>
    </tr>

    <?php
    $sql = "SELECT * FROM sanpham";
    $result = $conn->query($sql);
<<<<<<< HEAD
    if ($result->num_rows > 0) {
=======

    if ($result && $result->num_rows > 0) {
>>>>>>> 83a4e0f (second commit)
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['maSP']}</td>
                    <td>{$row['tenSP']}</td>
                    <td>" . number_format($row['gia']) . "₫</td>
                    <td>{$row['moTa']}</td>
                    <td>{$row['hinhAnh']}</td>
                    <td>{$row['soLuong']}</td>
                    <td>
                      <a href='edit.php?id={$row['maSP']}'>✏️ Sửa</a> |
                      <a href='delete.php?id={$row['maSP']}' onclick='return confirm(\"Xóa sản phẩm này?\")'>🗑️ Xóa</a>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='7'>Không có sản phẩm nào</td></tr>";
    }
    ?>
  </table>
</body>
</html>

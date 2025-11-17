<?php include 'connect.php'; ?>
<?php
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM sanpham WHERE maSP=$id");
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Sửa sản phẩm</title>
</head>
<body>
  <h1>✏️ Sửa sản phẩm</h1>
  <form method="post">
    Tên sản phẩm: <input type="text" name="tenSP" value="<?php echo $row['tenSP']; ?>"><br>
    Giá: <input type="number" name="gia" value="<?php echo $row['gia']; ?>"><br>
    Mô tả: <input type="text" name="moTa" value="<?php echo $row['moTa']; ?>"><br>
    Hình ảnh: <input type="text" name="hinhAnh" value="<?php echo $row['hinhAnh']; ?>"><br>
    Số lượng: <input type="number" name="soLuong" value="<?php echo $row['soLuong']; ?>"><br>
    <input type="submit" name="update" value="Cập nhật">
  </form>

  <?php
  if (isset($_POST['update'])) {
      $tenSP = $_POST['tenSP'];
      $gia = $_POST['gia'];
      $moTa = $_POST['moTa'];
      $hinhAnh = $_POST['hinhAnh'];
      $soLuong = $_POST['soLuong'];

      $sql = "UPDATE sanpham SET tenSP='$tenSP', gia='$gia', moTa='$moTa', hinhAnh='$hinhAnh', soLuong='$soLuong' WHERE maSP=$id";
      if ($conn->query($sql) === TRUE) {
          echo "✅ Cập nhật thành công!";
          header("refresh:1; url=index.php");
      } else {
          echo "❌ Lỗi: " . $conn->error;
      }
  }
  ?>
</body>
</html>

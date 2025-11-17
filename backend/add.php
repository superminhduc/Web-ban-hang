<?php include 'connect.php'; ?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Thêm sản phẩm</title>
</head>
<body>
  <h1>➕ Thêm sản phẩm mới</h1>
  <form method="post">
    Tên sản phẩm: <input type="text" name="tenSP" required><br>
    Giá: <input type="number" name="gia" required><br>
    Mô tả: <input type="text" name="moTa"><br>
    Hình ảnh: <input type="text" name="hinhAnh"><br>
    Mã danh mục: <input type="number" name="maDM" required><br>
    Số lượng: <input type="number" name="soLuong" value="0"><br>
    <input type="submit" name="submit" value="Thêm">
  </form>

  <?php
  if (isset($_POST['submit'])) {
      $tenSP = $_POST['tenSP'];
      $gia = $_POST['gia'];
      $moTa = $_POST['moTa'];
      $hinhAnh = $_POST['hinhAnh'];
      $maDM = $_POST['maDM'];
      $soLuong = $_POST['soLuong'];

      $sql = "INSERT INTO sanpham (tenSP, gia, moTa, hinhAnh, maDM, soLuong)
              VALUES ('$tenSP', '$gia', '$moTa', '$hinhAnh', '$maDM', '$soLuong')";
      if ($conn->query($sql) === TRUE) {
          echo "✅ Thêm thành công!";
          header("refresh:1; url=/trangweb/index.html");
      } else {
          echo "❌ Lỗi: " . $conn->error;
      }
  }
  ?>
</body>
</html>

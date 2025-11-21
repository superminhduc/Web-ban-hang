<?php
<<<<<<< HEAD
=======
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: /Web-ban-hang/login.html");
    exit();
}

// Lấy role từ session
$role = $_SESSION['role'] ?? 'employee';

>>>>>>> NguyenMinhDuc
// Kết nối database
include 'connect.php';

// Câu lệnh lấy toàn bộ sản phẩm trong bảng sanpham
$sql = "SELECT * FROM sanpham";
$result = $conn->query($sql);

// Duyệt từng dòng dữ liệu (mỗi dòng là 1 sản phẩm)
while ($row = $result->fetch_assoc()) {

    echo "<tr>";

    // Mã sản phẩm
    echo "<td>{$row['maSP']}</td>";

    // Tên sản phẩm (dùng htmlspecialchars để tránh lỗi XSS)
    echo "<td>" . htmlspecialchars($row['tenSP']) . "</td>";

    // Giá sản phẩm (định dạng số kèm ký hiệu VNĐ)
    echo "<td>" . number_format($row['gia']) . "₫</td>";

    // Mô tả (cũng escape để an toàn)
    echo "<td>" . htmlspecialchars($row['moTa']) . "</td>";

    // -----------------------------
    // HIỂN THỊ HÌNH ẢNH SẢN PHẨM
    // -----------------------------
    $img = trim($row['hinhAnh']);  // Lấy đường dẫn hình

    echo "<td>";

    if ($img !== '') {

        // Nếu hình ảnh là URL đầy đủ (http/https)
        if (!preg_match('#^https?://#i', $img)) {

            // Nếu chỉ là tên file → gắn thêm đường dẫn thư mục ảnh
            // Thay đường dẫn theo cấu trúc project của bạn
            $img = '/Web-ban-hang/uploads/' . $img;
        }

        // Hiển thị hình
        echo '<img src="' . htmlspecialchars($img) . '" 
                   alt="' . htmlspecialchars($row['tenSP']) . '" 
                   style="width:120px; height:80px; object-fit:cover; border-radius:8px;">';

    } else {

        // Không có hình
        echo "—";
    }

    echo "</td>";

    // Số lượng tồn kho
    echo "<td>{$row['soLuong']}</td>";

    // -----------------------------
<<<<<<< HEAD
    // CÁC NÚT THAO TÁC: SỬA / XÓA
    // -----------------------------
echo "<td>
        <a href=\"backend/edit.php?id={$row['maSP']}\">Sửa</a> | 
        <a href=\"backend/delete_confirm.php?id={$row['maSP']}\">Xóa</a>
      </td>";
=======
    // CÁC NÚT THAO TÁC: SỬA / XÓA (chỉ admin mới thấy)
    // -----------------------------
    echo "<td>";
    if ($role === 'admin') {
        echo "<a href=\"backend/edit.php?id={$row['maSP']}\">Sửa</a> | 
              <a href=\"backend/delete_confirm.php?id={$row['maSP']}\">Xóa</a>";
    } else {
        echo "—"; // Nhân viên chỉ xem
    }
    echo "</td>";
>>>>>>> NguyenMinhDuc
    echo "</tr>";
}
?>

<?php include 'connect.php'; ?>

<?php
$sql = "SELECT * FROM sanpham";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['maSP']}</td>
                <td>{$row['tenSP']}</td>
                <td>" . number_format($row['gia']) . "₫</td>
                <td>{$row['moTa']}</td>
                <td>{$row['hinhAnh']}</td>
                <td>{$row['soLuong']}</td>
                <td>
                  <a href='../backend/edit.php?id={$row['maSP']}'>✏️ Sửa</a> |
                  <a href='../backend/delete.php?id={$row['maSP']}' onclick='return confirm(\"Xóa sản phẩm này?\")'>🗑️ Xóa</a>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='7'>Không có sản phẩm nào</td></tr>";
}
?>

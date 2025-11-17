// Gọi API PHP để lấy danh sách sản phẩm
function loadProducts() {
    const tbody = document.getElementById('product-body');
    tbody.innerHTML = '<tr><td colspan="6">Đang tải dữ liệu...</td></tr>';

    // Nếu project là "trangweb", api nằm ở: /trangweb/backend/api_sanpham.php
    fetch('backend/api_sanpham.php')
        .then(res => {
            if (!res.ok) {
                throw new Error('HTTP ' + res.status);
            }
            return res.json();
        })
        .then(list => {
            if (!Array.isArray(list) || list.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6">Không có sản phẩm nào</td></tr>';
                return;
            }

            tbody.innerHTML = '';
            list.forEach(sp => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${sp.maSP}</td>
                    <td>${sp.tenSP}</td>
                    <td>${Number(sp.gia).toLocaleString('vi-VN')}₫</td>
                    <td>${sp.moTa ?? ''}</td>
                    <td>${sp.hinhAnh ?? ''}</td>
                    <td>${sp.soLuong}</td>
                `;
                tbody.appendChild(tr);
            });
        })
        .catch(err => {
            console.error('Lỗi fetch:', err);
            tbody.innerHTML = '<tr><td colspan="6">Lỗi khi tải dữ liệu</td></tr>';
        });
}

// nút reload
document.getElementById('reloadBtn').addEventListener('click', loadProducts);

// tải lần đầu
loadProducts();

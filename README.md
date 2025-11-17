README - ĐỒ ÁN LẬP TRÌNH ỨNG DỤNG WEB
====================================

NHÓM: 6
HỌC PHẦN: Lập Trình Ứng Dụng Web
Giảng viên: Trần Công Thanh

----------------------------------------------------------------------
I. THÔNG TIN THÀNH VIÊN
----------------------------------------------------------------------
- 2474802010274 – Trần Phước Nhân – Nhóm trưởng (database - triển khai)
- 2474802010090 – Nguyễn Minh Đức – Thành viên (frontend)
- 2474802010571 – Nguyễn Phan Vĩnh Phát – Thành viên (backend)

----------------------------------------------------------------------
II. MÔ TẢ ĐỀ TÀI
----------------------------------------------------------------------
Tên đề tài: Quản lý bán đồ ăn nhanh

Mô tả ngắn:
Website mô phỏng hệ thống quản lý dữ liệu thực tế sử dụng  HTML5, CSS3, JavaScript, PHP và MySQL. Hỗ trợ chức năng đăng nhập, thêm/xóa/sửa/tìm kiếm dữ liệu và thống kê cơ bản.

----------------------------------------------------------------------
III. CÁCH CÀI ĐẶT & CHẠY DỰ ÁN (LOCALHOST - XAMPP)
----------------------------------------------------------------------
1. Cài đặt XAMPP
2. Copy toàn bộ thư mục SourceCode vào:
   htdocs/Web-ban-hang/
3. Khởi động Apache và MySQL
4. Import Database:
   - Mở phpMyAdmin
   - Tạo database mới: qlbandoannhanh3
   - Import file: Database/qlbandoannhanh3.sql
5. Chạy dự án:
   http://localhost:8080/Web-ban-hang/login.html

----------------------------------------------------------------------
IV. TÀI KHOẢN ĐĂNG NHẬP
----------------------------------------------------------------------
Ví dụ (cập nhật theo nhóm):
- admin / 123456

----------------------------------------------------------------------
V. LINK TRIỂN KHAI ONLINE (FREE HOST)
----------------------------------------------------------------------
URL: https://_____________________________________

----------------------------------------------------------------------
VI. LINK GITHUB (BẮT BUỘC)
----------------------------------------------------------------------
Repo chính (public): 
https://github.com/superminhduc/Web-ban-hang

Nhánh từng sinh viên (BẮT BUỘC):
- SV1: https://github.com/superminhduc/Web-ban-hang/tree/NguyenPhanVinhPhat
- SV2: https://github.com/superminhduc/Web-ban-hang/tree/TranPhuocNhan
- SV3: https://github.com/superminhduc/Web-ban-hang/tree/NguyenMinhDuc
Ghi chú:
=> Mỗi thành viên phải có log commit rõ ràng xuyên suốt 3 tuần.
=> Không có log = không đạt đồ án (theo yêu cầu học phần).

----------------------------------------------------------------------
VII. CẤU TRÚC THƯ MỤC BÀI NỘP
----------------------------------------------------------------------
/SourceCode
    (Toàn bộ mã nguồn website)
    
/Database
    database.sql (script tạo bảng & dữ liệu mẫu)

/Documents
    BaoCao_DoAn_WebApp.pdf
    PhanCongThanhVien.pdf (hoặc nằm trong báo cáo)

/Slides
    SlideThuyetTrinh.pdf hoặc .pptx

/Video (tùy chọn - khuyến khích)
/README.txt

----------------------------------------------------------------------
VIII. GHI CHÚ QUAN TRỌNG
----------------------------------------------------------------------
- Website phải chạy trên XAMPP và free host.
- Database phải import được không lỗi.
- Mã nguồn phải có comment, đặt tên rõ ràng.
- Báo cáo 10–15 trang kèm sơ đồ chức năng + ERD.
- Slide thuyết trình chuẩn bị đúng hạn.
- Đảm bảo mỗi thành viên hiểu phần mình làm.

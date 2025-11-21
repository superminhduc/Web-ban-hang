-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 21, 2025 at 07:16 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qlbandoannhanh3`
--

-- --------------------------------------------------------

--
-- Table structure for table `chitietdonhang`
--

CREATE TABLE `chitietdonhang` (
  `maCT` int(11) NOT NULL,
  `maDH` int(11) NOT NULL,
  `maSP` int(11) NOT NULL,
  `soLuong` int(11) DEFAULT 1,
  `donGia` decimal(18,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chitietdonhang`
--

INSERT INTO `chitietdonhang` (`maCT`, `maDH`, `maSP`, `soLuong`, `donGia`) VALUES
(7, 2, 38, 1, 280000.00),
(8, 2, 38, 1, 280000.00),
(9, 3, 40, 1, 140000.00);

-- --------------------------------------------------------

--
-- Table structure for table `danhmuc`
--

CREATE TABLE `danhmuc` (
  `maDM` int(11) NOT NULL,
  `tenDM` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `moTa` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `danhmuc`
--

INSERT INTO `danhmuc` (`maDM`, `tenDM`, `moTa`) VALUES
(1, 'Đồ ăn nhanh', 'Hamburger, Pizza, Gà rán'),
(2, 'Thức uống', 'Cà phê, Sinh tố, Nước ép'),
(6, 'Đồ ăn thêm', 'phô mai');

-- --------------------------------------------------------

--
-- Table structure for table `donhang`
--

CREATE TABLE `donhang` (
  `maDH` int(11) NOT NULL,
  `maNguoiDung` int(11) NOT NULL,
  `maSP` int(11) DEFAULT NULL,
  `ngayDat` datetime DEFAULT current_timestamp(),
  `tongTien` decimal(18,2) DEFAULT 0.00,
  `trangThai` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'Đang xử lý',
  `maKM` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `donhang`
--

INSERT INTO `donhang` (`maDH`, `maNguoiDung`, `maSP`, `ngayDat`, `tongTien`, `trangThai`, `maKM`) VALUES
(2, 1, 39, '2025-11-21 00:00:00', 15000.00, 'Đang xử lý', NULL),
(3, 1, 40, '2025-11-21 00:00:00', 140000.00, 'đang xử lý', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `khuyenmai`
--

CREATE TABLE `khuyenmai` (
  `maKM` int(11) NOT NULL,
  `tenKM` varchar(255) NOT NULL,
  `ngayBatDau` date NOT NULL,
  `ngayKetThuc` date NOT NULL
) ;

--
-- Dumping data for table `khuyenmai`
--

INSERT INTO `khuyenmai` (`maKM`, `tenKM`, `ngayBatDau`, `ngayKetThuc`) VALUES
(2, 'giam10%sinhvien', '2025-11-13', '2025-11-30');

-- --------------------------------------------------------

--
-- Table structure for table `nguoidung`
--

CREATE TABLE `nguoidung` (
  `maNguoiDung` int(11) NOT NULL,
  `username` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `hoTen` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `email` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `sdt` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `matkhau` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `trangThai` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'Hoạt động',
  `roleID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nguoidung`
--

INSERT INTO `nguoidung` (`maNguoiDung`, `username`, `hoTen`, `email`, `sdt`, `matkhau`, `trangThai`, `roleID`) VALUES
(1, 'admin', 'Quản trị viên', 'admin@example.com', '0901234567', '123456', 'Hoạt động', 1);

-- --------------------------------------------------------

--
-- Table structure for table `phanquyen`
--

CREATE TABLE `phanquyen` (
  `roleID` int(11) NOT NULL,
  `roleName` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `phanquyen`
--

INSERT INTO `phanquyen` (`roleID`, `roleName`) VALUES
(1, 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `sanpham`
--

CREATE TABLE `sanpham` (
  `maSP` int(11) NOT NULL,
  `tenSP` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `gia` decimal(18,2) NOT NULL,
  `moTa` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `hinhAnh` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `maDM` int(11) NOT NULL,
  `soLuong` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sanpham`
--

INSERT INTO `sanpham` (`maSP`, `tenSP`, `gia`, `moTa`, `hinhAnh`, `maDM`, `soLuong`) VALUES
(38, 'Pizza hải sản', 2800000.00, '', '', 1, 1),
(39, 'coca', 15000.00, '', '', 2, 1),
(40, 'Mỳ ý phô mai hải sản', 140000.00, '', '', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `thongkedoanhthu`
--

CREATE TABLE `thongkedoanhthu` (
  `id` int(11) NOT NULL,
  `ngay` date NOT NULL,
  `doanhThu` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'admin', '123456'),
(5, 'thaythanh', '123456');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD PRIMARY KEY (`maCT`),
  ADD KEY `maDH` (`maDH`),
  ADD KEY `maSP` (`maSP`);

--
-- Indexes for table `danhmuc`
--
ALTER TABLE `danhmuc`
  ADD PRIMARY KEY (`maDM`);

--
-- Indexes for table `donhang`
--
ALTER TABLE `donhang`
  ADD PRIMARY KEY (`maDH`),
  ADD KEY `maNguoiDung` (`maNguoiDung`),
  ADD KEY `fk_donhang_sanpham` (`maSP`),
  ADD KEY `fk_donhang_khuyenmai` (`maKM`);

--
-- Indexes for table `khuyenmai`
--
ALTER TABLE `khuyenmai`
  ADD PRIMARY KEY (`maKM`);

--
-- Indexes for table `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD PRIMARY KEY (`maNguoiDung`),
  ADD KEY `roleID` (`roleID`);

--
-- Indexes for table `phanquyen`
--
ALTER TABLE `phanquyen`
  ADD PRIMARY KEY (`roleID`);

--
-- Indexes for table `sanpham`
--
ALTER TABLE `sanpham`
  ADD PRIMARY KEY (`maSP`),
  ADD KEY `maDM` (`maDM`);

--
-- Indexes for table `thongkedoanhthu`
--
ALTER TABLE `thongkedoanhthu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  MODIFY `maCT` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `danhmuc`
--
ALTER TABLE `danhmuc`
  MODIFY `maDM` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `donhang`
--
ALTER TABLE `donhang`
  MODIFY `maDH` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `khuyenmai`
--
ALTER TABLE `khuyenmai`
  MODIFY `maKM` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nguoidung`
--
ALTER TABLE `nguoidung`
  MODIFY `maNguoiDung` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `phanquyen`
--
ALTER TABLE `phanquyen`
  MODIFY `roleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sanpham`
--
ALTER TABLE `sanpham`
  MODIFY `maSP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `thongkedoanhthu`
--
ALTER TABLE `thongkedoanhthu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD CONSTRAINT `chitietdonhang_ibfk_1` FOREIGN KEY (`maDH`) REFERENCES `donhang` (`maDH`),
  ADD CONSTRAINT `chitietdonhang_ibfk_2` FOREIGN KEY (`maSP`) REFERENCES `sanpham` (`maSP`);

--
-- Constraints for table `donhang`
--
ALTER TABLE `donhang`
  ADD CONSTRAINT `donhang_ibfk_1` FOREIGN KEY (`maNguoiDung`) REFERENCES `nguoidung` (`maNguoiDung`),
  ADD CONSTRAINT `fk_donhang_khuyenmai` FOREIGN KEY (`maKM`) REFERENCES `khuyenmai` (`maKM`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_donhang_sanpham` FOREIGN KEY (`maSP`) REFERENCES `sanpham` (`maSP`);

--
-- Constraints for table `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD CONSTRAINT `nguoidung_ibfk_1` FOREIGN KEY (`roleID`) REFERENCES `phanquyen` (`roleID`);

--
-- Constraints for table `sanpham`
--
ALTER TABLE `sanpham`
  ADD CONSTRAINT `sanpham_ibfk_1` FOREIGN KEY (`maDM`) REFERENCES `danhmuc` (`maDM`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

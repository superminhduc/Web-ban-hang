-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 17, 2025 at 02:42 PM
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
(2, 'Thức uống', 'Cà phê, Sinh tố, Nước ép');

-- --------------------------------------------------------

--
-- Table structure for table `donhang`
--

CREATE TABLE `donhang` (
  `maDH` int(11) NOT NULL,
  `maNguoiDung` int(11) NOT NULL,
  `ngayDat` datetime DEFAULT current_timestamp(),
  `tongTien` decimal(18,2) DEFAULT 0.00,
  `trangThai` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'Đang xử lý'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 'Admin'),
(2, 'Khách hàng');

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
(30, 'Pizza hải sản', 12000.00, '', '', 1, 23),
(31, 'Burger bò', 25000.00, '', '', 1, 1),
(32, 'Nui đút lò', 120000.00, '', '', 1, 1),
(33, 'Coca', 20000.00, '', '', 2, 1),
(34, 'Sting', 20000.00, '', '', 2, 1),
(35, 'Nước suối', 15000.00, '', '', 2, 1),
(36, 'Mỳ ý sốt kem', 85000.00, '', '', 1, 1);

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
(2, 'user1', 'password1');

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
  ADD KEY `maNguoiDung` (`maNguoiDung`);

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
  MODIFY `maCT` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `danhmuc`
--
ALTER TABLE `danhmuc`
  MODIFY `maDM` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `donhang`
--
ALTER TABLE `donhang`
  MODIFY `maDH` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `maSP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `thongkedoanhthu`
--
ALTER TABLE `thongkedoanhthu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  ADD CONSTRAINT `donhang_ibfk_1` FOREIGN KEY (`maNguoiDung`) REFERENCES `nguoidung` (`maNguoiDung`);

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

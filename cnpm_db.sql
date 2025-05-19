-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3306
-- Thời gian đã tạo: Th5 17, 2025 lúc 05:35 AM
-- Phiên bản máy phục vụ: 9.1.0
-- Phiên bản PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `cnpm_db`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bookings`
--

DROP TABLE IF EXISTS `bookings`;
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `room` varchar(100) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `checkin` date NOT NULL,
  `checkout` date NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `bookings`
--

INSERT INTO `bookings` (`id`, `room`, `fullname`, `phone`, `email`, `checkin`, `checkout`, `time`) VALUES
(1, 'Phòng Gia Đình', 'sds sd', '0385751026', 'gaterhunter4@gmail.com', '2025-05-18', '2025-05-19', '2025-05-17 04:01:27'),
(2, 'Phòng Gia Đình', 'sds sd', '0385751026', 'gaterhunter4@gmail.com', '2025-05-18', '2025-05-20', '2025-05-17 04:01:57'),
(3, 'Phòng Gia Đình', 'sds sd', '0385751026', 'gaterhunter4@gmail.com', '2025-12-05', '2025-12-06', '2025-05-17 04:36:03');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `phone`, `email`, `gender`, `dob`, `address`, `username`, `password`) VALUES
(1, 'sds sd', '0385751026', 'gaterhunter4@gmail.com', 'Nam', '1111-11-11', 'yi 180', 'gaterhunter4', '$2y$10$ORZNHCwr0HZuv.fPEN4jG.W8z24m0fjFxN27uqjLmBHvbza3x517q'),
(2, 'sds sd', '0385751026', 'gaterhunter45@gmail.com', 'Nam', '1111-11-11', 'yi 180', 'gaterhunter5', '$2y$10$8clz2AiZMyDcRMIK2LecI.K3RkWvzcWYqL/zAuDM5K.Pu2X8Qj4HO');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng QuanLyKhachhang
--

DROP TABLE IF EXISTS `QuanLyKhachhang`;
CREATE TABLE IF NOT EXISTS `QuanLyKhachhang` (
  `id` int NOT NULL AUTO_INCREMENT,
  `maKH` varchar(10) NOT NULL,
  `ten` varchar(100) NOT NULL,
  `gioiTinh` varchar(10) DEFAULT NULL,
  `sdt` varchar(20) DEFAULT NULL,
  `ngaySinh` date DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `diaChi` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `maKH` (`maKH`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Đang đổ dữ liệu cho bảng QuanLyKhachhang
INSERT INTO `QuanLyKhachhang` (`maKH`, `ten`, `gioiTinh`, `sdt`, `ngaySinh`, `email`, `diaChi`) VALUES
('A001', 'Hoàng Kim Tình', 'Nam', '0234383234', '2004-01-01', 'hoangkimtinh@gmail.com', '80 Cao Lỗ, Phường 4, Quận 8, TP. Hồ Chí Minh'),
('A002', 'Phạm Ngọc Sơn', 'Nam', '0792782033', '2004-08-09', 'ngocson@gmail.com', '32 Nguyễn Xuân Phùng, Quận 9, TP. Hồ Chí Minh'),
('A003', 'Nguyễn Trung Hiếu', 'Nam', '0234383234', '2004-09-15', 'hieunguyen@gmail.com', '551/30D/1 Phạm Văn Chi, Phường 7, Quận 6, TPHCM'),
('A004', 'Nguyễn Thanh Tài', 'Nam', '0792782033', '2004-01-05', 'thanhtai@gmail.com', '51 Tỉnh Lộ 10, Quận Bình Tân, TP. Hồ Chí Minh'),
('A005', 'Lê Thanh Đạt', 'Nam', '0234383234', '2004-12-06', 'thanhdat@gmail.com', '108 đường Cao Lỗ, P11,Q8'),
('A006', 'Trần Mỹ Trân', 'Nữ', '0792782033', '2004-02-13', 'trannguyen@gmail.com', '76 Nguyễn Văn Linh, Quận 7, TP. Hồ Chí Minh'),
('A007', 'Hồ Gia Như', 'Nữ', '0792782033', '2004-01-17', 'hogianhu@gmail.com', '48 Nguyễn Thị Thập, Quận 9, TP. Hồ Chí Minh');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng QuanLyPhong
--

DROP TABLE IF EXISTS `QuanLyPhong`;
CREATE TABLE IF NOT EXISTS `QuanLyPhong` (
  `id` int NOT NULL AUTO_INCREMENT,
  `maPhong` varchar(10) NOT NULL,
  `tenPhong` varchar(100) NOT NULL,
  `kieuPhong` varchar(10) NOT NULL,
  `giaPhong` int NOT NULL,
  `tinhTrang` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `maPhong` (`maPhong`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Đang đổ dữ liệu cho bảng QuanLyPhong
INSERT INTO `QuanLyPhong` (`maPhong`, `tenPhong`, `kieuPhong`, `giaPhong`, `tinhTrang`) VALUES
('R001', 'Phòng Trang Trọng', '1', 400000, '1'),
('R002', 'Phòng Tiêu Chuẩn', '1', 200000, '1'),
('R003', 'Phòng Cao Cấp', '2', 1200000, '0'),
('R004', 'Phòng VIP 1', '3', 2500000, '1'),
('R005', 'Phòng Đôi', '1', 350000, '2'),
('R006', 'Phòng Gia Đình', '2', 1500000, '1'),
('R007', 'Phòng Đơn', '1', 180000, '1');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

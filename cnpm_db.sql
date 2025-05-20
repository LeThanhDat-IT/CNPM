SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


DROP TABLE IF EXISTS `bookings`;
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `room` varchar(100) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `checkin` date NOT NULL,
  `total` int DEFAULT 0,
  `checkout` date NOT NULL,
  `time` datetime NOT NULL,
  `bookingCode` varchar(20) UNIQUE,
  `TrangThaiThanhToan` tinyint(1) DEFAULT 0,
  `TrangThaiTraPhong` tinyint DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `reset_code` varchar(255) DEFAULT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- --------------------------------------------------------
--
-- Cấu trúc bảng cho bảng QuanLyKhachhang
--

DROP TABLE IF EXISTS `QuanLyKhachhang`;
CREATE TABLE IF NOT EXISTS `QuanLyKhachhang` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ten` varchar(100) NOT NULL,
  `gioiTinh` varchar(10) DEFAULT NULL,
  `sdt` varchar(20) DEFAULT NULL,
  `ngaySinh` date DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `diaChi` varchar(255) DEFAULT NULL,
  `maKH` varchar(20) UNIQUE,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Đang đổ dữ liệu cho bảng QuanLyKhachhang
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
  `hinhAnh` varchar(255) DEFAULT NULL,
  `loaiGiuong` varchar(100) DEFAULT NULL,
  `tienNghi` text DEFAULT NULL,
  `dienTich` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `maPhong` (`maPhong`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Đang đổ dữ liệu cho bảng QuanLyPhong
-- --------------------------------------------------------
--
-- Cấu trúc bảng cho bảng password_resets
--

COMMIT;


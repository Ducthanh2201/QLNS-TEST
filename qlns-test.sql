-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 06, 2025 lúc 12:46 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `qlns-test`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admin`
--

CREATE TABLE `admin` (
  `email` varchar(100) NOT NULL,
  `Password` varchar(50) NOT NULL,
  `HinhAnh` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `admin`
--

INSERT INTO `admin` (`email`, `Password`, `HinhAnh`) VALUES
('admin123@gmail.com', 'admin123', '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bangcong`
--

CREATE TABLE `bangcong` (
  `MABC` int(11) NOT NULL,
  `Nam` int(11) NOT NULL,
  `Thang` int(11) NOT NULL,
  `Ngay` int(11) NOT NULL,
  `Giovao` int(11) NOT NULL,
  `Phutvao` int(11) NOT NULL,
  `GioRa` int(11) NOT NULL,
  `PhutRa` int(11) NOT NULL,
  `MaNV` int(11) NOT NULL,
  `IDLC` int(11) NOT NULL,
  `TrangThai` int(11) NOT NULL,
  `TrangThaiChamCong` int(11) NOT NULL,
  `GhiChu` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `bangcong`
--

INSERT INTO `bangcong` (`MABC`, `Nam`, `Thang`, `Ngay`, `Giovao`, `Phutvao`, `GioRa`, `PhutRa`, `MaNV`, `IDLC`, `TrangThai`, `TrangThaiChamCong`, `GhiChu`) VALUES
(1, 2025, 4, 5, 8, 0, 18, 0, 11084, 4, 1, 0, ''),
(2, 2025, 4, 5, 7, 12, 19, 12, 11086, 4, 1, 0, ''),
(3, 2025, 4, 5, 8, 0, 18, 0, 11085, 2, 1, 0, ''),
(8, 2025, 4, 6, 5, 22, 17, 22, 11084, 1, 4, 0, ''),
(9, 2025, 4, 6, 9, 0, 17, 0, 4, 1, 1, 0, '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chucvu`
--

CREATE TABLE `chucvu` (
  `IDCV` int(11) NOT NULL,
  `TenCV` varchar(50) NOT NULL,
  `TrangThai` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chucvu`
--

INSERT INTO `chucvu` (`IDCV`, `TenCV`, `TrangThai`) VALUES
(1, 'Nhân viên', 1),
(2, 'Giám Đốc', 1),
(3, 'Trưởng phòng', 1),
(4, 'Thư ký', 1),
(5, 'Test2', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `kt/kl`
--

CREATE TABLE `kt/kl` (
  `ID` int(11) NOT NULL,
  `TieuDe` varchar(50) NOT NULL,
  `SoKTKL` int(11) NOT NULL,
  `NoiDung` varchar(255) NOT NULL,
  `Ngay` datetime NOT NULL,
  `MaNV` int(11) NOT NULL,
  `LoaiKT/KL` int(11) NOT NULL,
  `SoTien` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `kt/kl`
--

INSERT INTO `kt/kl` (`ID`, `TieuDe`, `SoKTKL`, `NoiDung`, `Ngay`, `MaNV`, `LoaiKT/KL`, `SoTien`) VALUES
(1, 'Dự án test', 1, 'Hoàn thành tốt dự án', '2025-06-04 00:00:00', 11084, 1, 500000),
(3, 'Đi muộn', 172926, 'ĐI muộn 15p', '2025-06-04 00:00:00', 11084, 0, 2000000);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `loaica`
--

CREATE TABLE `loaica` (
  `IDLoaiCa` int(11) NOT NULL,
  `TenLoaiCa` varchar(100) NOT NULL,
  `HeSo` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `loaica`
--

INSERT INTO `loaica` (`IDLoaiCa`, `TenLoaiCa`, `HeSo`) VALUES
(1, 'Ca đêm', 1.5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `loaicong`
--

CREATE TABLE `loaicong` (
  `IDLC` int(11) NOT NULL,
  `TenLC` varchar(50) NOT NULL,
  `HeSo` float NOT NULL,
  `TrangThai` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `loaicong`
--

INSERT INTO `loaicong` (`IDLC`, `TenLC`, `HeSo`, `TrangThai`) VALUES
(1, 'Công thường', 1, 1),
(2, 'Làm thêm ngày thường', 1.5, 1),
(3, 'Làm thêm chủ nhật', 2, 1),
(4, 'Làm thêm ngày lễ', 3, 1),
(5, 'Làm đêm', 0.3, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `luong`
--

CREATE TABLE `luong` (
  `ID` int(11) NOT NULL,
  `MaNV` int(11) NOT NULL,
  `Thang` int(11) NOT NULL,
  `Nam` int(11) NOT NULL,
  `LuongCoBan` float NOT NULL DEFAULT 0,
  `TongNgayCong` float NOT NULL DEFAULT 0,
  `TongTien` float NOT NULL DEFAULT 0,
  `TrangThai` tinyint(4) NOT NULL DEFAULT 0,
  `NgayTinh` datetime DEFAULT NULL,
  `GhiChu` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `luong`
--

INSERT INTO `luong` (`ID`, `MaNV`, `Thang`, `Nam`, `LuongCoBan`, `TongNgayCong`, `TongTien`, `TrangThai`, `NgayTinh`, `GhiChu`) VALUES
(1, 11084, 3, 2025, 15000000, 500, 15000000, 1, '2025-03-30 23:39:05', ''),
(2, 1, 4, 2025, 10000000, 15, 5818180, 1, '2025-04-06 00:07:21', 'Lương tháng 4/2025'),
(3, 1, 4, 2025, 10000000, 20, 8090910, 0, '2025-04-06 00:08:03', 'Lương tháng 4/2025');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhanvien`
--

CREATE TABLE `nhanvien` (
  `MaNV` int(11) NOT NULL,
  `TenNV` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `GioiTinh` tinyint(1) NOT NULL,
  `NgaySinh` date NOT NULL,
  `DienThoai` varchar(10) NOT NULL,
  `CCCD` varchar(22) NOT NULL,
  `DiaChi` varchar(100) NOT NULL,
  `HinhAnh` varchar(255) NOT NULL,
  `IDCV` int(11) NOT NULL,
  `TrangThai` int(11) NOT NULL,
  `IDPB` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `nhanvien`
--

INSERT INTO `nhanvien` (`MaNV`, `TenNV`, `email`, `Password`, `GioiTinh`, `NgaySinh`, `DienThoai`, `CCCD`, `DiaChi`, `HinhAnh`, `IDCV`, `TrangThai`, `IDPB`) VALUES
(1, 'Nguyễn Văn A', 'nvaaaa@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, '1990-05-15', '0987654321', '012345678', 'Hà Nội', '1743933705_53841_laptop_asus_gaming_rog_strix_g531gt_hn554t_9.png', 1, 1, 1),
(4, 'Nguyễn Văn C', 'nguyenvana@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, '1990-01-15', '0987654321', '04824524524', 'Hà Nội', '', 1, 1, 1),
(5, 'Trần Thị B', 'tranthib@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0, '1992-05-20', '0912345678', '0424224245', 'Hồ Chí Minh', '', 2, 1, 2),
(6, 'Lê Văn D', 'levanc@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, '1988-11-10', '0965432187', '0242454245', 'Đà Nẵng', '', 3, 1, 3),
(11084, 'Nguyễn Đức Thànhh', 'nguyennguyenthanh2201@gmail.com', 'nv123', 1, '1999-01-01', '0935176314', '049205016141', 'Điện Bàn, Quảng Nam', '11084_1743934963.jpg', 1, 1, 1),
(11085, 'Nguyễn Văn A', 'nva@gmail.com', '$2y$12$Eg4PN5SCAABZTVbb7gR6u.Zv4mWXuONRcCW9RvR.DFqMcREl.s8TO', 1, '1996-05-07', '0935165123', '049132913123', 'Đà Nẵng', '1743787500_xuong-rong-bong-gon-dep-420x420.jpg', 1, 1, 1),
(11086, 'Lê Thị B', 'ltb@gmail.com', '$2y$12$crcu7iOpK4bOGqnlDVhn5exCTuF0ALaJvFK2qVQrYhtiwn9xW1fJO', 0, '1994-02-05', '0986123413', '049987612312', 'Quảng Bình', '1743819145_tuoi-ngo-nam-2021-co-buoc-dot-kich-ve-dau-tu-cach-treo-tranh-ngua-phong-thuy-1024x614-1-400x240.jpg', 4, 1, 2),
(11088, 'Nguyễn Testtttt', 'test@gmail.com', 'têtst123', 1, '2009-02-06', '0935175515', '049205016143', 'Đà Nẵng', '1743935078_53841_laptop_asus_gaming_rog_strix_g531gt_hn554t_9.png', 1, 1, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nv_pc`
--

CREATE TABLE `nv_pc` (
  `ID` int(11) NOT NULL,
  `MaNV` int(11) NOT NULL,
  `IDPC` int(11) NOT NULL,
  `Ngay` datetime NOT NULL,
  `NoiDung` varchar(255) NOT NULL,
  `SoTien` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `nv_pc`
--

INSERT INTO `nv_pc` (`ID`, `MaNV`, `IDPC`, `Ngay`, `NoiDung`, `SoTien`) VALUES
(1, 11084, 1, '2025-04-05 18:51:54', '', 500000),
(2, 1, 1, '2025-04-01 00:00:00', '', 500000),
(3, 1, 1, '2025-04-01 00:00:00', '', 500000),
(4, 1, 1, '2025-04-01 00:00:00', '', 500000),
(5, 1, 1, '2025-04-01 00:00:00', '', 500000);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phongban`
--

CREATE TABLE `phongban` (
  `IDPB` int(11) NOT NULL,
  `TenPB` varchar(50) NOT NULL,
  `MoTa` text NOT NULL,
  `TrangThai` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `phongban`
--

INSERT INTO `phongban` (`IDPB`, `TenPB`, `MoTa`, `TrangThai`) VALUES
(1, 'Phòng Kỹ thuật', '', 1),
(2, 'Kỹ Thuật', 'Phòng Kỹ thuật', 1),
(3, 'Nhân sự', 'Phòng nhân sự', 1),
(4, 'Marketing', 'Phòng Marketing', 1),
(5, 'Phòng IT', '', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phucap`
--

CREATE TABLE `phucap` (
  `IDPC` int(11) NOT NULL,
  `TenPC` varchar(100) NOT NULL,
  `SoTien` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `phucap`
--

INSERT INTO `phucap` (`IDPC`, `TenPC`, `SoTien`) VALUES
(1, 'Phụ cấp xăng xe', 500000);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tangca`
--

CREATE TABLE `tangca` (
  `ID` int(11) NOT NULL,
  `Nam` int(11) NOT NULL,
  `Thang` int(11) NOT NULL,
  `Ngay` int(11) NOT NULL,
  `SoGio` float NOT NULL,
  `MaNV` int(11) NOT NULL,
  `IDLoaiCa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tangca`
--

INSERT INTO `tangca` (`ID`, `Nam`, `Thang`, `Ngay`, `SoGio`, `MaNV`, `IDLoaiCa`) VALUES
(2, 2025, 3, 12, 4, 11084, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ungluong`
--

CREATE TABLE `ungluong` (
  `ID` int(11) NOT NULL,
  `Nam` int(11) NOT NULL,
  `Thang` int(11) NOT NULL,
  `Ngay` int(11) NOT NULL,
  `SoTien` float NOT NULL,
  `MaNV` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ungluong`
--

INSERT INTO `ungluong` (`ID`, `Nam`, `Thang`, `Ngay`, `SoTien`, `MaNV`) VALUES
(1, 2025, 3, 12, 3000000, 11084),
(2, 2025, 4, 10, 1000000, 1),
(3, 2025, 4, 10, 1000000, 1),
(4, 2025, 4, 10, 1000000, 1);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`email`);

--
-- Chỉ mục cho bảng `bangcong`
--
ALTER TABLE `bangcong`
  ADD PRIMARY KEY (`MABC`),
  ADD KEY `fk_LC` (`IDLC`),
  ADD KEY `fk_NV` (`MaNV`);

--
-- Chỉ mục cho bảng `chucvu`
--
ALTER TABLE `chucvu`
  ADD PRIMARY KEY (`IDCV`);

--
-- Chỉ mục cho bảng `kt/kl`
--
ALTER TABLE `kt/kl`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_NV5` (`MaNV`);

--
-- Chỉ mục cho bảng `loaica`
--
ALTER TABLE `loaica`
  ADD PRIMARY KEY (`IDLoaiCa`);

--
-- Chỉ mục cho bảng `loaicong`
--
ALTER TABLE `loaicong`
  ADD PRIMARY KEY (`IDLC`);

--
-- Chỉ mục cho bảng `luong`
--
ALTER TABLE `luong`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `idx_luong_manv` (`MaNV`),
  ADD KEY `idx_luong_thang_nam` (`Thang`,`Nam`);

--
-- Chỉ mục cho bảng `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD PRIMARY KEY (`MaNV`),
  ADD KEY `fk_CV` (`IDCV`),
  ADD KEY `fk_PB` (`IDPB`);

--
-- Chỉ mục cho bảng `nv_pc`
--
ALTER TABLE `nv_pc`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_PC` (`IDPC`),
  ADD KEY `fk_NV2` (`MaNV`);

--
-- Chỉ mục cho bảng `phongban`
--
ALTER TABLE `phongban`
  ADD PRIMARY KEY (`IDPB`);

--
-- Chỉ mục cho bảng `phucap`
--
ALTER TABLE `phucap`
  ADD PRIMARY KEY (`IDPC`);

--
-- Chỉ mục cho bảng `tangca`
--
ALTER TABLE `tangca`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_LC2` (`IDLoaiCa`),
  ADD KEY `fk_NV3` (`MaNV`);

--
-- Chỉ mục cho bảng `ungluong`
--
ALTER TABLE `ungluong`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_NV4` (`MaNV`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `bangcong`
--
ALTER TABLE `bangcong`
  MODIFY `MABC` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `chucvu`
--
ALTER TABLE `chucvu`
  MODIFY `IDCV` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `kt/kl`
--
ALTER TABLE `kt/kl`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `loaica`
--
ALTER TABLE `loaica`
  MODIFY `IDLoaiCa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `loaicong`
--
ALTER TABLE `loaicong`
  MODIFY `IDLC` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `luong`
--
ALTER TABLE `luong`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `nhanvien`
--
ALTER TABLE `nhanvien`
  MODIFY `MaNV` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11089;

--
-- AUTO_INCREMENT cho bảng `nv_pc`
--
ALTER TABLE `nv_pc`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `phongban`
--
ALTER TABLE `phongban`
  MODIFY `IDPB` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `phucap`
--
ALTER TABLE `phucap`
  MODIFY `IDPC` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `tangca`
--
ALTER TABLE `tangca`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `ungluong`
--
ALTER TABLE `ungluong`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `bangcong`
--
ALTER TABLE `bangcong`
  ADD CONSTRAINT `fk_LC` FOREIGN KEY (`IDLC`) REFERENCES `loaicong` (`IDLC`),
  ADD CONSTRAINT `fk_NV` FOREIGN KEY (`MaNV`) REFERENCES `nhanvien` (`MaNV`);

--
-- Các ràng buộc cho bảng `kt/kl`
--
ALTER TABLE `kt/kl`
  ADD CONSTRAINT `fk_NV5` FOREIGN KEY (`MaNV`) REFERENCES `nhanvien` (`MaNV`);

--
-- Các ràng buộc cho bảng `luong`
--
ALTER TABLE `luong`
  ADD CONSTRAINT `fk_luong_nhanvien` FOREIGN KEY (`MaNV`) REFERENCES `nhanvien` (`MaNV`);

--
-- Các ràng buộc cho bảng `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD CONSTRAINT `fk_CV` FOREIGN KEY (`IDCV`) REFERENCES `chucvu` (`IDCV`),
  ADD CONSTRAINT `fk_PB` FOREIGN KEY (`IDPB`) REFERENCES `phongban` (`IDPB`);

--
-- Các ràng buộc cho bảng `nv_pc`
--
ALTER TABLE `nv_pc`
  ADD CONSTRAINT `fk_NV2` FOREIGN KEY (`MaNV`) REFERENCES `nhanvien` (`MaNV`),
  ADD CONSTRAINT `fk_PC` FOREIGN KEY (`IDPC`) REFERENCES `phucap` (`IDPC`);

--
-- Các ràng buộc cho bảng `tangca`
--
ALTER TABLE `tangca`
  ADD CONSTRAINT `fk_LC2` FOREIGN KEY (`IDLoaiCa`) REFERENCES `loaica` (`IDLoaiCa`),
  ADD CONSTRAINT `fk_NV3` FOREIGN KEY (`MaNV`) REFERENCES `nhanvien` (`MaNV`);

--
-- Các ràng buộc cho bảng `ungluong`
--
ALTER TABLE `ungluong`
  ADD CONSTRAINT `fk_NV4` FOREIGN KEY (`MaNV`) REFERENCES `nhanvien` (`MaNV`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

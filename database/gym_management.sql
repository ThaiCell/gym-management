-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th9 29, 2026 lúc 08:46 AM
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
-- Cơ sở dữ liệu: `gym_management`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `check_in`
--

CREATE TABLE `check_in` (
  `check_in_id` int(11) NOT NULL,
  `hoi_vien_id` int(11) NOT NULL,
  `dang_ky_goi_tap_id` int(11) NOT NULL,
  `thoi_gian` datetime NOT NULL,
  `nguoi_thuc_hien_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `check_in`
--

INSERT INTO `check_in` (`check_in_id`, `hoi_vien_id`, `dang_ky_goi_tap_id`, `thoi_gian`, `nguoi_thuc_hien_id`) VALUES
(1, 1, 1, '2026-09-25 07:30:00', 2),
(2, 2, 2, '2026-09-25 08:00:00', 2),
(3, 3, 3, '2026-09-25 09:00:00', 2),
(4, 4, 4, '2026-09-25 10:00:00', 3),
(5, 5, 5, '2026-09-25 16:00:00', 3),
(6, 6, 6, '2026-09-26 07:45:00', 2),
(7, 7, 7, '2026-09-26 08:30:00', 2),
(8, 8, 8, '2026-09-26 09:15:00', 3),
(9, 9, 9, '2026-09-27 17:00:00', 3),
(10, 10, 10, '2026-09-27 18:00:00', 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chi_tiet_hoa_don`
--

CREATE TABLE `chi_tiet_hoa_don` (
  `chi_tiet_id` int(11) NOT NULL,
  `hoa_don_id` int(11) NOT NULL,
  `goi_tap_id` int(11) DEFAULT NULL,
  `goi_pt_id` int(11) DEFAULT NULL,
  `so_luong` int(11) NOT NULL,
  `don_gia` decimal(12,2) NOT NULL,
  `thanh_tien` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chi_tiet_hoa_don`
--

INSERT INTO `chi_tiet_hoa_don` (`chi_tiet_id`, `hoa_don_id`, `goi_tap_id`, `goi_pt_id`, `so_luong`, `don_gia`, `thanh_tien`) VALUES
(1, 1, 1, NULL, 1, 300000.00, 300000.00),
(2, 2, 2, NULL, 1, 800000.00, 800000.00),
(3, 3, 3, NULL, 1, 1400000.00, 1400000.00),
(4, 4, 4, NULL, 1, 2500000.00, 2500000.00),
(5, 5, 1, NULL, 1, 300000.00, 300000.00),
(6, 6, 5, NULL, 1, 200000.00, 200000.00),
(7, 7, 2, NULL, 1, 800000.00, 800000.00),
(8, 8, 3, NULL, 1, 1400000.00, 1400000.00),
(9, 9, 1, NULL, 1, 300000.00, 300000.00),
(10, 10, 6, NULL, 1, 100000.00, 100000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dang_ky_goi_pt`
--

CREATE TABLE `dang_ky_goi_pt` (
  `dang_ky_goi_pt_id` int(11) NOT NULL,
  `hoi_vien_id` int(11) NOT NULL,
  `goi_pt_id` int(11) NOT NULL,
  `pt_id` int(11) NOT NULL,
  `ngay_bat_dau` date NOT NULL,
  `ngay_ket_thuc` date NOT NULL,
  `so_buoi_con_lai` int(11) NOT NULL,
  `trang_thai` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `dang_ky_goi_pt`
--

INSERT INTO `dang_ky_goi_pt` (`dang_ky_goi_pt_id`, `hoi_vien_id`, `goi_pt_id`, `pt_id`, `ngay_bat_dau`, `ngay_ket_thuc`, `so_buoi_con_lai`, `trang_thai`) VALUES
(1, 1, 1, 1, '2026-09-01', '2026-09-30', 7, 'đang hoạt động'),
(2, 2, 2, 2, '2026-08-01', '2026-09-30', 12, 'đang hoạt động'),
(3, 3, 3, 3, '2026-07-01', '2026-09-30', 20, 'đang hoạt động'),
(4, 4, 4, 4, '2026-09-01', '2026-09-15', 0, 'đã kết thúc'),
(5, 5, 1, 1, '2026-09-10', '2026-10-09', 8, 'đang hoạt động');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dang_ky_goi_tap`
--

CREATE TABLE `dang_ky_goi_tap` (
  `dang_ky_goi_tap_id` int(11) NOT NULL,
  `hoi_vien_id` int(11) NOT NULL,
  `goi_tap_id` int(11) NOT NULL,
  `ngay_bat_dau` date NOT NULL,
  `ngay_ket_thuc` date NOT NULL,
  `so_buoi_con_lai` int(11) NOT NULL DEFAULT 0,
  `trang_thai` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `dang_ky_goi_tap`
--

INSERT INTO `dang_ky_goi_tap` (`dang_ky_goi_tap_id`, `hoi_vien_id`, `goi_tap_id`, `ngay_bat_dau`, `ngay_ket_thuc`, `so_buoi_con_lai`, `trang_thai`) VALUES
(1, 1, 1, '2026-09-01', '2026-09-30', 22, 'đang hoạt động'),
(2, 2, 2, '2026-08-01', '2026-10-29', 45, 'đang hoạt động'),
(3, 3, 3, '2026-07-01', '2026-12-27', 70, 'đang hoạt động'),
(4, 4, 4, '2026-01-01', '2026-12-31', 95, 'đang hoạt động'),
(5, 5, 1, '2026-09-10', '2026-10-09', 18, 'đang hoạt động'),
(6, 6, 5, '2026-09-15', '2026-10-14', 25, 'đang hoạt động'),
(7, 7, 2, '2026-08-15', '2026-11-12', 50, 'đang hoạt động'),
(8, 8, 3, '2026-06-01', '2026-11-27', 40, 'đang hoạt động'),
(9, 9, 1, '2026-09-20', '2026-10-19', 25, 'đang hoạt động'),
(10, 10, 6, '2026-09-25', '2026-10-01', 5, 'đang hoạt động');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dang_ky_lop`
--

CREATE TABLE `dang_ky_lop` (
  `dang_ky_lop_id` int(11) NOT NULL,
  `lop_tap_id` int(11) NOT NULL,
  `hoi_vien_id` int(11) NOT NULL,
  `ngay_dang_ky` datetime NOT NULL,
  `trang_thai` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `dang_ky_lop`
--

INSERT INTO `dang_ky_lop` (`dang_ky_lop_id`, `lop_tap_id`, `hoi_vien_id`, `ngay_dang_ky`, `trang_thai`) VALUES
(1, 1, 1, '2026-09-01 08:00:00', 'đã đăng ký'),
(2, 2, 2, '2026-09-02 09:00:00', 'đã đăng ký'),
(3, 3, 3, '2026-09-03 10:00:00', 'đã đăng ký'),
(4, 1, 4, '2026-09-04 11:00:00', 'đã đăng ký'),
(5, 4, 5, '2026-09-05 12:00:00', 'đã đăng ký'),
(6, 2, 6, '2026-09-06 13:00:00', 'đã đăng ký'),
(7, 3, 7, '2026-09-07 14:00:00', 'đã đăng ký'),
(8, 1, 8, '2026-09-08 15:00:00', 'đã đăng ký'),
(9, 4, 9, '2026-09-09 16:00:00', 'đã đăng ký'),
(10, 2, 10, '2026-09-10 17:00:00', 'đã đăng ký');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `goi_pt`
--

CREATE TABLE `goi_pt` (
  `goi_pt_id` int(11) NOT NULL,
  `ten_goi_pt` varchar(100) NOT NULL,
  `gia` decimal(12,2) NOT NULL,
  `so_buoi` int(11) NOT NULL,
  `thoi_han_ngay` int(11) NOT NULL,
  `trang_thai` varchar(30) NOT NULL DEFAULT 'hoat_dong'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `goi_pt`
--

INSERT INTO `goi_pt` (`goi_pt_id`, `ten_goi_pt`, `gia`, `so_buoi`, `thoi_han_ngay`, `trang_thai`) VALUES
(1, 'PT Cơ Bản 10 Buổi', 1500000.00, 10, 30, 'hoạt_động'),
(2, 'PT Tiêu Chuẩn 20 Buổi', 2800000.00, 20, 60, 'hoạt_động'),
(3, 'PT Premium 30 Buổi', 3900000.00, 30, 90, 'hoạt_động'),
(4, 'PT Cá Nhân 5 Buổi', 800000.00, 5, 15, 'hoạt_động');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `goi_tap`
--

CREATE TABLE `goi_tap` (
  `goi_tap_id` int(11) NOT NULL,
  `ten_goi` varchar(100) NOT NULL,
  `gia` decimal(12,2) NOT NULL,
  `thoi_han_ngay` int(11) NOT NULL,
  `so_buoi` int(11) DEFAULT NULL,
  `trang_thai` varchar(30) NOT NULL DEFAULT 'hoat_dong'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `goi_tap`
--

INSERT INTO `goi_tap` (`goi_tap_id`, `ten_goi`, `gia`, `thoi_han_ngay`, `so_buoi`, `trang_thai`) VALUES
(1, 'Gói Cơ Bản 1 Tháng', 300000.00, 30, 30, 'hoạt_động'),
(2, 'Gói Tiêu Chuẩn 3 Tháng', 800000.00, 90, 90, 'hoạt_động'),
(3, 'Gói Premium 6 Tháng', 1400000.00, 180, 180, 'hoạt_động'),
(4, 'Gói VIP 12 Tháng', 2500000.00, 365, 365, 'hoạt_động'),
(5, 'Gói Sinh Viên', 200000.00, 30, 30, 'hoạt_động'),
(6, 'Gói Tập Thử', 100000.00, 7, 7, 'hoạt_động');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hoa_don`
--

CREATE TABLE `hoa_don` (
  `hoa_don_id` int(11) NOT NULL,
  `hoi_vien_id` int(11) NOT NULL,
  `nhan_vien_id` int(11) NOT NULL,
  `ngay_lap` datetime NOT NULL,
  `tong_tien` decimal(12,2) NOT NULL,
  `trang_thai` varchar(30) NOT NULL,
  `phuong_thuc_thanh_toan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `hoa_don`
--

INSERT INTO `hoa_don` (`hoa_don_id`, `hoi_vien_id`, `nhan_vien_id`, `ngay_lap`, `tong_tien`, `trang_thai`, `phuong_thuc_thanh_toan`) VALUES
(1, 1, 2, '2026-09-01 08:30:00', 300000.00, 'đã thanh toán', 'Tiền mặt'),
(2, 2, 2, '2026-09-02 09:15:00', 800000.00, 'đã thanh toán', 'Chuyển khoản'),
(3, 3, 3, '2026-09-03 10:00:00', 1400000.00, 'đã thanh toán', 'Chuyển khoản'),
(4, 4, 3, '2026-09-04 11:30:00', 2500000.00, 'đã thanh toán', 'Thẻ'),
(5, 5, 2, '2026-09-10 08:45:00', 300000.00, 'đã thanh toán', 'Tiền mặt'),
(6, 6, 3, '2026-09-15 09:30:00', 200000.00, 'đã thanh toán', 'Chuyển khoản'),
(7, 7, 2, '2026-09-16 10:15:00', 800000.00, 'đã thanh toán', 'Chuyển khoản'),
(8, 8, 3, '2026-09-18 14:00:00', 1400000.00, 'đã thanh toán', 'Thẻ'),
(9, 9, 2, '2026-09-20 15:30:00', 300000.00, 'đã thanh toán', 'Tiền mặt'),
(10, 10, 3, '2026-09-25 16:00:00', 100000.00, 'đã thanh toán', 'Tiền mặt');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hoi_vien`
--

CREATE TABLE `hoi_vien` (
  `hoi_vien_id` int(11) NOT NULL,
  `nguoi_dung_id` int(11) NOT NULL,
  `ngay_sinh` date DEFAULT NULL,
  `so_dien_thoai` varchar(20) DEFAULT NULL,
  `dia_chi` varchar(255) DEFAULT NULL,
  `ngay_tham_gia` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `hoi_vien`
--

INSERT INTO `hoi_vien` (`hoi_vien_id`, `nguoi_dung_id`, `ngay_sinh`, `so_dien_thoai`, `dia_chi`, `ngay_tham_gia`) VALUES
(1, 5, '2002-05-10', '0901000001', 'Thành phố Hồ Chí Minh', '2026-01-05'),
(2, 6, '2001-08-15', '0901000002', 'Thành phố Hồ Chí Minh', '2026-01-10'),
(3, 7, '2003-02-20', '0901000003', 'Bình Dương', '2026-01-15'),
(4, 8, '2000-11-12', '0901000004', 'Thành phố Hồ Chí Minh', '2026-02-01'),
(5, 9, '2002-07-25', '0901000005', 'Đồng Nai', '2026-02-10'),
(6, 10, '2001-04-18', '0901000006', 'Thành phố Hồ Chí Minh', '2026-02-20'),
(7, 15, '1999-03-11', '0901000007', 'Thành phố Hồ Chí Minh', '2026-03-01'),
(8, 16, '2003-09-22', '0901000008', 'Long An', '2026-03-05'),
(9, 17, '2000-06-30', '0901000009', 'Thành phố Hồ Chí Minh', '2026-03-12'),
(10, 18, '2002-12-01', '0901000010', 'Thành phố Hồ Chí Minh', '2026-03-20'),
(11, 19, NULL, NULL, NULL, '2026-09-29');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `huan_luyen_vien`
--

CREATE TABLE `huan_luyen_vien` (
  `pt_id` int(11) NOT NULL,
  `nguoi_dung_id` int(11) NOT NULL,
  `chuyen_mon` varchar(255) DEFAULT NULL,
  `so_dien_thoai` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `huan_luyen_vien`
--

INSERT INTO `huan_luyen_vien` (`pt_id`, `nguoi_dung_id`, `chuyen_mon`, `so_dien_thoai`) VALUES
(1, 11, 'Tăng cơ và phát triển cơ bắp', '0902000001'),
(2, 12, 'Giảm cân và Cardio', '0902000002'),
(3, 13, 'Thể hình chuyên nghiệp', '0902000003'),
(4, 14, 'Yoga và Fitness', '0902000004');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lich_pt`
--

CREATE TABLE `lich_pt` (
  `lich_pt_id` int(11) NOT NULL,
  `dang_ky_goi_pt_id` int(11) NOT NULL,
  `hoi_vien_id` int(11) NOT NULL,
  `pt_id` int(11) NOT NULL,
  `thoi_gian_bat_dau` datetime NOT NULL,
  `thoi_gian_ket_thuc` datetime NOT NULL,
  `trang_thai` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `lich_pt`
--

INSERT INTO `lich_pt` (`lich_pt_id`, `dang_ky_goi_pt_id`, `hoi_vien_id`, `pt_id`, `thoi_gian_bat_dau`, `thoi_gian_ket_thuc`, `trang_thai`) VALUES
(1, 1, 1, 1, '2026-09-29 07:00:00', '2026-09-29 08:00:00', 'đã đặt lịch'),
(2, 2, 2, 2, '2026-09-29 09:00:00', '2026-09-29 10:00:00', 'đã đặt lịch'),
(3, 3, 3, 3, '2026-09-29 15:00:00', '2026-09-29 16:00:00', 'đã đặt lịch'),
(4, 5, 5, 1, '2026-09-30 08:00:00', '2026-09-30 09:00:00', 'đã đặt lịch'),
(5, 1, 1, 1, '2026-10-01 07:00:00', '2026-10-01 08:00:00', 'đã đặt lịch');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lop_tap`
--

CREATE TABLE `lop_tap` (
  `lop_tap_id` int(11) NOT NULL,
  `ten_lop` varchar(100) NOT NULL,
  `mo_ta` text DEFAULT NULL,
  `lich_tap` varchar(255) DEFAULT NULL,
  `suc_chua` int(11) DEFAULT NULL,
  `trang_thai` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `lop_tap`
--

INSERT INTO `lop_tap` (`lop_tap_id`, `ten_lop`, `mo_ta`, `lich_tap`, `suc_chua`, `trang_thai`) VALUES
(1, 'Yoga Cơ Bản', 'Lớp yoga dành cho người mới bắt đầu', 'Thứ 2 - 4 - 6, 08:00', 20, 'hoạt_động'),
(2, 'Cardio Đốt Mỡ', 'Lớp cardio giúp tăng sức bền và đốt cháy năng lượng', 'Thứ 3 - 5 - 7, 17:00', 25, 'hoạt_động'),
(3, 'Gym Tăng Cơ', 'Lớp hướng dẫn tập luyện tăng cơ', 'Thứ 2 - 4 - 6, 18:00', 20, 'hoạt_động'),
(4, 'Fitness Toàn Thân', 'Bài tập fitness toàn thân', 'Chủ Nhật, 08:00', 30, 'hoạt_động');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(8, '2026_09_28_153041_create_vai_tro_table', 2),
(9, '2026_09_28_153614_create_gym_tables', 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoi_dung`
--

CREATE TABLE `nguoi_dung` (
  `nguoi_dung_id` int(11) NOT NULL,
  `vai_tro_id` int(11) NOT NULL,
  `ho_ten` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mat_khau` varchar(255) NOT NULL,
  `trang_thai` varchar(30) NOT NULL DEFAULT 'hoat_dong'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nguoi_dung`
--

INSERT INTO `nguoi_dung` (`nguoi_dung_id`, `vai_tro_id`, `ho_ten`, `email`, `mat_khau`, `trang_thai`) VALUES
(1, 1, 'Nguyễn Văn Admin', 'admin@gym.com', '123456', 'hoạt_động'),
(2, 2, 'Trần Văn An', 'an@gym.com', '123456', 'hoạt_động'),
(3, 2, 'Lê Thị Hoa', 'hoa@gym.com', '123456', 'hoạt_động'),
(4, 2, 'Phạm Văn Nam', 'nam@gym.com', '123456', 'hoạt_động'),
(5, 3, 'Nguyễn Văn Minh', 'minh@gmail.com', '123456', 'hoạt_động'),
(6, 3, 'Trần Thị Lan', 'lan@gmail.com', '123456', 'hoạt_động'),
(7, 3, 'Lê Văn Hùng', 'hung@gmail.com', '123456', 'hoạt_động'),
(8, 3, 'Phạm Thị Mai', 'mai@gmail.com', '123456', 'hoạt_động'),
(9, 3, 'Võ Thanh Tùng', 'tung@gmail.com', '123456', 'hoạt_động'),
(10, 3, 'Đặng Thị Ngọc', 'ngoc@gmail.com', '123456', 'hoạt_động'),
(11, 4, 'Nguyễn Hoàng Long', 'long@gym.com', '123456', 'hoạt_động'),
(12, 4, 'Trần Minh Khoa', 'khoa@gym.com', '123456', 'hoạt_động'),
(13, 4, 'Lê Quốc Bảo', 'bao@gym.com', '123456', 'hoạt_động'),
(14, 4, 'Phạm Thanh Phong', 'phong@gym.com', '123456', 'hoạt_động'),
(15, 3, 'Nguyễn Hoàng Nam', 'nam1@gmail.com', '123456', 'hoạt_động'),
(16, 3, 'Trần Quốc Việt', 'viet@gmail.com', '123456', 'hoạt_động'),
(17, 3, 'Lê Minh Tuấn', 'tuan@gmail.com', '123456', 'hoạt_động'),
(18, 3, 'Phạm Ngọc Anh', 'anh@gmail.com', '123456', 'hoạt_động'),
(19, 3, 'Trương Trí Thiện', 'truongtrithien2008@gmail.com', '$2y$12$U0Fe0KbOCPxASjRKkiYfIulPrID1F/PVoOOPJRFvV03qjUi2SGAJ2', 'hoạt_động');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhan_vien`
--

CREATE TABLE `nhan_vien` (
  `nhan_vien_id` int(11) NOT NULL,
  `nguoi_dung_id` int(11) NOT NULL,
  `chuc_vu` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nhan_vien`
--

INSERT INTO `nhan_vien` (`nhan_vien_id`, `nguoi_dung_id`, `chuc_vu`) VALUES
(1, 1, 'Quản trị viên'),
(2, 2, 'Nhân viên lễ tân'),
(3, 3, 'Nhân viên thu ngân'),
(4, 4, 'Quản lý phòng gym');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('MsL66eGbqez3XFZkWlgkWE08sHUMeMv9h8d40APl', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMmR2Sk5BWVh5cXJBanJyZTg2ODVXQ1dMb3Njb3NIYXZhbjQ3VEJ5VCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7Tjt9fQ==', 1790664328);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thong_bao`
--

CREATE TABLE `thong_bao` (
  `thong_bao_id` int(11) NOT NULL,
  `nguoi_dung_id` int(11) NOT NULL,
  `tieu_de` varchar(150) NOT NULL,
  `noi_dung` text NOT NULL,
  `da_doc` tinyint(1) NOT NULL DEFAULT 0,
  `tao_luc` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `thong_bao`
--

INSERT INTO `thong_bao` (`thong_bao_id`, `nguoi_dung_id`, `tieu_de`, `noi_dung`, `da_doc`, `tao_luc`) VALUES
(1, 1, 'Chào mừng', 'Chào mừng bạn đến với hệ thống Gym Management.', 0, '2026-09-29 08:00:00'),
(2, 2, 'Thành viên mới', 'Có thành viên mới vừa đăng ký.', 0, '2026-09-29 08:30:00'),
(3, 3, 'Thanh toán', 'Có hóa đơn mới đã được thanh toán.', 1, '2026-09-29 09:00:00'),
(4, 4, 'Lịch PT', 'Có lịch PT mới được tạo.', 0, '2026-09-29 09:30:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vai_tro`
--

CREATE TABLE `vai_tro` (
  `vai_tro_id` int(11) NOT NULL,
  `ten_vai_tro` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `vai_tro`
--

INSERT INTO `vai_tro` (`vai_tro_id`, `ten_vai_tro`) VALUES
(3, 'Hội viên'),
(4, 'Huấn luyện viên'),
(2, 'Nhân viên'),
(1, 'Quản trị viên');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Chỉ mục cho bảng `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Chỉ mục cho bảng `check_in`
--
ALTER TABLE `check_in`
  ADD PRIMARY KEY (`check_in_id`),
  ADD KEY `check_in_hoi_vien_id_foreign` (`hoi_vien_id`),
  ADD KEY `check_in_dang_ky_goi_tap_id_foreign` (`dang_ky_goi_tap_id`),
  ADD KEY `check_in_nguoi_thuc_hien_id_foreign` (`nguoi_thuc_hien_id`);

--
-- Chỉ mục cho bảng `chi_tiet_hoa_don`
--
ALTER TABLE `chi_tiet_hoa_don`
  ADD PRIMARY KEY (`chi_tiet_id`),
  ADD KEY `chi_tiet_hoa_don_hoa_don_id_foreign` (`hoa_don_id`),
  ADD KEY `chi_tiet_hoa_don_goi_tap_id_foreign` (`goi_tap_id`),
  ADD KEY `chi_tiet_hoa_don_goi_pt_id_foreign` (`goi_pt_id`);

--
-- Chỉ mục cho bảng `dang_ky_goi_pt`
--
ALTER TABLE `dang_ky_goi_pt`
  ADD PRIMARY KEY (`dang_ky_goi_pt_id`),
  ADD KEY `dang_ky_goi_pt_hoi_vien_id_foreign` (`hoi_vien_id`),
  ADD KEY `dang_ky_goi_pt_goi_pt_id_foreign` (`goi_pt_id`),
  ADD KEY `dang_ky_goi_pt_pt_id_foreign` (`pt_id`);

--
-- Chỉ mục cho bảng `dang_ky_goi_tap`
--
ALTER TABLE `dang_ky_goi_tap`
  ADD PRIMARY KEY (`dang_ky_goi_tap_id`),
  ADD KEY `dang_ky_goi_tap_hoi_vien_id_foreign` (`hoi_vien_id`),
  ADD KEY `dang_ky_goi_tap_goi_tap_id_foreign` (`goi_tap_id`);

--
-- Chỉ mục cho bảng `dang_ky_lop`
--
ALTER TABLE `dang_ky_lop`
  ADD PRIMARY KEY (`dang_ky_lop_id`),
  ADD KEY `dang_ky_lop_lop_tap_id_foreign` (`lop_tap_id`),
  ADD KEY `dang_ky_lop_hoi_vien_id_foreign` (`hoi_vien_id`);

--
-- Chỉ mục cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Chỉ mục cho bảng `goi_pt`
--
ALTER TABLE `goi_pt`
  ADD PRIMARY KEY (`goi_pt_id`);

--
-- Chỉ mục cho bảng `goi_tap`
--
ALTER TABLE `goi_tap`
  ADD PRIMARY KEY (`goi_tap_id`);

--
-- Chỉ mục cho bảng `hoa_don`
--
ALTER TABLE `hoa_don`
  ADD PRIMARY KEY (`hoa_don_id`),
  ADD KEY `hoa_don_hoi_vien_id_foreign` (`hoi_vien_id`),
  ADD KEY `hoa_don_nhan_vien_id_foreign` (`nhan_vien_id`);

--
-- Chỉ mục cho bảng `hoi_vien`
--
ALTER TABLE `hoi_vien`
  ADD PRIMARY KEY (`hoi_vien_id`),
  ADD UNIQUE KEY `hoi_vien_nguoi_dung_id_unique` (`nguoi_dung_id`);

--
-- Chỉ mục cho bảng `huan_luyen_vien`
--
ALTER TABLE `huan_luyen_vien`
  ADD PRIMARY KEY (`pt_id`),
  ADD UNIQUE KEY `huan_luyen_vien_nguoi_dung_id_unique` (`nguoi_dung_id`);

--
-- Chỉ mục cho bảng `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Chỉ mục cho bảng `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `lich_pt`
--
ALTER TABLE `lich_pt`
  ADD PRIMARY KEY (`lich_pt_id`),
  ADD KEY `lich_pt_dang_ky_goi_pt_id_foreign` (`dang_ky_goi_pt_id`),
  ADD KEY `lich_pt_hoi_vien_id_foreign` (`hoi_vien_id`),
  ADD KEY `lich_pt_pt_id_foreign` (`pt_id`);

--
-- Chỉ mục cho bảng `lop_tap`
--
ALTER TABLE `lop_tap`
  ADD PRIMARY KEY (`lop_tap_id`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD PRIMARY KEY (`nguoi_dung_id`),
  ADD UNIQUE KEY `nguoi_dung_email_unique` (`email`),
  ADD KEY `nguoi_dung_vai_tro_id_foreign` (`vai_tro_id`);

--
-- Chỉ mục cho bảng `nhan_vien`
--
ALTER TABLE `nhan_vien`
  ADD PRIMARY KEY (`nhan_vien_id`),
  ADD UNIQUE KEY `nhan_vien_nguoi_dung_id_unique` (`nguoi_dung_id`);

--
-- Chỉ mục cho bảng `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Chỉ mục cho bảng `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Chỉ mục cho bảng `thong_bao`
--
ALTER TABLE `thong_bao`
  ADD PRIMARY KEY (`thong_bao_id`),
  ADD KEY `thong_bao_nguoi_dung_id_foreign` (`nguoi_dung_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Chỉ mục cho bảng `vai_tro`
--
ALTER TABLE `vai_tro`
  ADD PRIMARY KEY (`vai_tro_id`),
  ADD UNIQUE KEY `vai_tro_ten_vai_tro_unique` (`ten_vai_tro`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `check_in`
--
ALTER TABLE `check_in`
  MODIFY `check_in_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `chi_tiet_hoa_don`
--
ALTER TABLE `chi_tiet_hoa_don`
  MODIFY `chi_tiet_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `dang_ky_goi_pt`
--
ALTER TABLE `dang_ky_goi_pt`
  MODIFY `dang_ky_goi_pt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `dang_ky_goi_tap`
--
ALTER TABLE `dang_ky_goi_tap`
  MODIFY `dang_ky_goi_tap_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `dang_ky_lop`
--
ALTER TABLE `dang_ky_lop`
  MODIFY `dang_ky_lop_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `goi_pt`
--
ALTER TABLE `goi_pt`
  MODIFY `goi_pt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `goi_tap`
--
ALTER TABLE `goi_tap`
  MODIFY `goi_tap_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `hoa_don`
--
ALTER TABLE `hoa_don`
  MODIFY `hoa_don_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `hoi_vien`
--
ALTER TABLE `hoi_vien`
  MODIFY `hoi_vien_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `huan_luyen_vien`
--
ALTER TABLE `huan_luyen_vien`
  MODIFY `pt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `lich_pt`
--
ALTER TABLE `lich_pt`
  MODIFY `lich_pt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `lop_tap`
--
ALTER TABLE `lop_tap`
  MODIFY `lop_tap_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  MODIFY `nguoi_dung_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT cho bảng `nhan_vien`
--
ALTER TABLE `nhan_vien`
  MODIFY `nhan_vien_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `thong_bao`
--
ALTER TABLE `thong_bao`
  MODIFY `thong_bao_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `vai_tro`
--
ALTER TABLE `vai_tro`
  MODIFY `vai_tro_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `check_in`
--
ALTER TABLE `check_in`
  ADD CONSTRAINT `check_in_dang_ky_goi_tap_id_foreign` FOREIGN KEY (`dang_ky_goi_tap_id`) REFERENCES `dang_ky_goi_tap` (`dang_ky_goi_tap_id`),
  ADD CONSTRAINT `check_in_hoi_vien_id_foreign` FOREIGN KEY (`hoi_vien_id`) REFERENCES `hoi_vien` (`hoi_vien_id`),
  ADD CONSTRAINT `check_in_nguoi_thuc_hien_id_foreign` FOREIGN KEY (`nguoi_thuc_hien_id`) REFERENCES `nguoi_dung` (`nguoi_dung_id`);

--
-- Các ràng buộc cho bảng `chi_tiet_hoa_don`
--
ALTER TABLE `chi_tiet_hoa_don`
  ADD CONSTRAINT `chi_tiet_hoa_don_goi_pt_id_foreign` FOREIGN KEY (`goi_pt_id`) REFERENCES `goi_pt` (`goi_pt_id`),
  ADD CONSTRAINT `chi_tiet_hoa_don_goi_tap_id_foreign` FOREIGN KEY (`goi_tap_id`) REFERENCES `goi_tap` (`goi_tap_id`),
  ADD CONSTRAINT `chi_tiet_hoa_don_hoa_don_id_foreign` FOREIGN KEY (`hoa_don_id`) REFERENCES `hoa_don` (`hoa_don_id`);

--
-- Các ràng buộc cho bảng `dang_ky_goi_pt`
--
ALTER TABLE `dang_ky_goi_pt`
  ADD CONSTRAINT `dang_ky_goi_pt_goi_pt_id_foreign` FOREIGN KEY (`goi_pt_id`) REFERENCES `goi_pt` (`goi_pt_id`),
  ADD CONSTRAINT `dang_ky_goi_pt_hoi_vien_id_foreign` FOREIGN KEY (`hoi_vien_id`) REFERENCES `hoi_vien` (`hoi_vien_id`),
  ADD CONSTRAINT `dang_ky_goi_pt_pt_id_foreign` FOREIGN KEY (`pt_id`) REFERENCES `huan_luyen_vien` (`pt_id`);

--
-- Các ràng buộc cho bảng `dang_ky_goi_tap`
--
ALTER TABLE `dang_ky_goi_tap`
  ADD CONSTRAINT `dang_ky_goi_tap_goi_tap_id_foreign` FOREIGN KEY (`goi_tap_id`) REFERENCES `goi_tap` (`goi_tap_id`),
  ADD CONSTRAINT `dang_ky_goi_tap_hoi_vien_id_foreign` FOREIGN KEY (`hoi_vien_id`) REFERENCES `hoi_vien` (`hoi_vien_id`);

--
-- Các ràng buộc cho bảng `dang_ky_lop`
--
ALTER TABLE `dang_ky_lop`
  ADD CONSTRAINT `dang_ky_lop_hoi_vien_id_foreign` FOREIGN KEY (`hoi_vien_id`) REFERENCES `hoi_vien` (`hoi_vien_id`),
  ADD CONSTRAINT `dang_ky_lop_lop_tap_id_foreign` FOREIGN KEY (`lop_tap_id`) REFERENCES `lop_tap` (`lop_tap_id`);

--
-- Các ràng buộc cho bảng `hoa_don`
--
ALTER TABLE `hoa_don`
  ADD CONSTRAINT `hoa_don_hoi_vien_id_foreign` FOREIGN KEY (`hoi_vien_id`) REFERENCES `hoi_vien` (`hoi_vien_id`),
  ADD CONSTRAINT `hoa_don_nhan_vien_id_foreign` FOREIGN KEY (`nhan_vien_id`) REFERENCES `nhan_vien` (`nhan_vien_id`);

--
-- Các ràng buộc cho bảng `hoi_vien`
--
ALTER TABLE `hoi_vien`
  ADD CONSTRAINT `hoi_vien_nguoi_dung_id_foreign` FOREIGN KEY (`nguoi_dung_id`) REFERENCES `nguoi_dung` (`nguoi_dung_id`);

--
-- Các ràng buộc cho bảng `huan_luyen_vien`
--
ALTER TABLE `huan_luyen_vien`
  ADD CONSTRAINT `huan_luyen_vien_nguoi_dung_id_foreign` FOREIGN KEY (`nguoi_dung_id`) REFERENCES `nguoi_dung` (`nguoi_dung_id`);

--
-- Các ràng buộc cho bảng `lich_pt`
--
ALTER TABLE `lich_pt`
  ADD CONSTRAINT `lich_pt_dang_ky_goi_pt_id_foreign` FOREIGN KEY (`dang_ky_goi_pt_id`) REFERENCES `dang_ky_goi_pt` (`dang_ky_goi_pt_id`),
  ADD CONSTRAINT `lich_pt_hoi_vien_id_foreign` FOREIGN KEY (`hoi_vien_id`) REFERENCES `hoi_vien` (`hoi_vien_id`),
  ADD CONSTRAINT `lich_pt_pt_id_foreign` FOREIGN KEY (`pt_id`) REFERENCES `huan_luyen_vien` (`pt_id`);

--
-- Các ràng buộc cho bảng `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD CONSTRAINT `nguoi_dung_vai_tro_id_foreign` FOREIGN KEY (`vai_tro_id`) REFERENCES `vai_tro` (`vai_tro_id`);

--
-- Các ràng buộc cho bảng `nhan_vien`
--
ALTER TABLE `nhan_vien`
  ADD CONSTRAINT `nhan_vien_nguoi_dung_id_foreign` FOREIGN KEY (`nguoi_dung_id`) REFERENCES `nguoi_dung` (`nguoi_dung_id`);

--
-- Các ràng buộc cho bảng `thong_bao`
--
ALTER TABLE `thong_bao`
  ADD CONSTRAINT `thong_bao_nguoi_dung_id_foreign` FOREIGN KEY (`nguoi_dung_id`) REFERENCES `nguoi_dung` (`nguoi_dung_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

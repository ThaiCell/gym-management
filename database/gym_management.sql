-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 05, 2026 lúc 06:11 AM
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
-- Cấu trúc bảng cho bảng `bai_tap_giao_an_pt`
--

CREATE TABLE `bai_tap_giao_an_pt` (
  `exercise_id` int(10) UNSIGNED NOT NULL,
  `plan_id` int(10) UNSIGNED NOT NULL,
  `ten_bai_tap` varchar(255) NOT NULL,
  `so_hiep` int(11) NOT NULL DEFAULT 3,
  `so_lan` varchar(50) NOT NULL DEFAULT '12',
  `ghi_chu` text DEFAULT NULL,
  `thu_tu` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bai_tap_giao_an_pt`
--

INSERT INTO `bai_tap_giao_an_pt` (`exercise_id`, `plan_id`, `ten_bai_tap`, `so_hiep`, `so_lan`, `ghi_chu`, `thu_tu`) VALUES
(1, 1, 'Di bo tren may', 1, '10 phut', 'Khoi dong nhe.', 1),
(2, 1, 'Squat', 3, '12', 'Giu lung thang, ha nguoi co kiem soat.', 2),
(3, 1, 'Push-up', 3, '10', 'Co the chong goi neu chua du suc.', 3),
(4, 1, 'Plank', 3, '30 giay', 'Giu than nguoi thanh mot duong thang.', 4),
(5, 1, 'Đẩy ngực với tạ', 3, '12 lần', 'Tập trung phát triển cơ ngực.', 1),
(6, 2, 'Đẩy ngực với tạ', 3, '12 lần', 'Tập trung phát triển cơ ngực.', 1),
(7, 3, 'Đẩy ngực với tạ', 3, '12 lần', 'Tập trung phát triển cơ ngực.', 1),
(8, 4, 'Đẩy ngực với tạ', 3, '12 lần', 'Tập trung phát triển cơ ngực.', 1),
(9, 5, 'Đẩy ngực với tạ', 3, '12 lần', 'Tập trung phát triển cơ ngực.', 1),
(10, 2, 'Squat', 3, '12 lần', 'Phát triển cơ chân.', 2),
(11, 3, 'Squat', 3, '12 lần', 'Phát triển cơ chân.', 2),
(12, 4, 'Squat', 3, '12 lần', 'Phát triển cơ chân.', 2),
(13, 5, 'Squat', 3, '12 lần', 'Phát triển cơ chân.', 2),
(14, 2, 'Plank', 3, '30 giây', 'Tăng sức mạnh cơ bụng.', 3),
(15, 3, 'Plank', 3, '30 giây', 'Tăng sức mạnh cơ bụng.', 3),
(16, 4, 'Plank', 3, '30 giây', 'Tăng sức mạnh cơ bụng.', 3),
(17, 5, 'Plank', 3, '30 giây', 'Tăng sức mạnh cơ bụng.', 3),
(18, 6, 'Chạy bộ trên máy', 1, '15 phút', 'Khởi động và đốt mỡ.', 1),
(19, 7, 'Chạy bộ trên máy', 1, '15 phút', 'Khởi động và đốt mỡ.', 1),
(20, 8, 'Chạy bộ trên máy', 1, '15 phút', 'Khởi động và đốt mỡ.', 1),
(21, 9, 'Chạy bộ trên máy', 1, '15 phút', 'Khởi động và đốt mỡ.', 1),
(22, 10, 'Chạy bộ trên máy', 1, '15 phút', 'Khởi động và đốt mỡ.', 1),
(23, 6, 'Đạp xe', 1, '20 phút', 'Tăng sức bền.', 2),
(24, 7, 'Đạp xe', 1, '20 phút', 'Tăng sức bền.', 2),
(25, 8, 'Đạp xe', 1, '20 phút', 'Tăng sức bền.', 2),
(26, 9, 'Đạp xe', 1, '20 phút', 'Tăng sức bền.', 2),
(27, 10, 'Đạp xe', 1, '20 phút', 'Tăng sức bền.', 2),
(28, 6, 'Jumping Jacks', 3, '20 lần', 'Đốt cháy năng lượng.', 3),
(29, 7, 'Jumping Jacks', 3, '20 lần', 'Đốt cháy năng lượng.', 3),
(30, 8, 'Jumping Jacks', 3, '20 lần', 'Đốt cháy năng lượng.', 3),
(31, 9, 'Jumping Jacks', 3, '20 lần', 'Đốt cháy năng lượng.', 3),
(32, 10, 'Jumping Jacks', 3, '20 lần', 'Đốt cháy năng lượng.', 3),
(33, 11, 'Đấm thẳng', 3, '15 lần', 'Luyện kỹ thuật đấm.', 1),
(34, 12, 'Đấm thẳng', 3, '15 lần', 'Luyện kỹ thuật đấm.', 1),
(35, 13, 'Đấm thẳng', 3, '15 lần', 'Luyện kỹ thuật đấm.', 1),
(36, 14, 'Đấm thẳng', 3, '15 lần', 'Luyện kỹ thuật đấm.', 1),
(37, 15, 'Đấm thẳng', 3, '15 lần', 'Luyện kỹ thuật đấm.', 1),
(38, 11, 'Đấm móc', 3, '12 lần', 'Cải thiện phản xạ.', 2),
(39, 12, 'Đấm móc', 3, '12 lần', 'Cải thiện phản xạ.', 2),
(40, 13, 'Đấm móc', 3, '12 lần', 'Cải thiện phản xạ.', 2),
(41, 14, 'Đấm móc', 3, '12 lần', 'Cải thiện phản xạ.', 2),
(42, 15, 'Đấm móc', 3, '12 lần', 'Cải thiện phản xạ.', 2),
(43, 11, 'Nhảy dây', 3, '2 phút', 'Tăng sức bền.', 3),
(44, 12, 'Nhảy dây', 3, '2 phút', 'Tăng sức bền.', 3),
(45, 13, 'Nhảy dây', 3, '2 phút', 'Tăng sức bền.', 3),
(46, 14, 'Nhảy dây', 3, '2 phút', 'Tăng sức bền.', 3),
(47, 15, 'Nhảy dây', 3, '2 phút', 'Tăng sức bền.', 3),
(48, 16, 'Tư thế cái cây', 3, '30 giây', 'Cải thiện thăng bằng.', 1),
(49, 17, 'Tư thế cái cây', 3, '30 giây', 'Cải thiện thăng bằng.', 1),
(50, 18, 'Tư thế cái cây', 3, '30 giây', 'Cải thiện thăng bằng.', 1),
(51, 19, 'Tư thế cái cây', 3, '30 giây', 'Cải thiện thăng bằng.', 1),
(52, 16, 'Tư thế chiến binh', 3, '12 lần', 'Tăng độ dẻo dai.', 2),
(53, 17, 'Tư thế chiến binh', 3, '12 lần', 'Tăng độ dẻo dai.', 2),
(54, 18, 'Tư thế chiến binh', 3, '12 lần', 'Tăng độ dẻo dai.', 2),
(55, 19, 'Tư thế chiến binh', 3, '12 lần', 'Tăng độ dẻo dai.', 2),
(56, 16, 'Plank', 3, '30 giây', 'Tăng sức mạnh cơ bụng.', 3),
(57, 17, 'Plank', 3, '30 giây', 'Tăng sức mạnh cơ bụng.', 3),
(58, 18, 'Plank', 3, '30 giây', 'Tăng sức mạnh cơ bụng.', 3),
(59, 19, 'Plank', 3, '30 giây', 'Tăng sức mạnh cơ bụng.', 3),
(60, 20, 'Deadlift', 3, '10 lần', 'Phát triển cơ lưng.', 1),
(61, 21, 'Deadlift', 3, '10 lần', 'Phát triển cơ lưng.', 1),
(62, 22, 'Deadlift', 3, '10 lần', 'Phát triển cơ lưng.', 1),
(63, 23, 'Deadlift', 3, '10 lần', 'Phát triển cơ lưng.', 1),
(64, 20, 'Shoulder Press', 3, '12 lần', 'Phát triển cơ vai.', 2),
(65, 21, 'Shoulder Press', 3, '12 lần', 'Phát triển cơ vai.', 2),
(66, 22, 'Shoulder Press', 3, '12 lần', 'Phát triển cơ vai.', 2),
(67, 23, 'Shoulder Press', 3, '12 lần', 'Phát triển cơ vai.', 2),
(68, 20, 'Bench Press', 3, '10 lần', 'Phát triển cơ ngực.', 3),
(69, 21, 'Bench Press', 3, '10 lần', 'Phát triển cơ ngực.', 3),
(70, 22, 'Bench Press', 3, '10 lần', 'Phát triển cơ ngực.', 3),
(71, 23, 'Bench Press', 3, '10 lần', 'Phát triển cơ ngực.', 3),
(72, 24, 'Burpees', 3, '12 lần', 'Đốt mỡ toàn thân.', 1),
(73, 25, 'Burpees', 3, '12 lần', 'Đốt mỡ toàn thân.', 1),
(74, 26, 'Burpees', 3, '12 lần', 'Đốt mỡ toàn thân.', 1),
(75, 27, 'Burpees', 3, '12 lần', 'Đốt mỡ toàn thân.', 1),
(76, 24, 'Jumping Jacks', 3, '20 lần', 'Tăng nhịp tim.', 2),
(77, 25, 'Jumping Jacks', 3, '20 lần', 'Tăng nhịp tim.', 2),
(78, 26, 'Jumping Jacks', 3, '20 lần', 'Tăng nhịp tim.', 2),
(79, 27, 'Jumping Jacks', 3, '20 lần', 'Tăng nhịp tim.', 2),
(80, 24, 'Chạy bộ', 1, '15 phút', 'Cải thiện thể lực.', 3),
(81, 25, 'Chạy bộ', 1, '15 phút', 'Cải thiện thể lực.', 3),
(82, 26, 'Chạy bộ', 1, '15 phút', 'Cải thiện thể lực.', 3),
(83, 27, 'Chạy bộ', 1, '15 phút', 'Cải thiện thể lực.', 3);

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
-- Cấu trúc bảng cho bảng `cai_dat`
--

CREATE TABLE `cai_dat` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `khoa` varchar(100) NOT NULL,
  `gia_tri` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `cai_dat`
--

INSERT INTO `cai_dat` (`id`, `khoa`, `gia_tri`, `created_at`, `updated_at`) VALUES
(1, 'ten_phong_gym', 'GYMFIT Fitness Center', '2026-10-15 21:02:55', '2026-10-15 21:02:55'),
(2, 'dia_chi', '123 Đường Nguyễn Văn Linh, Quận 9, TP. Hồ Chí Minh', '2026-10-15 21:02:55', '2026-10-15 21:02:55'),
(3, 'so_dien_thoai', '090999999', '2026-10-15 21:02:55', '2026-10-15 21:02:55'),
(4, 'email_lien_he', 'gymfit@example.com', '2026-10-15 21:02:55', '2026-10-15 21:02:55');

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
(10, 10, 10, '2026-09-27 18:00:00', 2),
(11, 14, 17, '2026-10-01 13:40:22', 2);

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
(10, 10, 6, NULL, 1, 100000.00, 100000.00),
(12, 12, 5, NULL, 1, 200000.00, 200000.00),
(14, 14, 5, NULL, 1, 200000.00, 200000.00),
(15, 15, 6, NULL, 1, 100000.00, 100000.00),
(21, 21, NULL, 1, 1, 1500000.00, 1500000.00),
(22, 22, NULL, 5, 1, 1800000.00, 1800000.00),
(30, 30, NULL, 1, 1, 1500000.00, 1500000.00),
(34, 34, 6, NULL, 1, 100000.00, 100000.00),
(37, 37, 6, NULL, 1, 100000.00, 100000.00);

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
(1, 1, 1, 1, '2026-09-01', '2026-09-30', 5, 'đang hoạt động'),
(2, 2, 2, 2, '2026-08-01', '2026-09-30', 12, 'đang hoạt động'),
(3, 3, 3, 3, '2026-07-01', '2026-09-30', 19, 'đang hoạt động'),
(4, 4, 4, 4, '2026-09-01', '2026-09-15', 0, 'đã kết thúc'),
(5, 5, 1, 1, '2026-09-10', '2026-10-09', 8, 'đang hoạt động'),
(6, 12, 1, 1, '2026-10-01', '2026-10-30', 10, 'đang hoạt động'),
(7, 12, 4, 1, '2026-10-01', '2026-10-15', 5, 'đang hoạt động'),
(8, 14, 1, 1, '2026-10-01', '2026-10-30', 10, 'đang hoạt động'),
(9, 14, 4, 1, '2026-10-01', '2026-10-15', 5, 'đang hoạt động'),
(10, 14, 3, 1, '2026-10-01', '2026-12-29', 30, 'đang hoạt động'),
(11, 14, 2, 1, '2026-10-01', '2026-11-29', 20, 'đang hoạt động'),
(12, 11, 3, 1, '2026-10-03', '2026-12-31', 30, 'đã hủy'),
(13, 11, 4, 1, '2026-10-03', '2026-10-17', 5, 'đã hủy'),
(14, 11, 1, 1, '2026-10-11', '2026-11-09', 10, 'đã hủy'),
(15, 11, 3, 1, '2026-10-11', '2027-01-08', 30, 'đã hủy'),
(16, 11, 2, 1, '2026-10-11', '2026-12-09', 20, 'đã hủy'),
(17, 26, 1, 6, '2026-10-15', '2026-11-14', 10, 'đang hoạt động'),
(18, 19, 1, 2, '2026-10-15', '2026-11-14', 10, 'đang hoạt động'),
(19, 17, 1, 1, '2026-10-15', '2026-11-14', 10, 'đang hoạt động'),
(20, 18, 1, 2, '2026-10-15', '2026-11-14', 10, 'đang hoạt động'),
(21, 24, 1, 5, '2026-10-15', '2026-11-14', 10, 'đang hoạt động'),
(22, 25, 1, 5, '2026-10-15', '2026-11-14', 10, 'đang hoạt động'),
(23, 16, 1, 1, '2026-10-15', '2026-11-14', 10, 'đang hoạt động'),
(24, 22, 1, 4, '2026-10-15', '2026-11-14', 10, 'đang hoạt động'),
(25, 27, 1, 6, '2026-10-15', '2026-11-14', 10, 'đang hoạt động'),
(26, 21, 1, 3, '2026-10-15', '2026-11-14', 10, 'đang hoạt động'),
(27, 20, 1, 3, '2026-10-15', '2026-11-14', 10, 'đang hoạt động'),
(28, 23, 1, 4, '2026-10-15', '2026-11-14', 10, 'đang hoạt động'),
(32, 11, 4, 1, '2026-10-22', '2026-11-05', 5, 'đã hủy'),
(33, 11, 1, 1, '2026-10-22', '2026-11-20', 10, 'đã hủy'),
(34, 11, 5, 1, '2026-10-22', '2026-12-05', 12, 'đã hủy'),
(35, 11, 4, 1, '2026-10-22', '2026-11-05', 5, 'đã hủy'),
(36, 11, 5, 1, '2026-10-22', '2026-12-05', 12, 'đã hủy'),
(37, 11, 1, 1, '2026-10-22', '2026-11-20', 10, 'đang hoạt động'),
(38, 8, 5, 1, '2026-10-22', '2026-12-05', 12, 'đang hoạt động'),
(39, 15, 4, 1, '2026-10-24', '2026-11-07', 5, 'đã hủy'),
(40, 15, 4, 1, '2026-10-24', '2026-11-07', 5, 'đã hủy'),
(41, 15, 1, 1, '2026-10-24', '2026-11-22', 10, 'đã hủy'),
(42, 15, 1, 4, '2026-10-24', '2026-11-22', 10, 'đã hủy'),
(43, 15, 5, 2, '2026-10-24', '2026-12-07', 12, 'đã hủy'),
(44, 15, 1, 2, '2026-10-24', '2026-11-22', 10, 'đã hủy'),
(45, 15, 4, 2, '2026-10-24', '2026-11-07', 5, 'đã hủy'),
(46, 15, 1, 1, '2026-10-26', '2026-11-24', 10, 'đã hủy'),
(47, 15, 4, 1, '2026-10-31', '2026-11-14', 5, 'đã hủy'),
(48, 15, 5, 6, '2026-10-31', '2026-12-14', 12, 'đã hủy'),
(49, 15, 1, 5, '2026-10-31', '2026-11-29', 10, 'đã hủy'),
(50, 15, 1, 1, '2026-11-05', '2026-12-04', 10, 'đã hủy'),
(51, 15, 4, 1, '2026-11-05', '2026-11-19', 5, 'đã hủy'),
(52, 15, 1, 2, '2026-11-05', '2026-12-04', 10, 'đã hủy');

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
(1, 1, 1, '2026-09-01', '2026-09-30', 22, 'đã hết hạn'),
(2, 2, 2, '2026-08-01', '2026-10-29', 45, 'đang hoạt động'),
(3, 3, 3, '2026-07-01', '2026-12-27', 70, 'đang hoạt động'),
(4, 4, 4, '2026-01-01', '2026-12-31', 95, 'đang hoạt động'),
(5, 5, 1, '2026-09-10', '2026-10-09', 18, 'đã hết hạn'),
(6, 6, 5, '2026-09-15', '2026-10-14', 25, 'đang hoạt động'),
(7, 7, 2, '2026-08-15', '2026-11-12', 50, 'đang hoạt động'),
(8, 8, 3, '2026-06-01', '2026-11-27', 40, 'đang hoạt động'),
(9, 9, 1, '2026-09-20', '2026-10-19', 25, 'đang hoạt động'),
(10, 10, 6, '2026-09-25', '2026-10-01', 5, 'đang hoạt động'),
(12, 13, 4, '2026-09-30', '2027-09-30', 365, 'đang hoạt động'),
(16, 12, 6, '2026-09-30', '2026-10-07', 7, 'đang hoạt động'),
(17, 14, 5, '2026-10-01', '2026-10-31', 30, 'đang hoạt động'),
(19, 15, 5, '2026-10-01', '2026-10-31', 30, 'đã hết hạn'),
(21, 11, 5, '2026-10-03', '2026-11-02', 30, 'đã hết hạn'),
(22, 1, 6, '2026-10-04', '2026-10-11', 7, 'đang hoạt động'),
(23, 11, 6, '2026-11-05', '2026-11-12', 7, 'đã hủy'),
(24, 15, 6, '2026-11-05', '2026-11-12', 7, 'đã hủy');

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
(10, 2, 10, '2026-09-10 17:00:00', 'đã đăng ký'),
(11, 1, 12, '2026-10-01 09:59:59', 'đã hủy'),
(12, 3, 12, '2026-10-01 10:00:19', 'đã hủy'),
(13, 2, 12, '2026-10-01 10:00:23', 'đã hủy'),
(14, 6, 12, '2026-10-01 10:06:28', 'đã hủy'),
(15, 5, 12, '2026-10-01 10:06:31', 'đã hủy'),
(16, 4, 12, '2026-10-01 10:08:38', 'đã hủy'),
(17, 1, 14, '2026-10-01 11:50:11', 'đang hoạt động'),
(18, 1, 12, '2026-10-02 03:34:58', 'đã hủy'),
(19, 3, 12, '2026-10-02 03:35:14', 'đã hủy'),
(20, 1, 12, '2026-10-02 03:41:31', 'đã hủy'),
(21, 1, 12, '2026-10-02 03:42:54', 'đang hoạt động'),
(22, 2, 12, '2026-10-02 03:43:19', 'đang hoạt động'),
(23, 3, 12, '2026-10-02 03:43:21', 'đang hoạt động'),
(24, 5, 12, '2026-10-02 03:43:24', 'đang hoạt động'),
(25, 6, 12, '2026-10-02 03:43:27', 'đang hoạt động'),
(26, 4, 12, '2026-10-02 03:43:29', 'đang hoạt động'),
(27, 5, 15, '2026-10-02 08:46:15', 'đã hủy'),
(28, 2, 15, '2026-10-02 08:46:19', 'đã hủy'),
(29, 3, 15, '2026-10-02 08:46:21', 'đã hủy'),
(30, 1, 15, '2026-10-02 08:46:23', 'đã hủy'),
(31, 4, 15, '2026-10-02 08:46:29', 'đã hủy'),
(32, 3, 11, '2026-10-03 08:06:04', 'đã hủy'),
(33, 2, 11, '2026-10-03 08:06:10', 'đã hủy'),
(34, 3, 11, '2026-10-04 11:26:47', 'đã hủy'),
(35, 1, 11, '2026-10-24 04:44:18', 'đã hủy'),
(36, 2, 11, '2026-10-24 04:44:20', 'đã hủy'),
(37, 3, 11, '2026-10-24 04:44:22', 'đã hủy'),
(38, 4, 11, '2026-10-24 04:44:27', 'đã hủy'),
(39, 5, 11, '2026-10-24 04:44:29', 'đã hủy'),
(40, 1, 15, '2026-10-24 06:14:50', 'đã hủy'),
(41, 1, 15, '2026-10-31 05:49:02', 'đã hủy'),
(42, 2, 15, '2026-10-31 06:09:10', 'đã hủy'),
(43, 3, 15, '2026-10-31 06:40:57', 'đã hủy'),
(44, 1, 15, '2026-11-05 03:47:27', 'đang hoạt động'),
(45, 2, 15, '2026-11-05 03:47:37', 'đang hoạt động');

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
-- Cấu trúc bảng cho bảng `giao_an_pt`
--

CREATE TABLE `giao_an_pt` (
  `plan_id` int(10) UNSIGNED NOT NULL,
  `pt_id` int(11) NOT NULL,
  `hoi_vien_id` int(11) NOT NULL,
  `ten_giao_an` varchar(255) NOT NULL,
  `muc_tieu` text DEFAULT NULL,
  `ghi_chu` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `giao_an_pt`
--

INSERT INTO `giao_an_pt` (`plan_id`, `pt_id`, `hoi_vien_id`, `ten_giao_an`, `muc_tieu`, `ghi_chu`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Giao an giam mo 4 tuan', 'Giam mo, cai thien suc ben va duy tri khoi co.', 'Tap 4 buoi moi tuan; khoi dong ky va dieu chinh cuong do theo the luc.', '2026-11-05 04:08:55', '2026-11-05 04:08:55'),
(2, 1, 2, 'Giáo án tăng cơ 4 tuần', 'Tăng khối lượng cơ bắp và sức mạnh.', 'Giáo án được xây dựng riêng cho từng hội viên.', '2026-11-05 04:52:16', '2026-11-05 04:52:16'),
(3, 1, 3, 'Giáo án tăng cơ 4 tuần', 'Tăng khối lượng cơ bắp và sức mạnh.', 'Giáo án được xây dựng riêng cho từng hội viên.', '2026-11-05 04:52:16', '2026-11-05 04:52:16'),
(4, 1, 4, 'Giáo án tăng cơ 4 tuần', 'Tăng khối lượng cơ bắp và sức mạnh.', 'Giáo án được xây dựng riêng cho từng hội viên.', '2026-11-05 04:52:16', '2026-11-05 04:52:16'),
(5, 1, 5, 'Giáo án tăng cơ 4 tuần', 'Tăng khối lượng cơ bắp và sức mạnh.', 'Giáo án được xây dựng riêng cho từng hội viên.', '2026-11-05 04:52:16', '2026-11-05 04:52:16'),
(6, 2, 6, 'Giáo án giảm cân Cardio', 'Giảm cân, đốt mỡ và tăng sức bền.', 'Giáo án được xây dựng riêng cho từng hội viên.', '2026-11-05 04:52:16', '2026-11-05 04:52:16'),
(7, 2, 7, 'Giáo án giảm cân Cardio', 'Giảm cân, đốt mỡ và tăng sức bền.', 'Giáo án được xây dựng riêng cho từng hội viên.', '2026-11-05 04:52:16', '2026-11-05 04:52:16'),
(8, 2, 8, 'Giáo án giảm cân Cardio', 'Giảm cân, đốt mỡ và tăng sức bền.', 'Giáo án được xây dựng riêng cho từng hội viên.', '2026-11-05 04:52:16', '2026-11-05 04:52:16'),
(9, 2, 9, 'Giáo án giảm cân Cardio', 'Giảm cân, đốt mỡ và tăng sức bền.', 'Giáo án được xây dựng riêng cho từng hội viên.', '2026-11-05 04:52:16', '2026-11-05 04:52:16'),
(10, 2, 10, 'Giáo án giảm cân Cardio', 'Giảm cân, đốt mỡ và tăng sức bền.', 'Giáo án được xây dựng riêng cho từng hội viên.', '2026-11-05 04:52:16', '2026-11-05 04:52:16'),
(11, 3, 11, 'Giáo án Boxing cơ bản', 'Cải thiện kỹ thuật Boxing và phản xạ.', 'Giáo án được xây dựng riêng cho từng hội viên.', '2026-11-05 04:52:16', '2026-11-05 04:52:16'),
(12, 3, 12, 'Giáo án Boxing cơ bản', 'Cải thiện kỹ thuật Boxing và phản xạ.', 'Giáo án được xây dựng riêng cho từng hội viên.', '2026-11-05 04:52:16', '2026-11-05 04:52:16'),
(13, 3, 13, 'Giáo án Boxing cơ bản', 'Cải thiện kỹ thuật Boxing và phản xạ.', 'Giáo án được xây dựng riêng cho từng hội viên.', '2026-11-05 04:52:16', '2026-11-05 04:52:16'),
(14, 3, 14, 'Giáo án Boxing cơ bản', 'Cải thiện kỹ thuật Boxing và phản xạ.', 'Giáo án được xây dựng riêng cho từng hội viên.', '2026-11-05 04:52:16', '2026-11-05 04:52:16'),
(15, 3, 15, 'Giáo án Boxing cơ bản', 'Cải thiện kỹ thuật Boxing và phản xạ.', 'Giáo án được xây dựng riêng cho từng hội viên.', '2026-11-05 04:52:16', '2026-11-05 04:52:16'),
(16, 4, 16, 'Giáo án Yoga và Fitness', 'Tăng độ dẻo dai và cân bằng cơ thể.', 'Giáo án được xây dựng riêng cho từng hội viên.', '2026-11-05 04:52:16', '2026-11-05 04:52:16'),
(17, 4, 17, 'Giáo án Yoga và Fitness', 'Tăng độ dẻo dai và cân bằng cơ thể.', 'Giáo án được xây dựng riêng cho từng hội viên.', '2026-11-05 04:52:16', '2026-11-05 04:52:16'),
(18, 4, 18, 'Giáo án Yoga và Fitness', 'Tăng độ dẻo dai và cân bằng cơ thể.', 'Giáo án được xây dựng riêng cho từng hội viên.', '2026-11-05 04:52:16', '2026-11-05 04:52:16'),
(19, 4, 19, 'Giáo án Yoga và Fitness', 'Tăng độ dẻo dai và cân bằng cơ thể.', 'Giáo án được xây dựng riêng cho từng hội viên.', '2026-11-05 04:52:16', '2026-11-05 04:52:16'),
(20, 5, 20, 'Giáo án thể hình chuyên nghiệp', 'Phát triển hình thể và khối lượng cơ.', 'Giáo án được xây dựng riêng cho từng hội viên.', '2026-11-05 04:52:16', '2026-11-05 04:52:16'),
(21, 5, 21, 'Giáo án thể hình chuyên nghiệp', 'Phát triển hình thể và khối lượng cơ.', 'Giáo án được xây dựng riêng cho từng hội viên.', '2026-11-05 04:52:16', '2026-11-05 04:52:16'),
(22, 5, 22, 'Giáo án thể hình chuyên nghiệp', 'Phát triển hình thể và khối lượng cơ.', 'Giáo án được xây dựng riêng cho từng hội viên.', '2026-11-05 04:52:16', '2026-11-05 04:52:16'),
(23, 5, 23, 'Giáo án thể hình chuyên nghiệp', 'Phát triển hình thể và khối lượng cơ.', 'Giáo án được xây dựng riêng cho từng hội viên.', '2026-11-05 04:52:16', '2026-11-05 04:52:16'),
(24, 6, 24, 'Giáo án giảm mỡ toàn thân', 'Giảm mỡ và cải thiện thể lực.', 'Giáo án được xây dựng riêng cho từng hội viên.', '2026-11-05 04:52:16', '2026-11-05 04:52:16'),
(25, 6, 25, 'Giáo án giảm mỡ toàn thân', 'Giảm mỡ và cải thiện thể lực.', 'Giáo án được xây dựng riêng cho từng hội viên.', '2026-11-05 04:52:16', '2026-11-05 04:52:16'),
(26, 6, 26, 'Giáo án giảm mỡ toàn thân', 'Giảm mỡ và cải thiện thể lực.', 'Giáo án được xây dựng riêng cho từng hội viên.', '2026-11-05 04:52:16', '2026-11-05 04:52:16'),
(27, 6, 27, 'Giáo án giảm mỡ toàn thân', 'Giảm mỡ và cải thiện thể lực.', 'Giáo án được xây dựng riêng cho từng hội viên.', '2026-11-05 04:52:16', '2026-11-05 04:52:16');

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
  `anh` varchar(255) DEFAULT NULL,
  `trang_thai` varchar(30) NOT NULL DEFAULT 'hoat_dong'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `goi_pt`
--

INSERT INTO `goi_pt` (`goi_pt_id`, `ten_goi_pt`, `gia`, `so_buoi`, `thoi_han_ngay`, `anh`, `trang_thai`) VALUES
(1, 'PT Cơ Bản 10 Buổi', 1500000.00, 10, 30, 'img/pt1.jpg', 'hoat_dong'),
(2, 'PT Tiêu Chuẩn 20 Buổi', 2800000.00, 20, 60, 'img/pt2.jpg', 'hoat_dong'),
(3, 'PT Premium 30 Buổi', 3900000.00, 30, 90, 'img/pt3.jpg', 'hoat_dong'),
(4, 'PT Cá Nhân 5 Buổi', 800000.00, 5, 15, 'img/pt4.jpg', 'hoat_dong'),
(5, 'PT Giảm Mỡ 12 Buổi', 1800000.00, 12, 45, 'img/pt5.jpg', 'hoat_dong'),
(6, 'PT Tăng Cơ 24 Buổi', 3200000.00, 24, 75, 'img/pt6.jpg', 'hoat_dong');

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
(10, 10, 3, '2026-09-25 16:00:00', 100000.00, 'đã thanh toán', 'Tiền mặt'),
(12, 15, 2, '2026-10-01 13:54:55', 200000.00, 'đã thanh toán', 'Tiền mặt'),
(14, 11, 2, '2026-10-03 08:33:52', 200000.00, 'đã thanh toán', 'Tiền mặt'),
(15, 1, 2, '2026-10-04 12:07:23', 100000.00, 'đã thanh toán', 'Tiền mặt'),
(21, 11, 2, '2026-10-22 05:47:55', 1500000.00, 'đã thanh toán', 'Tiền mặt'),
(22, 8, 1, '2026-10-22 06:04:07', 1800000.00, 'đã thanh toán', 'Chuyển khoản'),
(30, 15, 1, '2026-10-26 05:38:44', 1500000.00, 'đã hủy', NULL),
(31, 15, 1, '2026-10-31 05:51:28', 800000.00, 'đã hủy', NULL),
(32, 15, 1, '2026-10-31 06:08:53', 1800000.00, 'đã hủy', NULL),
(33, 15, 1, '2026-10-31 06:18:38', 1500000.00, 'đã hủy', NULL),
(34, 11, 1, '2026-11-05 03:22:16', 100000.00, 'đã hủy', NULL),
(35, 15, 1, '2026-11-05 03:36:47', 1500000.00, 'đã hủy', NULL),
(36, 15, 1, '2026-11-05 03:38:01', 800000.00, 'đã hủy', NULL),
(37, 15, 1, '2026-11-05 03:46:09', 100000.00, 'đã hủy', NULL),
(38, 15, 1, '2026-11-05 03:46:39', 1500000.00, 'đã hủy', NULL);

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
(1, 5, '2002-05-10', '0901234567', 'Thành phố Hồ Chí Minh', '2026-01-05'),
(2, 6, '2001-08-15', '0901000002', 'Thành phố Hồ Chí Minh', '2026-01-10'),
(3, 7, '2003-02-20', '0901000003', 'Bình Dương', '2026-01-15'),
(4, 8, '2000-11-12', '0901000004', 'Thành phố Hồ Chí Minh', '2026-02-01'),
(5, 9, '2002-07-25', '0901000005', 'Đồng Nai', '2026-02-10'),
(6, 10, '2001-04-18', '0901000006', 'Thành phố Hồ Chí Minh', '2026-02-20'),
(7, 15, '1999-03-11', '0901000007', 'Thành phố Hồ Chí Minh', '2026-03-01'),
(8, 16, '2003-09-22', '0901000008', 'Long An', '2026-03-05'),
(9, 17, '2000-06-30', '0901000009', 'Thành phố Hồ Chí Minh', '2026-03-12'),
(10, 18, '2002-12-01', '0901000010', 'Thành phố Hồ Chí Minh', '2026-03-20'),
(11, 19, NULL, NULL, NULL, '2026-09-29'),
(12, 20, NULL, NULL, NULL, '2026-09-29'),
(13, 21, NULL, NULL, NULL, '2026-09-30'),
(14, 22, NULL, NULL, NULL, '2026-10-01'),
(15, 23, NULL, '090999996', 'hà nội', '2026-10-01'),
(16, 29, '2003-01-01', '0900000029', 'TP. Hồ Chí Minh', '2026-10-15'),
(17, 30, '2003-01-01', '0900000030', 'TP. Hồ Chí Minh', '2026-10-15'),
(18, 31, '2003-01-01', '0900000031', 'TP. Hồ Chí Minh', '2026-10-15'),
(19, 32, '2003-01-01', '0900000032', 'TP. Hồ Chí Minh', '2026-10-15'),
(20, 33, '2003-01-01', '0900000033', 'TP. Hồ Chí Minh', '2026-10-15'),
(21, 34, '2003-01-01', '0900000034', 'TP. Hồ Chí Minh', '2026-10-15'),
(22, 35, '2003-01-01', '0900000035', 'TP. Hồ Chí Minh', '2026-10-15'),
(23, 36, '2003-01-01', '0900000036', 'TP. Hồ Chí Minh', '2026-10-15'),
(24, 37, '2003-01-01', '0900000037', 'TP. Hồ Chí Minh', '2026-10-15'),
(25, 38, '2003-01-01', '0900000038', 'TP. Hồ Chí Minh', '2026-10-15'),
(26, 39, '2003-01-01', '0900000039', 'TP. Hồ Chí Minh', '2026-10-15'),
(27, 40, '2003-01-01', '0900000040', 'TP. Hồ Chí Minh', '2026-10-15');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `huan_luyen_vien`
--

CREATE TABLE `huan_luyen_vien` (
  `pt_id` int(11) NOT NULL,
  `nguoi_dung_id` int(11) NOT NULL,
  `chuyen_mon` varchar(255) DEFAULT NULL,
  `thanh_tich` text DEFAULT NULL,
  `so_dien_thoai` varchar(20) DEFAULT NULL,
  `anh` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `huan_luyen_vien`
--

INSERT INTO `huan_luyen_vien` (`pt_id`, `nguoi_dung_id`, `chuyen_mon`, `thanh_tich`, `so_dien_thoai`, `anh`) VALUES
(1, 11, 'Tăng cơ và phát triển cơ bắp', 'Chứng chỉ Huấn luyện viên thể hình chuyên nghiệp; Giải Nhất thể hình cấp thành phố năm 2024', '0902000001', 'img/trainer1.jpg'),
(2, 12, 'Giảm cân và Cardio', 'Chứng chỉ Huấn luyện viên giảm cân và Cardio; Chứng chỉ sơ cấp cứu thể thao', '0902000002', 'img/trainer4.jpg'),
(3, 13, 'Boxing', 'Chứng chỉ Huấn luyện viên Boxing; Thành tích thi đấu Boxing phong trào', '0902000003', 'img/trainer3.jpg'),
(4, 14, 'Yoga và Fitness nâng cao', 'Chứng chỉ Yoga quốc tế; Chứng chỉ Fitness nâng cao', '0902000004', 'img/trainer5.jpg'),
(5, 27, 'Thể hình chuyên nghiệp', 'Chứng chỉ Huấn luyện viên thể hình chuyên nghiệp; Thành tích thi đấu thể hình', '0903000005', 'img/trainer6.jpg'),
(6, 28, 'Giảm cân và fitness', 'Chứng chỉ Huấn luyện viên Fitness; Chứng chỉ tư vấn giảm cân', '0903000006', 'img/trainer2.jpg');

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
(1, 1, 1, 1, '2026-09-29 07:00:00', '2026-09-29 08:00:00', 'đã hoàn thành'),
(2, 2, 2, 2, '2026-09-29 09:00:00', '2026-09-29 10:00:00', 'đã đặt lịch'),
(3, 3, 3, 3, '2026-09-29 15:00:00', '2026-09-29 16:00:00', 'đã hoàn thành'),
(4, 5, 5, 1, '2026-09-30 08:00:00', '2026-09-30 09:00:00', 'đã đặt lịch'),
(5, 1, 1, 1, '2026-10-01 07:00:00', '2026-10-01 08:00:00', 'đã hoàn thành'),
(6, 11, 14, 1, '2026-10-02 19:44:00', '2026-10-02 20:45:00', 'đã hủy'),
(7, 13, 11, 1, '2026-10-03 15:34:00', '2026-10-03 20:34:00', 'đã đặt lịch');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lien_he`
--

CREATE TABLE `lien_he` (
  `lien_he_id` int(11) NOT NULL,
  `ho_ten` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `so_dien_thoai` varchar(20) NOT NULL,
  `noi_dung` text NOT NULL,
  `trang_thai` varchar(30) NOT NULL DEFAULT 'chưa xử lý',
  `nhan_vien_id` int(11) DEFAULT NULL,
  `phan_hoi` text DEFAULT NULL,
  `tao_luc` datetime NOT NULL DEFAULT current_timestamp(),
  `xu_ly_luc` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `lien_he`
--

INSERT INTO `lien_he` (`lien_he_id`, `ho_ten`, `email`, `so_dien_thoai`, `noi_dung`, `trang_thai`, `nhan_vien_id`, `phan_hoi`, `tao_luc`, `xu_ly_luc`) VALUES
(1, 'Trương Trí Thiện', 'truongtrithien2008@gmail.com', '0377124762', 'mmb', 'đã xử lý', 1, 'bmb', '2026-11-05 03:19:11', '2026-11-05 03:21:17'),
(2, 'Trương Trí Thiện', 'truongtrithien2008@gmail.com', '0377124762', '123', 'chưa xử lý', NULL, NULL, '2026-11-05 03:23:44', NULL);

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
  `anh` varchar(255) DEFAULT NULL,
  `trang_thai` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `lop_tap`
--

INSERT INTO `lop_tap` (`lop_tap_id`, `ten_lop`, `mo_ta`, `lich_tap`, `suc_chua`, `anh`, `trang_thai`) VALUES
(1, 'Yoga Cơ Bản', 'Lớp yoga dành cho người mới bắt đầu', 'Thứ 2 - 4 - 6, 08:00', 20, 'img/yoga.jpg', 'hoạt_động'),
(2, 'Cardio Đốt Mỡ', 'Lớp cardio giúp tăng sức bền và đốt cháy năng lượng', 'Thứ 3 - 5 - 7, 17:00', 25, 'img/cardio.jpg', 'hoạt_động'),
(3, 'Gym Tăng Cơ', 'Lớp hướng dẫn tập luyện tăng cơ', 'Thứ 2 - 4 - 6, 18:00', 20, 'img/gym-tang-co.jpg', 'hoạt_động'),
(4, 'Fitness Toàn Thân', 'Bài tập fitness toàn thân', 'Chủ Nhật, 08:00', 30, 'img/fitness-toan-than.jpg', 'hoạt_động'),
(5, 'Strength', 'Tập trung phát triển sức mạnh, cơ bắp và khả năng vận động.', 'Thứ 2 - 4 - 6, 20:00', 15, 'img/strength.jpg', 'hoạt_động'),
(6, 'Boxing', 'Rèn luyện sức mạnh, phản xạ và khả năng phối hợp thông qua các bài tập Boxing.', 'Thứ 3 - 5 - 7, 19:30', 12, 'img/boxing.jpg', 'ngừng_hoạt_động');

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
(9, '2026_09_28_153614_create_gym_tables', 2),
(10, '2026_10_13_044238_create_personal_access_tokens_table', 3),
(11, '2026_10_16_000001_create_cai_dat_table', 4);

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
(1, 1, 'Nguyễn Văn Admin', 'admin@gym.com', '$2y$12$zdYNzlsjZ98wjJRsg/vwm.BAR1Pyb3UOsaMZULjz4INNeqYvJyyW6', 'hoat_dong'),
(2, 2, 'Trần Văn An', 'an@gym.com', '$2y$12$J97/pjJSVBwdj6Q6nqDm0.BvMG5ySkGl35J1czKOc4ATb0aaNQR7u', 'hoat_dong'),
(3, 2, 'Lê Thị Hoa', 'hoa@gym.com', '123456', 'hoat_dong'),
(4, 2, 'Phạm Văn Nam', 'nam@gym.com', '123456', 'hoat_dong'),
(5, 3, 'Nguyễn Văn Minh', 'minh@gmail.com', '123456', 'hoat_dong'),
(6, 3, 'Trần Thị Lan', 'lan@gmail.com', '123456', 'hoat_dong'),
(7, 3, 'Lê Văn Hùng', 'hung@gmail.com', '123456', 'hoat_dong'),
(8, 3, 'Phạm Thị Mai', 'mai@gmail.com', '123456', 'hoat_dong'),
(9, 3, 'Võ Thanh Tùng', 'tung@gmail.com', '123456', 'hoat_dong'),
(10, 3, 'Đặng Thị Ngọc', 'ngoc@gmail.com', '123456', 'hoat_dong'),
(11, 4, 'Nguyễn Hoàng Long', 'long@gym.com', '123456', 'hoat_dong'),
(12, 4, 'Trần Minh Khoa', 'khoa@gym.com', '123456', 'hoat_dong'),
(13, 4, 'Lê Quốc Bảo', 'bao@gym.com', '$2y$12$H47JAMChTZBOWLY4RfBtcu95f9ZcHQ6tnoQuH/k9EupUmI.oFHUxW', 'hoat_dong'),
(14, 4, 'Phạm Thanh Phong', 'phong@gym.com', '123456', 'hoat_dong'),
(15, 3, 'Nguyễn Hoàng Nam', 'nam1@gmail.com', '123456', 'hoat_dong'),
(16, 3, 'Trần Quốc Việt', 'viet@gmail.com', '123456', 'hoat_dong'),
(17, 3, 'Lê Minh Tuấn', 'tuan@gmail.com', '123456', 'hoat_dong'),
(18, 3, 'Phạm Ngọc Anh', 'anh@gmail.com', '123456', 'hoat_dong'),
(19, 3, 'Trương Trí Thiện', 'truongtrithien2008@gmail.com', '$2y$12$vooz56DW.YnawpY5fJYOAedbhfw.td3s8ZQEvqLe7sXpcvhXA2vki', 'hoat_dong'),
(20, 3, 'thái', 'thai@gmail.com', '$2y$12$MTMe6TfSFGGf49Gr.H3TM.MniGrJyYxR4YsuWwXfnUbzv3JfnmqKu', 'hoat_dong'),
(21, 3, 'ali', 'ali@gmail.com', '$2y$12$GTbbenfb650KvMdkYQRBf.AGQBnW6pZWOxihpB6kn1lH5GqnCRkke', 'hoat_dong'),
(22, 3, 'ali', 'alo@gmail.com', '$2y$12$WVAz9hYnukGA4I65C95Nfuh31PFFPhblNoCe81/jmWbZUGcm0C4.i', 'hoat_dong'),
(23, 3, 'thiengay', 'gay@gmail.com', '$2y$12$o26IFfqeUrGOmKGeD8LpxecgSl3x3EDE0Iy1nmztxqxcNpA0cFcvK', 'hoat_dong'),
(24, 3, 'Nguyễn Minh Đức', 'minhduc@gmail.com', '123456', 'hoat_dong'),
(25, 3, 'Trần Ngọc Hà', 'ngocha@gmail.com', '123456', 'hoat_dong'),
(26, 3, 'Lê Quốc Anh', 'quocanh@gym.com', '123456', 'hoat_dong'),
(27, 4, 'Phạm Minh Tuấn', 'minhtuan@gym.com', '123456', 'hoat_dong'),
(28, 4, 'Võ Ngọc Hân', 'ngochan@gym.com', '123456', 'hoat_dong'),
(29, 3, 'Nguyễn Minh Quân', 'minhquan@gmail.com', '123456', 'hoat_dong'),
(30, 3, 'Trần Gia Bảo', 'giabao@gmail.com', '123456', 'hoat_dong'),
(31, 3, 'Lê Hoàng Phúc', 'hoangphuc@gmail.com', '123456', 'hoat_dong'),
(32, 3, 'Phạm Đức Anh', 'ducanh@gmail.com', '123456', 'hoat_dong'),
(33, 3, 'Võ Thanh Bình', 'thanhbinh@gmail.com', '123456', 'hoat_dong'),
(34, 3, 'Đặng Quốc Huy', 'quochuy@gmail.com', '123456', 'hoat_dong'),
(35, 3, 'Nguyễn Ngọc Mai', 'ngocmai@gmail.com', '123456', 'hoat_dong'),
(36, 3, 'Trần Thu Hà', 'thuha@gmail.com', '123456', 'hoat_dong'),
(37, 3, 'Lê Khánh Linh', 'khanhlinh@gmail.com', '123456', 'hoat_dong'),
(38, 3, 'Phạm Minh Châu', 'minhchau@gmail.com', '123456', 'hoat_dong'),
(39, 3, 'Võ Bảo Ngọc', 'baongoc@gmail.com', '123456', 'hoat_dong'),
(40, 3, 'Đỗ Nhật Nam', 'nhatnam@gmail.com', '123456', 'hoat_dong');

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
-- Cấu trúc bảng cho bảng `nhat_ky_buoi_tap_pt`
--

CREATE TABLE `nhat_ky_buoi_tap_pt` (
  `log_id` int(10) UNSIGNED NOT NULL,
  `pt_id` int(11) NOT NULL,
  `hoi_vien_id` int(11) NOT NULL,
  `lich_pt_id` int(11) DEFAULT NULL,
  `ngay_tap` date NOT NULL,
  `noi_dung` text NOT NULL,
  `ket_qua` varchar(255) DEFAULT NULL,
  `ghi_chu` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nhat_ky_buoi_tap_pt`
--

INSERT INTO `nhat_ky_buoi_tap_pt` (`log_id`, `pt_id`, `hoi_vien_id`, `lich_pt_id`, `ngay_tap`, `noi_dung`, `ket_qua`, `ghi_chu`, `created_at`) VALUES
(1, 1, 1, NULL, '2026-11-01', 'Khoi dong 10 phut; squat 3 x 12; push-up 3 x 10; plank 3 x 30 giay.', 'Hoan thanh buoi tap', 'Hoc vien thuc hien dung ky thuat, can cai thien suc ben.', '2026-11-05 04:08:55'),
(2, 1, 1, NULL, '2026-11-05', 'Tập đẩy ngực, Squat và Plank.', 'Hoàn thành buổi tập', 'Hội viên hoàn thành buổi tập theo hướng dẫn của huấn luyện viên.', '2026-11-05 04:52:16'),
(3, 1, 2, NULL, '2026-11-05', 'Tập đẩy ngực, Squat và Plank.', 'Hoàn thành buổi tập', 'Hội viên hoàn thành buổi tập theo hướng dẫn của huấn luyện viên.', '2026-11-05 04:52:16'),
(4, 1, 3, NULL, '2026-11-05', 'Tập đẩy ngực, Squat và Plank.', 'Hoàn thành buổi tập', 'Hội viên hoàn thành buổi tập theo hướng dẫn của huấn luyện viên.', '2026-11-05 04:52:16'),
(5, 1, 4, NULL, '2026-11-05', 'Tập đẩy ngực, Squat và Plank.', 'Hoàn thành buổi tập', 'Hội viên hoàn thành buổi tập theo hướng dẫn của huấn luyện viên.', '2026-11-05 04:52:16'),
(6, 1, 5, NULL, '2026-11-05', 'Tập đẩy ngực, Squat và Plank.', 'Hoàn thành buổi tập', 'Hội viên hoàn thành buổi tập theo hướng dẫn của huấn luyện viên.', '2026-11-05 04:52:16'),
(7, 2, 6, NULL, '2026-11-05', 'Chạy bộ, đạp xe và tập Cardio.', 'Hoàn thành buổi tập', 'Hội viên hoàn thành buổi tập theo hướng dẫn của huấn luyện viên.', '2026-11-05 04:52:16'),
(8, 2, 7, NULL, '2026-11-05', 'Chạy bộ, đạp xe và tập Cardio.', 'Hoàn thành buổi tập', 'Hội viên hoàn thành buổi tập theo hướng dẫn của huấn luyện viên.', '2026-11-05 04:52:16'),
(9, 2, 8, NULL, '2026-11-05', 'Chạy bộ, đạp xe và tập Cardio.', 'Hoàn thành buổi tập', 'Hội viên hoàn thành buổi tập theo hướng dẫn của huấn luyện viên.', '2026-11-05 04:52:16'),
(10, 2, 9, NULL, '2026-11-05', 'Chạy bộ, đạp xe và tập Cardio.', 'Hoàn thành buổi tập', 'Hội viên hoàn thành buổi tập theo hướng dẫn của huấn luyện viên.', '2026-11-05 04:52:16'),
(11, 2, 10, NULL, '2026-11-05', 'Chạy bộ, đạp xe và tập Cardio.', 'Hoàn thành buổi tập', 'Hội viên hoàn thành buổi tập theo hướng dẫn của huấn luyện viên.', '2026-11-05 04:52:16'),
(12, 3, 11, NULL, '2026-11-05', 'Luyện đấm thẳng, đấm móc và nhảy dây.', 'Hoàn thành buổi tập', 'Hội viên hoàn thành buổi tập theo hướng dẫn của huấn luyện viên.', '2026-11-05 04:52:16'),
(13, 3, 12, NULL, '2026-11-05', 'Luyện đấm thẳng, đấm móc và nhảy dây.', 'Hoàn thành buổi tập', 'Hội viên hoàn thành buổi tập theo hướng dẫn của huấn luyện viên.', '2026-11-05 04:52:16'),
(14, 3, 13, NULL, '2026-11-05', 'Luyện đấm thẳng, đấm móc và nhảy dây.', 'Hoàn thành buổi tập', 'Hội viên hoàn thành buổi tập theo hướng dẫn của huấn luyện viên.', '2026-11-05 04:52:16'),
(15, 3, 14, NULL, '2026-11-05', 'Luyện đấm thẳng, đấm móc và nhảy dây.', 'Hoàn thành buổi tập', 'Hội viên hoàn thành buổi tập theo hướng dẫn của huấn luyện viên.', '2026-11-05 04:52:16'),
(16, 3, 15, NULL, '2026-11-05', 'Luyện đấm thẳng, đấm móc và nhảy dây.', 'Hoàn thành buổi tập', 'Hội viên hoàn thành buổi tập theo hướng dẫn của huấn luyện viên.', '2026-11-05 04:52:16'),
(17, 4, 16, NULL, '2026-11-05', 'Tập Yoga, tư thế cái cây và Plank.', 'Hoàn thành buổi tập', 'Hội viên hoàn thành buổi tập theo hướng dẫn của huấn luyện viên.', '2026-11-05 04:52:16'),
(18, 4, 17, NULL, '2026-11-05', 'Tập Yoga, tư thế cái cây và Plank.', 'Hoàn thành buổi tập', 'Hội viên hoàn thành buổi tập theo hướng dẫn của huấn luyện viên.', '2026-11-05 04:52:16'),
(19, 4, 18, NULL, '2026-11-05', 'Tập Yoga, tư thế cái cây và Plank.', 'Hoàn thành buổi tập', 'Hội viên hoàn thành buổi tập theo hướng dẫn của huấn luyện viên.', '2026-11-05 04:52:16'),
(20, 4, 19, NULL, '2026-11-05', 'Tập Yoga, tư thế cái cây và Plank.', 'Hoàn thành buổi tập', 'Hội viên hoàn thành buổi tập theo hướng dẫn của huấn luyện viên.', '2026-11-05 04:52:16'),
(21, 5, 20, NULL, '2026-11-05', 'Tập Deadlift, Shoulder Press và Bench Press.', 'Hoàn thành buổi tập', 'Hội viên hoàn thành buổi tập theo hướng dẫn của huấn luyện viên.', '2026-11-05 04:52:16'),
(22, 5, 21, NULL, '2026-11-05', 'Tập Deadlift, Shoulder Press và Bench Press.', 'Hoàn thành buổi tập', 'Hội viên hoàn thành buổi tập theo hướng dẫn của huấn luyện viên.', '2026-11-05 04:52:16'),
(23, 5, 22, NULL, '2026-11-05', 'Tập Deadlift, Shoulder Press và Bench Press.', 'Hoàn thành buổi tập', 'Hội viên hoàn thành buổi tập theo hướng dẫn của huấn luyện viên.', '2026-11-05 04:52:16'),
(24, 5, 23, NULL, '2026-11-05', 'Tập Deadlift, Shoulder Press và Bench Press.', 'Hoàn thành buổi tập', 'Hội viên hoàn thành buổi tập theo hướng dẫn của huấn luyện viên.', '2026-11-05 04:52:16'),
(25, 6, 24, NULL, '2026-11-05', 'Tập Burpees, Jumping Jacks và chạy bộ.', 'Hoàn thành buổi tập', 'Hội viên hoàn thành buổi tập theo hướng dẫn của huấn luyện viên.', '2026-11-05 04:52:16'),
(26, 6, 25, NULL, '2026-11-05', 'Tập Burpees, Jumping Jacks và chạy bộ.', 'Hoàn thành buổi tập', 'Hội viên hoàn thành buổi tập theo hướng dẫn của huấn luyện viên.', '2026-11-05 04:52:16'),
(27, 6, 26, NULL, '2026-11-05', 'Tập Burpees, Jumping Jacks và chạy bộ.', 'Hoàn thành buổi tập', 'Hội viên hoàn thành buổi tập theo hướng dẫn của huấn luyện viên.', '2026-11-05 04:52:16'),
(28, 6, 27, NULL, '2026-11-05', 'Tập Burpees, Jumping Jacks và chạy bộ.', 'Hoàn thành buổi tập', 'Hội viên hoàn thành buổi tập theo hướng dẫn của huấn luyện viên.', '2026-11-05 04:52:16');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('truongtrithien2008@gmail.com', '$2y$12$xgdfGVc9yIwbJFb/hDeWzevs9fxQ2k7fYJgS42KwCd5lCjpOTX1dC', '2026-10-25 20:51:26');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\NguoiDung', 1, 'gym-api', '208e5e531749994bf248ada05953d117a9b8de6f035a3ce3bdcc2fa3c713dafd', '[\"*\"]', NULL, NULL, '2026-10-12 22:24:34', '2026-10-12 22:24:34'),
(2, 'App\\Models\\NguoiDung', 1, 'gym-api', '5e1af2db9d502f2d5ae10eb5253c8f30d9068e8e3c676665f08ac407c21bc82a', '[\"*\"]', NULL, NULL, '2026-10-12 22:33:36', '2026-10-12 22:33:36'),
(3, 'App\\Models\\NguoiDung', 1, 'gym-api', '21c3aa2cca371d84cee5fc4cd832648637cf74f4bfe03784712489344e27d499', '[\"*\"]', '2026-10-12 22:47:33', NULL, '2026-10-12 22:47:17', '2026-10-12 22:47:33'),
(5, 'App\\Models\\NguoiDung', 1, 'gym-api', '1db3d978eab0a1405e553b3f887dd343d1f08f5d042fae43547aa6dd5b8c1ac5', '[\"*\"]', '2026-10-12 22:52:18', NULL, '2026-10-12 22:52:08', '2026-10-12 22:52:18');

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
('2RWXr3ap1B9qbkKKMMlK7nYQAWDgpTCSbtqR9mTY', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiRVFmTnhTc09jVEowRDBLbTJGUXpUTGRycU1oaTMzdDU2Qm84dURqMCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC90cmFpbmVyL3BsYW5zIjtzOjU6InJvdXRlIjtOO31zOjQ6InVzZXIiO086ODoic3RkQ2xhc3MiOjU6e3M6MTM6Im5ndW9pX2R1bmdfaWQiO2k6MTM7czoxMDoidmFpX3Ryb19pZCI7aTo0O3M6NjoiaG9fdGVuIjtzOjE2OiJMw6ogUXXhu5FjIELhuqNvIjtzOjU6ImVtYWlsIjtzOjExOiJiYW9AZ3ltLmNvbSI7czoxMDoidHJhbmdfdGhhaSI7czo5OiJob2F0X2RvbmciO31zOjc6InJvbGVfaWQiO2k6NDtzOjE0OiJkYXNoYm9hcmRfcGF0aCI7czoxNzoidHJhaW5lci9kYXNoYm9hcmQiO30=', 1793855471),
('5xrBnJ5eD06AilStjeQ0XCAW2ecGeXlzENcojmEO', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWWVMUnR4ZklvaUdFRjVZb2hoOFBqVVo4VWRoNm1TMk55Zk4xOWV6UyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1793848672),
('6gPykSiY2dn7HTfKq8eDNV4qicRrWJC9EjxGrlhj', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVnNEaGpGT3NUN1pMVFREUkR3NlFSbDZ6YlNrU3I5UncyVFpHTG1XRSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1793848851),
('Gb2nPgWv87CyMAwmhBDhgH3Equv2eaeTQbprLtA8', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiT1VGOWJxM1ZDWkZMcW5OV1l6YTBxTkxmNXpSVWtoQUg5dnVJcjBSZyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1793854591),
('HxkDCvrXwIQMZiapMlMqQRscZWYzo5t9JCMDNbCl', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUkx6aVppeWszMGZIcEJZcHlNRUNNZkMwdDFxYzVWbFljV1BNalJ6YyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1793854418),
('HXky8qlYbCA8eOksYK1mT6rxHwYMTGoqjcySucSz', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVFpXNEEyV0I0N1RRakRtZmxvQTEyQXRxandoUUlqOVY0ZExuc3BaTiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1793854514),
('IKq0yBHxeTaEKrL50fhWZOXWrQhHYqJk9MSlGmFD', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVHk2VVFaSDU5ZmpnNW8zdFM1dUx3cFROVTU0d0cybGhXNnFvU3JxSiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1793848888),
('mg4cxpyNQutbIEPtGryhyvScWfivTgghk5lnHCkD', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYUpKS3hVTGhtYTZzUHZvWkRrdERNV3ZIcFpnVW5lVW9RWEg0b2hGNiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1793854344),
('mQYAA0v2xD9VHcfDoCHPgmf7NmwUAYRTlzQkfyqt', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNTZhVU9hY2taa2c3SzVIVmF2U2laNFBBZjBpMmJNNzd5Zm43T0MxYiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1793849793),
('Ot2jqBxAnRjyNRaSgJAHgvVd5J1hnTTIczWM7qdv', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYUhlejZvRzdpMk1XODhqUk1TS1NSNGxmMTNIOG00amdPbnNoa0RpRSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1793854968),
('pLNpIFKAjTcUm1tgg5KHoFqbUM3B8ZQejO3D7fGi', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidWtuT3BkdTlZQWpsTlpFSUlETnR5SDk3NVlzaUV2dWpvRTZRVm9ZYyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1793855139),
('Qu2eKtbgkHTWHynKGuEXXTYtsnr3XRzsug9WAswI', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVmtMeUI1OWhUc3ZoUjU2TzM3cnFVcFhvUjc4emFHaUJ4OVg0cnFsMCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1793855091),
('rOPwdnDffIxioKaKwodIaBG7XEYiOKPrqUop8GXK', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTTB3NTJIUWpCVThnS1AzWW15NlRvenVlaE1VTzRmdFI4OEhLM3o3RSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1793854727),
('tbivXrC5S3SgycZLpkIUU0KiVVRFsNuJDLWiLgJa', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSWJWcUZZbE9LNnNpZlV5bnlGeEMyTXUwWVVKMEcyWWRpQ0gwY3l5cCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1793849041),
('uU3VPN4pdOLHVmAN4pbCdoKAWC9g2CSDP2N1YfpF', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidnNnQmFhWUdaSkt3alRrYnp2WjNQNXhSQmljNzZ4ZmF1Y3NOeVY5NiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1793850722),
('ViVxw4RM0xcGBf0ZWxG3IdEMt2h1GUgBdH6aQ2uv', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTWVDYVN3cmZKc1BVVzMyc2loZG4wcFlaZDhFMzF1VTVzWk1DRktZTSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1793854372),
('wKNnql3BimNlx2KsMjv9sdKC2KzoHEwoMOM6TDW4', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRmlnZnFVU2tsckxITUdubGFZcERoNXJvOXp4aTFnZ29BdHNmR1p6RCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1793855225),
('WOahtUnahC7xLkmqwttKN0QRMmz3ctWo7dbiiH4l', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiM2dOakRuZG1ORzllZ3BQQWpFblYybG5PMzY2aVJuSzBPWnZmQ3JYbSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1793855046),
('wojQTcRnlHvA53B5sXHS53xB6n1jIvNl2K5byIvb', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQm55ZDU0dDNLa2VVUHdydk8ybDU5MGYwNFZwTXdxY2kxV055bWVuRiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1793854956),
('wOUNLRJyopscFb4TQHTqjgHRffS8kG0UHznkWg6z', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTUhkdmpJd0NoSkNySFpkTUhLZnhhS1dnWVcybzhXUlZSODFDVDgzayI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1793848913),
('Xgw9QKfn8UeMcD62Eg56B1JRiOEYx6ERm1TZGbte', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUVdrVmpacVZIV3pPdkZjeGl4VFJTbUdzZDdIcUR5dDhGWHFiTVM4dyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1793848976),
('zaVaL9RCZpy2Ck5pwKJrAXcjSXIIcMtdHuHLR31x', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVERYTU5nZE5NSVo3VW9TbEE4Snp4SE9VYUNFNWVLOXg0TGI1djAzcyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1793855285),
('zJrgbSlwqjMgZFuPlQ5DKWmN3vbrZR3OiQdNWfYB', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiV01yVGJKWDcxVWlEOFZJNGREWUVXbmNJZlVZRkU5cVhvNmRKZ1dyeSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1793854504);

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
(2, 2, 'Thành viên mới', 'Có thành viên mới vừa đăng ký.', 1, '2026-09-29 08:30:00'),
(3, 3, 'Thanh toán', 'Có hóa đơn mới đã được thanh toán.', 1, '2026-09-29 09:00:00'),
(4, 4, 'Lịch PT', 'Có lịch PT mới được tạo.', 0, '2026-09-29 09:30:00'),
(5, 20, 'Đăng ký gói PT thành công', 'Bạn đã đăng ký thành công gói PT Cơ Bản 10 Buổi. Thời hạn: 30 ngày, 10 buổi.', 0, '2026-10-01 11:17:45'),
(6, 20, 'Đăng ký gói PT thành công', 'Bạn đã đăng ký thành công gói PT Cá Nhân 5 Buổi. Thời hạn: 15 ngày, 5 buổi.', 0, '2026-10-01 11:18:14'),
(7, 22, 'Đăng ký gói PT thành công', 'Bạn đã đăng ký thành công gói PT Cơ Bản 10 Buổi. Thời hạn: 30 ngày, 10 buổi.', 0, '2026-10-01 11:20:02'),
(8, 22, 'Đăng ký gói PT thành công', 'Bạn đã đăng ký thành công gói PT Cá Nhân 5 Buổi. Thời hạn: 15 ngày, 5 buổi.', 0, '2026-10-01 11:21:49'),
(9, 22, 'Đăng ký gói PT thành công', 'Bạn đã đăng ký thành công gói PT Premium 30 Buổi. Có 30 buổi, thời hạn 90 ngày.', 0, '2026-10-01 11:24:20'),
(10, 22, 'Đăng ký gói PT thành công', 'Bạn đã đăng ký thành công gói PT Tiêu Chuẩn 20 Buổi. Có 20 buổi, thời hạn 60 ngày.', 0, '2026-10-01 11:24:27'),
(11, 22, 'Đặt lịch PT thành công', 'Bạn đã đặt lịch cho gói PT Tiêu Chuẩn 20 Buổi từ 02/10/2026 19:44 đến 20:45.', 0, '2026-10-01 12:45:13'),
(12, 22, 'Hủy lịch PT', 'Bạn đã hủy lịch PT vào ngày 02/10/2026 19:44.', 0, '2026-10-01 13:03:16'),
(13, 5, 'Buổi PT đã hoàn thành', 'Buổi tập PT ngày 29/09/2026 07:00 đã được xác nhận hoàn thành. Số buổi PT còn lại đã giảm 1 buổi.', 1, '2026-10-01 13:30:43'),
(14, 22, 'Check-in thành công', 'Bạn đã check-in Gym lúc 13:40 01/10/2026.', 0, '2026-10-01 13:40:22'),
(15, 23, 'Đăng ký gói tập thành công', 'Bạn đã đăng ký gói \"Gói Sinh Viên\". Hóa đơn đang chờ thanh toán.', 1, '2026-10-01 13:54:55'),
(16, 23, 'Thanh toán thành công', 'Hóa đơn #HD00012 đã được xác nhận thanh toán bằng Tiền mặt.', 1, '2026-10-01 14:05:15'),
(17, 20, 'Hủy đăng ký lớp', 'Bạn đã hủy đăng ký lớp \"Yoga Cơ Bản\".', 0, '2026-10-02 03:29:28'),
(18, 20, 'Hủy đăng ký lớp', 'Bạn đã hủy đăng ký lớp \"Gym Tăng Cơ\".', 0, '2026-10-02 03:30:36'),
(19, 20, 'Đăng ký lớp thành công', 'Bạn đã đăng ký lớp \"Yoga Cơ Bản\" thành công.', 0, '2026-10-02 03:34:58'),
(20, 20, 'Đăng ký lớp thành công', 'Bạn đã đăng ký lớp \"Gym Tăng Cơ\" thành công.', 0, '2026-10-02 03:35:14'),
(21, 20, 'Hủy đăng ký lớp', 'Bạn đã hủy đăng ký lớp \"Yoga Cơ Bản\".', 0, '2026-10-02 03:41:07'),
(22, 20, 'Hủy đăng ký lớp', 'Bạn đã hủy đăng ký lớp \"Cardio Đốt Mỡ\".', 0, '2026-10-02 03:41:11'),
(23, 20, 'Hủy đăng ký lớp', 'Bạn đã hủy đăng ký lớp \"Gym Tăng Cơ\".', 0, '2026-10-02 03:41:13'),
(24, 20, 'Hủy đăng ký lớp', 'Bạn đã hủy đăng ký lớp \"Fitness Toàn Thân\".', 0, '2026-10-02 03:41:17'),
(25, 20, 'Hủy đăng ký lớp', 'Bạn đã hủy đăng ký lớp \"Strength\".', 0, '2026-10-02 03:41:20'),
(26, 20, 'Hủy đăng ký lớp', 'Bạn đã hủy đăng ký lớp \"Boxing\".', 0, '2026-10-02 03:41:24'),
(27, 20, 'Đăng ký lớp thành công', 'Bạn đã đăng ký lớp \"Yoga Cơ Bản\" thành công.', 0, '2026-10-02 03:41:31'),
(28, 20, 'Hủy đăng ký lớp', 'Bạn đã hủy đăng ký lớp \"Yoga Cơ Bản\".', 0, '2026-10-02 03:42:18'),
(29, 20, 'Đăng ký lớp thành công', 'Bạn đã đăng ký lớp \"Yoga Cơ Bản\" thành công.', 0, '2026-10-02 03:42:54'),
(30, 20, 'Đăng ký lớp thành công', 'Bạn đã đăng ký lớp \"Cardio Đốt Mỡ\" thành công.', 0, '2026-10-02 03:43:19'),
(31, 20, 'Đăng ký lớp thành công', 'Bạn đã đăng ký lớp \"Gym Tăng Cơ\" thành công.', 0, '2026-10-02 03:43:21'),
(32, 20, 'Đăng ký lớp thành công', 'Bạn đã đăng ký lớp \"Strength\" thành công.', 0, '2026-10-02 03:43:24'),
(33, 20, 'Đăng ký lớp thành công', 'Bạn đã đăng ký lớp \"Boxing\" thành công.', 0, '2026-10-02 03:43:27'),
(34, 20, 'Đăng ký lớp thành công', 'Bạn đã đăng ký lớp \"Fitness Toàn Thân\" thành công.', 0, '2026-10-02 03:43:29'),
(35, 23, 'Đăng ký lớp thành công', 'Bạn đã đăng ký lớp \"Strength\" thành công.', 1, '2026-10-02 08:46:15'),
(36, 23, 'Đăng ký lớp thành công', 'Bạn đã đăng ký lớp \"Cardio Đốt Mỡ\" thành công.', 1, '2026-10-02 08:46:19'),
(37, 23, 'Đăng ký lớp thành công', 'Bạn đã đăng ký lớp \"Gym Tăng Cơ\" thành công.', 1, '2026-10-02 08:46:21'),
(38, 23, 'Đăng ký lớp thành công', 'Bạn đã đăng ký lớp \"Yoga Cơ Bản\" thành công.', 1, '2026-10-02 08:46:23'),
(39, 23, 'Đăng ký lớp thành công', 'Bạn đã đăng ký lớp \"Fitness Toàn Thân\" thành công.', 1, '2026-10-02 08:46:29'),
(40, 23, 'Hủy đăng ký lớp', 'Bạn đã hủy đăng ký lớp \"Cardio Đốt Mỡ\".', 1, '2026-10-02 08:46:35'),
(41, 19, 'Đăng ký gói tập thành công', 'Bạn đã đăng ký gói \"Gói Premium 6 Tháng\". Hóa đơn đang chờ thanh toán.', 1, '2026-10-03 08:05:39'),
(42, 19, 'Đăng ký gói PT thành công', 'Bạn đã đăng ký thành công gói PT Premium 30 Buổi. Có 30 buổi, thời hạn 90 ngày.', 1, '2026-10-03 08:06:00'),
(43, 19, 'Đăng ký lớp thành công', 'Bạn đã đăng ký lớp \"Gym Tăng Cơ\" thành công.', 1, '2026-10-03 08:06:04'),
(44, 19, 'Đăng ký lớp thành công', 'Bạn đã đăng ký lớp \"Cardio Đốt Mỡ\" thành công.', 1, '2026-10-03 08:06:10'),
(45, 19, 'Hủy đăng ký lớp', 'Bạn đã hủy đăng ký lớp \"Cardio Đốt Mỡ\".', 1, '2026-10-03 08:06:17'),
(46, 19, 'Hủy đăng ký lớp', 'Bạn đã hủy đăng ký lớp \"Gym Tăng Cơ\".', 1, '2026-10-03 08:06:20'),
(47, 7, 'Buổi PT đã hoàn thành', 'Buổi tập PT ngày 29/09/2026 15:00 đã được xác nhận hoàn thành. Số buổi PT còn lại đã giảm 1 buổi.', 0, '2026-10-03 08:13:11'),
(48, 19, 'Hủy gói tập thành công', 'Bạn đã hủy gói \"Gói Premium 6 Tháng\". Hóa đơn liên quan đã được xóa.', 1, '2026-10-03 08:16:14'),
(49, 19, 'Đăng ký gói tập thành công', 'Bạn đã đăng ký gói \"Gói Sinh Viên\". Hóa đơn đang chờ thanh toán.', 1, '2026-10-03 08:33:52'),
(50, 19, 'Đăng ký gói PT thành công', 'Bạn đã đăng ký thành công gói PT Cá Nhân 5 Buổi. Có 5 buổi, thời hạn 15 ngày.', 1, '2026-10-03 08:33:58'),
(51, 19, 'Đặt lịch PT thành công', 'Bạn đã đặt lịch cho gói PT Cá Nhân 5 Buổi từ 03/10/2026 15:34 đến 20:34.', 1, '2026-10-03 08:34:39'),
(52, 19, 'Nhắc nhở thanh toán', 'Hóa đơn #HD00014 trị giá 200.000 VNĐ đang chờ thanh toán. Thời gian còn lại: 71.977147240833 giờ.', 1, '2026-10-03 08:35:14'),
(53, 19, 'Thanh toán thành công', 'Hóa đơn #HD00014 đã được xác nhận thanh toán bằng Tiền mặt.', 1, '2026-10-03 08:42:34'),
(54, 19, 'Đăng ký lớp thành công', 'Bạn đã đăng ký lớp \"Gym Tăng Cơ\" thành công.', 1, '2026-10-04 11:26:47'),
(55, 5, 'Đăng ký gói tập thành công', 'Bạn đã đăng ký gói \"Gói Tập Thử\". Hóa đơn đang chờ thanh toán.', 1, '2026-10-04 12:07:23'),
(56, 5, 'Nhắc nhở thanh toán', 'Hóa đơn #HD00015 trị giá 100.000 VNĐ đang chờ thanh toán. Thời gian còn lại: 71.998547446667 giờ.', 1, '2026-10-04 12:07:28'),
(57, 19, 'Hủy đăng ký lớp', 'Bạn đã hủy đăng ký lớp \"Gym Tăng Cơ\".', 1, '2026-10-11 12:04:30'),
(58, 19, 'Đăng ký gói PT thành công', 'Bạn đã đăng ký thành công gói PT Cơ Bản 10 Buổi. Có 10 buổi, thời hạn 30 ngày.', 1, '2026-10-11 12:56:50'),
(59, 19, 'Đăng ký gói PT thành công', 'Bạn đã đăng ký thành công gói PT Premium 30 Buổi. Có 30 buổi, thời hạn 90 ngày.', 1, '2026-10-11 12:57:08'),
(60, 19, 'Đăng ký gói PT thành công', 'Bạn đã đăng ký thành công gói PT Tiêu Chuẩn 20 Buổi. Có 20 buổi, thời hạn 60 ngày.', 1, '2026-10-11 12:57:23'),
(61, 5, 'Thanh toán thành công', 'Hóa đơn #HD00015 đã được xác nhận thanh toán bằng Tiền mặt.', 0, '2026-10-11 13:11:36'),
(62, 5, 'Buổi PT đã hoàn thành', 'Buổi tập PT ngày 01/10/2026 07:00 đã được xác nhận hoàn thành. Số buổi PT còn lại đã giảm 1 buổi.', 0, '2026-10-16 02:14:49'),
(63, 19, 'Đăng ký gói PT thành công', 'Đã tạo hóa đơn #HD00016 cho gói PT PT Cá Nhân 5 Buổi. Vui lòng liên hệ nhân viên để thanh toán trong 72 giờ.', 1, '2026-10-22 04:23:40'),
(64, 19, 'Nhắc nhở thanh toán', 'Hóa đơn #HD00016 trị giá 800.000 VNĐ đang chờ thanh toán. Thời gian còn lại: 71.9999071775 giờ.', 1, '2026-10-22 04:23:40'),
(65, 19, 'Hủy gói PT thành công', 'Bạn đã hủy đăng ký gói PT PT Cá Nhân 5 Buổi.', 1, '2026-10-22 04:36:09'),
(66, 19, 'Đăng ký gói PT thành công', 'Đã tạo hóa đơn #HD00017 cho gói PT PT Cơ Bản 10 Buổi. Vui lòng liên hệ nhân viên để thanh toán trong 72 giờ.', 1, '2026-10-22 04:36:27'),
(67, 19, 'Nhắc nhở thanh toán', 'Hóa đơn #HD00017 trị giá 1.500.000 VNĐ đang chờ thanh toán. Thời gian còn lại: 71.996977541944 giờ.', 1, '2026-10-22 04:36:37'),
(68, 19, 'Hủy gói PT thành công', 'Bạn đã hủy đăng ký gói PT PT Cơ Bản 10 Buổi.', 1, '2026-10-22 05:30:33'),
(69, 19, 'Đăng ký gói PT thành công', 'Đã tạo hóa đơn #HD00018 cho gói PT PT Giảm Mỡ 12 Buổi. Vui lòng liên hệ nhân viên để thanh toán trong 72 giờ.', 1, '2026-10-22 05:30:39'),
(70, 19, 'Đăng ký gói PT thành công', 'Đã tạo hóa đơn #HD00019 cho gói PT PT Cá Nhân 5 Buổi. Vui lòng liên hệ nhân viên để thanh toán trong 72 giờ.', 1, '2026-10-22 05:30:44'),
(71, 19, 'Hủy gói PT thành công', 'Bạn đã hủy đăng ký gói PT PT Cá Nhân 5 Buổi.', 1, '2026-10-22 05:39:03'),
(72, 19, 'Hủy gói PT thành công', 'Bạn đã hủy đăng ký gói PT PT Giảm Mỡ 12 Buổi.', 1, '2026-10-22 05:39:16'),
(73, 19, 'Đăng ký gói PT thành công', 'Đã tạo hóa đơn #HD00020 cho gói PT PT Giảm Mỡ 12 Buổi. Vui lòng liên hệ nhân viên để thanh toán trong 72 giờ.', 1, '2026-10-22 05:43:44'),
(74, 19, 'Hủy gói PT thành công', 'Bạn đã hủy đăng ký gói PT PT Giảm Mỡ 12 Buổi.', 1, '2026-10-22 05:43:59'),
(75, 19, 'Đăng ký gói PT thành công', 'Đã tạo hóa đơn #HD00021 cho gói PT PT Cơ Bản 10 Buổi. Vui lòng liên hệ nhân viên để thanh toán trong 72 giờ.', 1, '2026-10-22 05:47:55'),
(76, 19, 'Nhắc nhở thanh toán', 'Hóa đơn #HD00021 trị giá 1.500.000 VNĐ đang chờ thanh toán. Thời gian còn lại: 71.998660575278 giờ.', 1, '2026-10-22 05:47:59'),
(77, 19, 'Thanh toán thành công', 'Hóa đơn #HD00021 đã được xác nhận thanh toán bằng Tiền mặt.', 1, '2026-10-22 05:50:17'),
(78, 16, 'Đăng ký gói PT thành công', 'Đã tạo hóa đơn #HD00022 cho gói PT PT Giảm Mỡ 12 Buổi. Vui lòng liên hệ nhân viên để thanh toán trong 72 giờ.', 1, '2026-10-22 06:04:07'),
(79, 16, 'Nhắc nhở thanh toán', 'Hóa đơn #HD00022 trị giá 1.800.000 VNĐ đang chờ thanh toán. Thời gian còn lại: 71.998310445 giờ.', 1, '2026-10-22 06:04:13'),
(80, 19, 'Đăng ký lớp thành công', 'Bạn đã đăng ký lớp \"Yoga Cơ Bản\" thành công.', 1, '2026-10-24 04:44:18'),
(81, 19, 'Đăng ký lớp thành công', 'Bạn đã đăng ký lớp \"Cardio Đốt Mỡ\" thành công.', 1, '2026-10-24 04:44:20'),
(82, 19, 'Đăng ký lớp thành công', 'Bạn đã đăng ký lớp \"Gym Tăng Cơ\" thành công.', 1, '2026-10-24 04:44:22'),
(83, 19, 'Đăng ký lớp thành công', 'Bạn đã đăng ký lớp \"Fitness Toàn Thân\" thành công.', 1, '2026-10-24 04:44:27'),
(84, 19, 'Đăng ký lớp thành công', 'Bạn đã đăng ký lớp \"Strength\" thành công.', 1, '2026-10-24 04:44:29'),
(85, 19, 'Hủy đăng ký lớp', 'Bạn đã hủy đăng ký lớp \"Yoga Cơ Bản\".', 1, '2026-10-24 04:44:39'),
(86, 19, 'Hủy đăng ký lớp', 'Bạn đã hủy đăng ký lớp \"Strength\".', 1, '2026-10-24 04:44:47'),
(87, 19, 'Hủy đăng ký lớp', 'Bạn đã hủy đăng ký lớp \"Fitness Toàn Thân\".', 1, '2026-10-24 04:44:56'),
(88, 19, 'Hủy đăng ký lớp', 'Bạn đã hủy đăng ký lớp \"Gym Tăng Cơ\".', 1, '2026-10-24 04:45:02'),
(89, 19, 'Hủy đăng ký lớp', 'Bạn đã hủy đăng ký lớp \"Cardio Đốt Mỡ\".', 1, '2026-10-24 04:45:05'),
(90, 23, 'Hủy đăng ký lớp', 'Bạn đã hủy đăng ký lớp \"Fitness Toàn Thân\".', 1, '2026-10-24 06:14:25'),
(91, 23, 'Hủy đăng ký lớp', 'Bạn đã hủy đăng ký lớp \"Yoga Cơ Bản\".', 1, '2026-10-24 06:14:31'),
(92, 23, 'Hủy đăng ký lớp', 'Bạn đã hủy đăng ký lớp \"Gym Tăng Cơ\".', 1, '2026-10-24 06:14:44'),
(93, 23, 'Đăng ký lớp thành công', 'Bạn đã đăng ký lớp \"Yoga Cơ Bản\" thành công.', 1, '2026-10-24 06:14:50'),
(94, 23, 'Đăng ký gói PT thành công', 'Đã tạo hóa đơn #HD00023 cho gói PT PT Cá Nhân 5 Buổi. Vui lòng liên hệ nhân viên để thanh toán trong 72 giờ.', 1, '2026-10-24 06:19:01'),
(95, 23, 'Hủy gói PT thành công', 'Bạn đã hủy đăng ký gói PT PT Cá Nhân 5 Buổi.', 1, '2026-10-24 06:19:41'),
(96, 23, 'Đăng ký gói PT thành công', 'Đã tạo hóa đơn #HD00024 cho gói PT PT Cá Nhân 5 Buổi. Vui lòng liên hệ nhân viên để thanh toán trong 72 giờ.', 1, '2026-10-24 06:19:50'),
(97, 23, 'Hủy gói PT thành công', 'Bạn đã hủy đăng ký gói PT PT Cá Nhân 5 Buổi.', 1, '2026-10-24 06:20:19'),
(98, 23, 'Đăng ký gói PT thành công', 'Đã tạo hóa đơn #HD00025 cho gói PT PT Cơ Bản 10 Buổi. Vui lòng liên hệ nhân viên để thanh toán trong 72 giờ.', 1, '2026-10-24 06:20:59'),
(99, 23, 'Nhắc nhở thanh toán', 'Hóa đơn #HD00025 trị giá 1.500.000 VNĐ đang chờ thanh toán. Thời gian còn lại: 71.853698861111 giờ.', 1, '2026-10-24 06:29:45'),
(100, 23, 'Hủy gói PT thành công', 'Bạn đã hủy đăng ký gói PT PT Cơ Bản 10 Buổi.', 1, '2026-10-24 06:40:18'),
(101, 23, 'Đăng ký gói PT thành công', 'Đã tạo hóa đơn #HD00026 cho gói PT PT Cơ Bản 10 Buổi. Vui lòng liên hệ nhân viên để thanh toán trong 72 giờ.', 1, '2026-10-24 06:40:27'),
(102, 23, 'Hủy gói PT thành công', 'Bạn đã hủy đăng ký gói PT PT Cơ Bản 10 Buổi.', 1, '2026-10-24 06:40:34'),
(103, 23, 'Đăng ký gói PT thành công', 'Đã tạo hóa đơn #HD00027 cho gói PT PT Giảm Mỡ 12 Buổi. Vui lòng liên hệ nhân viên để thanh toán trong 72 giờ.', 1, '2026-10-24 06:40:42'),
(104, 23, 'Hủy gói PT thành công', 'Bạn đã hủy đăng ký gói PT PT Giảm Mỡ 12 Buổi.', 1, '2026-10-24 06:59:49'),
(105, 23, 'Đăng ký gói PT thành công', 'Đã tạo hóa đơn #HD00028 cho gói PT PT Cơ Bản 10 Buổi. Vui lòng liên hệ nhân viên để thanh toán trong 72 giờ.', 1, '2026-10-24 06:59:58'),
(106, 23, 'Hủy gói PT thành công', 'Bạn đã hủy đăng ký gói PT PT Cơ Bản 10 Buổi.', 1, '2026-10-24 07:12:40'),
(107, 23, 'Đăng ký gói PT thành công', 'Đã tạo hóa đơn #HD00029 cho gói PT PT Cá Nhân 5 Buổi. Vui lòng liên hệ nhân viên để thanh toán trong 72 giờ.', 1, '2026-10-24 07:12:47'),
(108, 23, 'Hủy gói PT thành công', 'Bạn đã hủy đăng ký gói PT PT Cá Nhân 5 Buổi.', 1, '2026-10-24 07:13:31'),
(109, 23, 'Đăng ký gói PT thành công', 'Đã tạo hóa đơn #HD00030 cho gói PT PT Cơ Bản 10 Buổi. Vui lòng liên hệ nhân viên để thanh toán trong 72 giờ.', 1, '2026-10-26 05:38:44'),
(110, 23, 'Nhắc nhở thanh toán', 'Hóa đơn #HD00030 trị giá 1.500.000 VNĐ đang chờ thanh toán. Thời gian còn lại: 71.998703874167 giờ.', 1, '2026-10-26 05:38:48'),
(111, 23, 'Nhắc nhở thanh toán', 'Hóa đơn #HD00030 trị giá 1.500.000 VNĐ đang chờ thanh toán. Thời gian còn lại: đã quá hạn 72 giờ.', 1, '2026-10-31 05:15:10'),
(112, 23, 'Gói PT đã tự động hủy', 'Gói \"PT Cơ Bản 10 Buổi\" đã được tự động hủy vì quá 72 giờ chưa thanh toán.', 1, '2026-10-31 05:46:39'),
(113, 23, 'Hủy đăng ký lớp', 'Bạn đã hủy đăng ký lớp \"Yoga Cơ Bản\".', 1, '2026-10-31 05:49:00'),
(114, 23, 'Đăng ký lớp thành công', 'Bạn đã đăng ký lớp \"Yoga Cơ Bản\" thành công.', 1, '2026-10-31 05:49:02'),
(115, 23, 'Đăng ký gói PT thành công', 'Đã tạo hóa đơn #HD00031 cho gói PT PT Cá Nhân 5 Buổi. Vui lòng liên hệ nhân viên để thanh toán trong 72 giờ.', 1, '2026-10-31 05:51:28'),
(116, 23, 'Hủy gói PT thành công', 'Bạn đã hủy đăng ký gói PT PT Cá Nhân 5 Buổi.', 1, '2026-10-31 06:00:53'),
(117, 23, 'Đăng ký gói PT thành công', 'Đã tạo hóa đơn #HD00032 cho gói PT PT Giảm Mỡ 12 Buổi. Vui lòng liên hệ nhân viên để thanh toán trong 72 giờ.', 1, '2026-10-31 06:08:53'),
(118, 23, 'Đăng ký lớp thành công', 'Bạn đã đăng ký lớp \"Cardio Đốt Mỡ\" thành công.', 1, '2026-10-31 06:09:10'),
(119, 23, 'Hủy gói PT thành công', 'Bạn đã hủy đăng ký gói PT PT Giảm Mỡ 12 Buổi.', 1, '2026-10-31 06:09:25'),
(120, 23, 'Hủy đăng ký lớp', 'Bạn đã hủy đăng ký lớp \"Cardio Đốt Mỡ\".', 1, '2026-10-31 06:09:34'),
(121, 23, 'Đăng ký gói PT thành công', 'Đã tạo hóa đơn #HD00033 cho gói PT PT Cơ Bản 10 Buổi. Vui lòng liên hệ nhân viên để thanh toán trong 72 giờ.', 1, '2026-10-31 06:18:38'),
(122, 23, 'Hủy gói PT thành công', 'Bạn đã hủy đăng ký gói PT PT Cơ Bản 10 Buổi.', 1, '2026-10-31 06:18:47'),
(123, 23, 'Hủy đăng ký lớp', 'Bạn đã hủy đăng ký lớp \"Strength\".', 1, '2026-10-31 06:40:46'),
(124, 23, 'Đăng ký lớp thành công', 'Bạn đã đăng ký lớp \"Gym Tăng Cơ\" thành công.', 1, '2026-10-31 06:40:57'),
(125, 19, 'Đăng ký gói tập thành công', 'Bạn đã đăng ký gói \"Gói Tập Thử\". Hóa đơn đang chờ thanh toán.', 1, '2026-11-05 03:22:16'),
(126, 19, 'Hủy gói tập thành công', 'Bạn đã hủy gói \"Gói Tập Thử\". Hóa đơn được lưu lại với trạng thái đã hủy.', 1, '2026-11-05 03:36:04'),
(127, 23, 'Đăng ký gói PT thành công', 'Đã tạo hóa đơn #HD00035 cho gói PT PT Cơ Bản 10 Buổi. Vui lòng liên hệ nhân viên để thanh toán trong 72 giờ.', 1, '2026-11-05 03:36:47'),
(128, 23, 'Hủy gói PT thành công', 'Bạn đã hủy đăng ký gói PT PT Cơ Bản 10 Buổi.', 1, '2026-11-05 03:36:51'),
(129, 23, 'Đăng ký gói PT thành công', 'Đã tạo hóa đơn #HD00036 cho gói PT PT Cá Nhân 5 Buổi. Vui lòng liên hệ nhân viên để thanh toán trong 72 giờ.', 1, '2026-11-05 03:38:01'),
(130, 23, 'Hủy gói PT thành công', 'Bạn đã hủy đăng ký gói PT PT Cá Nhân 5 Buổi.', 1, '2026-11-05 03:42:45'),
(131, 23, 'Đăng ký gói tập thành công', 'Bạn đã đăng ký gói \"Gói Tập Thử\". Hóa đơn đang chờ thanh toán.', 1, '2026-11-05 03:46:09'),
(132, 23, 'Hủy gói tập thành công', 'Bạn đã hủy gói \"Gói Tập Thử\". Hóa đơn được lưu lại với trạng thái đã hủy.', 1, '2026-11-05 03:46:15'),
(133, 23, 'Đăng ký gói PT thành công', 'Đã tạo hóa đơn #HD00038 cho gói PT PT Cơ Bản 10 Buổi. Vui lòng liên hệ nhân viên để thanh toán trong 72 giờ.', 1, '2026-11-05 03:46:39'),
(134, 23, 'Hủy gói PT thành công', 'Bạn đã hủy đăng ký gói PT PT Cơ Bản 10 Buổi.', 1, '2026-11-05 03:47:07'),
(135, 23, 'Hủy đăng ký lớp', 'Bạn đã hủy đăng ký lớp \"Gym Tăng Cơ\".', 1, '2026-11-05 03:47:15'),
(136, 23, 'Hủy đăng ký lớp', 'Bạn đã hủy đăng ký lớp \"Yoga Cơ Bản\".', 1, '2026-11-05 03:47:24'),
(137, 23, 'Đăng ký lớp thành công', 'Bạn đã đăng ký lớp \"Yoga Cơ Bản\" thành công.', 1, '2026-11-05 03:47:27'),
(138, 23, 'Đăng ký lớp thành công', 'Bạn đã đăng ký lớp \"Cardio Đốt Mỡ\" thành công.', 0, '2026-11-05 03:47:37');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tien_do_hoc_vien_pt`
--

CREATE TABLE `tien_do_hoc_vien_pt` (
  `progress_id` int(10) UNSIGNED NOT NULL,
  `pt_id` int(11) NOT NULL,
  `hoi_vien_id` int(11) NOT NULL,
  `ngay_ghi_nhan` date NOT NULL,
  `can_nang` decimal(5,2) DEFAULT NULL,
  `chieu_cao` decimal(5,2) DEFAULT NULL,
  `vong_nguc` decimal(5,2) DEFAULT NULL,
  `vong_eo` decimal(5,2) DEFAULT NULL,
  `vong_mong` decimal(5,2) DEFAULT NULL,
  `ghi_chu` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tien_do_hoc_vien_pt`
--

INSERT INTO `tien_do_hoc_vien_pt` (`progress_id`, `pt_id`, `hoi_vien_id`, `ngay_ghi_nhan`, `can_nang`, `chieu_cao`, `vong_nguc`, `vong_eo`, `vong_mong`, `ghi_chu`, `created_at`) VALUES
(1, 1, 1, '2026-10-01', 72.00, 170.00, 96.00, 88.00, 100.00, 'So lieu do dau chuong trinh.', '2026-11-05 04:08:55'),
(2, 1, 1, '2026-11-01', 70.50, 170.00, 96.00, 84.00, 99.00, 'Sau 4 tuan: can nang va vong eo giam, suc ben cai thien.', '2026-11-05 04:08:55'),
(3, 1, 1, '2026-11-05', 56.00, 161.00, 86.00, 66.00, 91.00, 'Số liệu mẫu theo dõi thể trạng hội viên.', '2026-11-05 04:52:16'),
(4, 1, 2, '2026-11-05', 57.00, 162.00, 87.00, 67.00, 92.00, 'Số liệu mẫu theo dõi thể trạng hội viên.', '2026-11-05 04:52:16'),
(5, 1, 3, '2026-11-05', 58.00, 163.00, 88.00, 68.00, 93.00, 'Số liệu mẫu theo dõi thể trạng hội viên.', '2026-11-05 04:52:16'),
(6, 1, 4, '2026-11-05', 59.00, 164.00, 89.00, 69.00, 94.00, 'Số liệu mẫu theo dõi thể trạng hội viên.', '2026-11-05 04:52:16'),
(7, 1, 5, '2026-11-05', 60.00, 165.00, 90.00, 70.00, 95.00, 'Số liệu mẫu theo dõi thể trạng hội viên.', '2026-11-05 04:52:16'),
(8, 2, 6, '2026-11-05', 61.00, 166.00, 91.00, 71.00, 96.00, 'Số liệu mẫu theo dõi thể trạng hội viên.', '2026-11-05 04:52:16'),
(9, 2, 7, '2026-11-05', 62.00, 167.00, 92.00, 72.00, 97.00, 'Số liệu mẫu theo dõi thể trạng hội viên.', '2026-11-05 04:52:16'),
(10, 2, 8, '2026-11-05', 63.00, 168.00, 93.00, 73.00, 98.00, 'Số liệu mẫu theo dõi thể trạng hội viên.', '2026-11-05 04:52:16'),
(11, 2, 9, '2026-11-05', 64.00, 169.00, 94.00, 74.00, 99.00, 'Số liệu mẫu theo dõi thể trạng hội viên.', '2026-11-05 04:52:16'),
(12, 2, 10, '2026-11-05', 65.00, 170.00, 95.00, 75.00, 100.00, 'Số liệu mẫu theo dõi thể trạng hội viên.', '2026-11-05 04:52:16'),
(13, 3, 11, '2026-11-05', 66.00, 171.00, 96.00, 76.00, 101.00, 'Số liệu mẫu theo dõi thể trạng hội viên.', '2026-11-05 04:52:16'),
(14, 3, 12, '2026-11-05', 67.00, 172.00, 97.00, 77.00, 102.00, 'Số liệu mẫu theo dõi thể trạng hội viên.', '2026-11-05 04:52:16'),
(15, 3, 13, '2026-11-05', 68.00, 173.00, 98.00, 78.00, 103.00, 'Số liệu mẫu theo dõi thể trạng hội viên.', '2026-11-05 04:52:16'),
(16, 3, 14, '2026-11-05', 69.00, 174.00, 99.00, 79.00, 104.00, 'Số liệu mẫu theo dõi thể trạng hội viên.', '2026-11-05 04:52:16'),
(17, 3, 15, '2026-11-05', 55.00, 160.00, 85.00, 65.00, 90.00, 'Số liệu mẫu theo dõi thể trạng hội viên.', '2026-11-05 04:52:16'),
(18, 4, 16, '2026-11-05', 56.00, 161.00, 86.00, 66.00, 91.00, 'Số liệu mẫu theo dõi thể trạng hội viên.', '2026-11-05 04:52:16'),
(19, 4, 17, '2026-11-05', 57.00, 162.00, 87.00, 67.00, 92.00, 'Số liệu mẫu theo dõi thể trạng hội viên.', '2026-11-05 04:52:16'),
(20, 4, 18, '2026-11-05', 58.00, 163.00, 88.00, 68.00, 93.00, 'Số liệu mẫu theo dõi thể trạng hội viên.', '2026-11-05 04:52:16'),
(21, 4, 19, '2026-11-05', 59.00, 164.00, 89.00, 69.00, 94.00, 'Số liệu mẫu theo dõi thể trạng hội viên.', '2026-11-05 04:52:16'),
(22, 5, 20, '2026-11-05', 60.00, 165.00, 90.00, 70.00, 95.00, 'Số liệu mẫu theo dõi thể trạng hội viên.', '2026-11-05 04:52:16'),
(23, 5, 21, '2026-11-05', 61.00, 166.00, 91.00, 71.00, 96.00, 'Số liệu mẫu theo dõi thể trạng hội viên.', '2026-11-05 04:52:16'),
(24, 5, 22, '2026-11-05', 62.00, 167.00, 92.00, 72.00, 97.00, 'Số liệu mẫu theo dõi thể trạng hội viên.', '2026-11-05 04:52:16'),
(25, 5, 23, '2026-11-05', 63.00, 168.00, 93.00, 73.00, 98.00, 'Số liệu mẫu theo dõi thể trạng hội viên.', '2026-11-05 04:52:16'),
(26, 6, 24, '2026-11-05', 64.00, 169.00, 94.00, 74.00, 99.00, 'Số liệu mẫu theo dõi thể trạng hội viên.', '2026-11-05 04:52:16'),
(27, 6, 25, '2026-11-05', 65.00, 170.00, 95.00, 75.00, 100.00, 'Số liệu mẫu theo dõi thể trạng hội viên.', '2026-11-05 04:52:16'),
(28, 6, 26, '2026-11-05', 66.00, 171.00, 96.00, 76.00, 101.00, 'Số liệu mẫu theo dõi thể trạng hội viên.', '2026-11-05 04:52:16'),
(29, 6, 27, '2026-11-05', 67.00, 172.00, 97.00, 77.00, 102.00, 'Số liệu mẫu theo dõi thể trạng hội viên.', '2026-11-05 04:52:16');

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
-- Chỉ mục cho bảng `bai_tap_giao_an_pt`
--
ALTER TABLE `bai_tap_giao_an_pt`
  ADD PRIMARY KEY (`exercise_id`),
  ADD KEY `idx_bai_tap_giao_an_plan` (`plan_id`);

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
-- Chỉ mục cho bảng `cai_dat`
--
ALTER TABLE `cai_dat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cai_dat_khoa_unique` (`khoa`);

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
-- Chỉ mục cho bảng `giao_an_pt`
--
ALTER TABLE `giao_an_pt`
  ADD PRIMARY KEY (`plan_id`),
  ADD KEY `idx_giao_an_pt_pt` (`pt_id`),
  ADD KEY `idx_giao_an_pt_hoi_vien` (`hoi_vien_id`);

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
-- Chỉ mục cho bảng `lien_he`
--
ALTER TABLE `lien_he`
  ADD PRIMARY KEY (`lien_he_id`),
  ADD KEY `idx_lien_he_nhan_vien` (`nhan_vien_id`);

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
-- Chỉ mục cho bảng `nhat_ky_buoi_tap_pt`
--
ALTER TABLE `nhat_ky_buoi_tap_pt`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `idx_nhat_ky_pt` (`pt_id`),
  ADD KEY `idx_nhat_ky_hoi_vien` (`hoi_vien_id`),
  ADD KEY `idx_nhat_ky_lich_pt` (`lich_pt_id`);

--
-- Chỉ mục cho bảng `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Chỉ mục cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

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
-- Chỉ mục cho bảng `tien_do_hoc_vien_pt`
--
ALTER TABLE `tien_do_hoc_vien_pt`
  ADD PRIMARY KEY (`progress_id`),
  ADD KEY `idx_tien_do_pt` (`pt_id`),
  ADD KEY `idx_tien_do_hoi_vien` (`hoi_vien_id`),
  ADD KEY `idx_tien_do_ngay` (`ngay_ghi_nhan`);

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
-- AUTO_INCREMENT cho bảng `bai_tap_giao_an_pt`
--
ALTER TABLE `bai_tap_giao_an_pt`
  MODIFY `exercise_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT cho bảng `cai_dat`
--
ALTER TABLE `cai_dat`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `check_in`
--
ALTER TABLE `check_in`
  MODIFY `check_in_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `chi_tiet_hoa_don`
--
ALTER TABLE `chi_tiet_hoa_don`
  MODIFY `chi_tiet_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT cho bảng `dang_ky_goi_pt`
--
ALTER TABLE `dang_ky_goi_pt`
  MODIFY `dang_ky_goi_pt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT cho bảng `dang_ky_goi_tap`
--
ALTER TABLE `dang_ky_goi_tap`
  MODIFY `dang_ky_goi_tap_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT cho bảng `dang_ky_lop`
--
ALTER TABLE `dang_ky_lop`
  MODIFY `dang_ky_lop_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `giao_an_pt`
--
ALTER TABLE `giao_an_pt`
  MODIFY `plan_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT cho bảng `goi_pt`
--
ALTER TABLE `goi_pt`
  MODIFY `goi_pt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `goi_tap`
--
ALTER TABLE `goi_tap`
  MODIFY `goi_tap_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `hoa_don`
--
ALTER TABLE `hoa_don`
  MODIFY `hoa_don_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT cho bảng `hoi_vien`
--
ALTER TABLE `hoi_vien`
  MODIFY `hoi_vien_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT cho bảng `huan_luyen_vien`
--
ALTER TABLE `huan_luyen_vien`
  MODIFY `pt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `lich_pt`
--
ALTER TABLE `lich_pt`
  MODIFY `lich_pt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `lien_he`
--
ALTER TABLE `lien_he`
  MODIFY `lien_he_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `lop_tap`
--
ALTER TABLE `lop_tap`
  MODIFY `lop_tap_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  MODIFY `nguoi_dung_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT cho bảng `nhan_vien`
--
ALTER TABLE `nhan_vien`
  MODIFY `nhan_vien_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `nhat_ky_buoi_tap_pt`
--
ALTER TABLE `nhat_ky_buoi_tap_pt`
  MODIFY `log_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `thong_bao`
--
ALTER TABLE `thong_bao`
  MODIFY `thong_bao_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT cho bảng `tien_do_hoc_vien_pt`
--
ALTER TABLE `tien_do_hoc_vien_pt`
  MODIFY `progress_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

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

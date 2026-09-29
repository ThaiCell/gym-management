-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th9 29, 2026 lúc 03:51 AM
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

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhan_vien`
--

CREATE TABLE `nhan_vien` (
  `nhan_vien_id` int(11) NOT NULL,
  `nguoi_dung_id` int(11) NOT NULL,
  `chuc_vu` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('eN4QPvBiq13QGsaW6MyVPnIo9kbmZcT5l5Xd4ZJe', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSTM5U0R2TXZqZFhPZGRHMTZyWkpkWUtNRDNxdWVRc040aGdVaktGYyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly9sb2NhbGhvc3QvZ3ltLW1hbmFnZW1lbnQvcHVibGljIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1790609001),
('igMjU0wLsP7NDKKXamltiD0FoKqBJ452WisOK7kW', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWExlUDVCb3J5V1RXenoybDFLOURPV213MWZsU2lXeG16b0RpZXhUTSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly9sb2NhbGhvc3QvZ3ltLW1hbmFnZW1lbnQvcHVibGljIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1790393850),
('YwdEfAfXMK2LBZlFulFUblVwPJR2BAlax1xPBkpm', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaDNBZ1M5dkZqZkRZM2xqM0d2Y3BScWRZUmw2N3F6RWp5RUZnQlNaTSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1790393023);

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
  MODIFY `check_in_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `chi_tiet_hoa_don`
--
ALTER TABLE `chi_tiet_hoa_don`
  MODIFY `chi_tiet_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `dang_ky_goi_pt`
--
ALTER TABLE `dang_ky_goi_pt`
  MODIFY `dang_ky_goi_pt_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `dang_ky_goi_tap`
--
ALTER TABLE `dang_ky_goi_tap`
  MODIFY `dang_ky_goi_tap_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `dang_ky_lop`
--
ALTER TABLE `dang_ky_lop`
  MODIFY `dang_ky_lop_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `goi_pt`
--
ALTER TABLE `goi_pt`
  MODIFY `goi_pt_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `goi_tap`
--
ALTER TABLE `goi_tap`
  MODIFY `goi_tap_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `hoa_don`
--
ALTER TABLE `hoa_don`
  MODIFY `hoa_don_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `hoi_vien`
--
ALTER TABLE `hoi_vien`
  MODIFY `hoi_vien_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `huan_luyen_vien`
--
ALTER TABLE `huan_luyen_vien`
  MODIFY `pt_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `lich_pt`
--
ALTER TABLE `lich_pt`
  MODIFY `lich_pt_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `lop_tap`
--
ALTER TABLE `lop_tap`
  MODIFY `lop_tap_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  MODIFY `nguoi_dung_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `nhan_vien`
--
ALTER TABLE `nhan_vien`
  MODIFY `nhan_vien_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `thong_bao`
--
ALTER TABLE `thong_bao`
  MODIFY `thong_bao_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `vai_tro`
--
ALTER TABLE `vai_tro`
  MODIFY `vai_tro_id` int(11) NOT NULL AUTO_INCREMENT;

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

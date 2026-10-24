<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Generated from database/gym_management.sql. MySQL/MariaDB only.
        DB::unprepared('CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
        DB::unprepared('CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
        DB::unprepared('CREATE TABLE `cai_dat` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `khoa` varchar(100) NOT NULL,
  `gia_tri` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
        DB::unprepared('CREATE TABLE `check_in` (
  `check_in_id` int(11) NOT NULL,
  `hoi_vien_id` int(11) NOT NULL,
  `dang_ky_goi_tap_id` int(11) NOT NULL,
  `thoi_gian` datetime NOT NULL,
  `nguoi_thuc_hien_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
        DB::unprepared('CREATE TABLE `chi_tiet_hoa_don` (
  `chi_tiet_id` int(11) NOT NULL,
  `hoa_don_id` int(11) NOT NULL,
  `goi_tap_id` int(11) DEFAULT NULL,
  `goi_pt_id` int(11) DEFAULT NULL,
  `so_luong` int(11) NOT NULL,
  `don_gia` decimal(12,2) NOT NULL,
  `thanh_tien` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
        DB::unprepared('CREATE TABLE `dang_ky_goi_pt` (
  `dang_ky_goi_pt_id` int(11) NOT NULL,
  `hoi_vien_id` int(11) NOT NULL,
  `goi_pt_id` int(11) NOT NULL,
  `pt_id` int(11) NOT NULL,
  `ngay_bat_dau` date NOT NULL,
  `ngay_ket_thuc` date NOT NULL,
  `so_buoi_con_lai` int(11) NOT NULL,
  `trang_thai` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
        DB::unprepared('CREATE TABLE `dang_ky_goi_tap` (
  `dang_ky_goi_tap_id` int(11) NOT NULL,
  `hoi_vien_id` int(11) NOT NULL,
  `goi_tap_id` int(11) NOT NULL,
  `ngay_bat_dau` date NOT NULL,
  `ngay_ket_thuc` date NOT NULL,
  `so_buoi_con_lai` int(11) NOT NULL DEFAULT 0,
  `trang_thai` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
        DB::unprepared('CREATE TABLE `dang_ky_lop` (
  `dang_ky_lop_id` int(11) NOT NULL,
  `lop_tap_id` int(11) NOT NULL,
  `hoi_vien_id` int(11) NOT NULL,
  `ngay_dang_ky` datetime NOT NULL,
  `trang_thai` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
        DB::unprepared('CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
        DB::unprepared('CREATE TABLE `goi_pt` (
  `goi_pt_id` int(11) NOT NULL,
  `ten_goi_pt` varchar(100) NOT NULL,
  `gia` decimal(12,2) NOT NULL,
  `so_buoi` int(11) NOT NULL,
  `thoi_han_ngay` int(11) NOT NULL,
  `anh` varchar(255) DEFAULT NULL,
  `trang_thai` varchar(30) NOT NULL DEFAULT \'hoat_dong\'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
        DB::unprepared('CREATE TABLE `goi_tap` (
  `goi_tap_id` int(11) NOT NULL,
  `ten_goi` varchar(100) NOT NULL,
  `gia` decimal(12,2) NOT NULL,
  `thoi_han_ngay` int(11) NOT NULL,
  `so_buoi` int(11) DEFAULT NULL,
  `trang_thai` varchar(30) NOT NULL DEFAULT \'hoat_dong\'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
        DB::unprepared('CREATE TABLE `hoa_don` (
  `hoa_don_id` int(11) NOT NULL,
  `hoi_vien_id` int(11) NOT NULL,
  `nhan_vien_id` int(11) NOT NULL,
  `ngay_lap` datetime NOT NULL,
  `tong_tien` decimal(12,2) NOT NULL,
  `trang_thai` varchar(30) NOT NULL,
  `phuong_thuc_thanh_toan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
        DB::unprepared('CREATE TABLE `hoi_vien` (
  `hoi_vien_id` int(11) NOT NULL,
  `nguoi_dung_id` int(11) NOT NULL,
  `ngay_sinh` date DEFAULT NULL,
  `so_dien_thoai` varchar(20) DEFAULT NULL,
  `dia_chi` varchar(255) DEFAULT NULL,
  `ngay_tham_gia` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
        DB::unprepared('CREATE TABLE `huan_luyen_vien` (
  `pt_id` int(11) NOT NULL,
  `nguoi_dung_id` int(11) NOT NULL,
  `chuyen_mon` varchar(255) DEFAULT NULL,
  `thanh_tich` text DEFAULT NULL,
  `so_dien_thoai` varchar(20) DEFAULT NULL,
  `anh` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
        DB::unprepared('CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
        DB::unprepared('CREATE TABLE `job_batches` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
        DB::unprepared('CREATE TABLE `lich_pt` (
  `lich_pt_id` int(11) NOT NULL,
  `dang_ky_goi_pt_id` int(11) NOT NULL,
  `hoi_vien_id` int(11) NOT NULL,
  `pt_id` int(11) NOT NULL,
  `thoi_gian_bat_dau` datetime NOT NULL,
  `thoi_gian_ket_thuc` datetime NOT NULL,
  `trang_thai` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
        DB::unprepared('CREATE TABLE `lien_he` (
  `lien_he_id` int(11) NOT NULL,
  `ho_ten` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `so_dien_thoai` varchar(20) NOT NULL,
  `noi_dung` text NOT NULL,
  `trang_thai` varchar(30) NOT NULL DEFAULT \'chưa xử lý\',
  `nhan_vien_id` int(11) DEFAULT NULL,
  `phan_hoi` text DEFAULT NULL,
  `tao_luc` datetime NOT NULL DEFAULT current_timestamp(),
  `xu_ly_luc` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
        DB::unprepared('CREATE TABLE `lop_tap` (
  `lop_tap_id` int(11) NOT NULL,
  `ten_lop` varchar(100) NOT NULL,
  `mo_ta` text DEFAULT NULL,
  `lich_tap` varchar(255) DEFAULT NULL,
  `suc_chua` int(11) DEFAULT NULL,
  `anh` varchar(255) DEFAULT NULL,
  `trang_thai` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
        DB::unprepared('CREATE TABLE `nguoi_dung` (
  `nguoi_dung_id` int(11) NOT NULL,
  `vai_tro_id` int(11) NOT NULL,
  `ho_ten` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mat_khau` varchar(255) NOT NULL,
  `trang_thai` varchar(30) NOT NULL DEFAULT \'hoat_dong\'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
        DB::unprepared('CREATE TABLE `nhan_vien` (
  `nhan_vien_id` int(11) NOT NULL,
  `nguoi_dung_id` int(11) NOT NULL,
  `chuc_vu` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
        DB::unprepared('CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
        DB::unprepared('CREATE TABLE `personal_access_tokens` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
        DB::unprepared('CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
        DB::unprepared('CREATE TABLE `thong_bao` (
  `thong_bao_id` int(11) NOT NULL,
  `nguoi_dung_id` int(11) NOT NULL,
  `tieu_de` varchar(150) NOT NULL,
  `noi_dung` text NOT NULL,
  `da_doc` tinyint(1) NOT NULL DEFAULT 0,
  `tao_luc` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
        DB::unprepared('CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
        DB::unprepared('CREATE TABLE `vai_tro` (
  `vai_tro_id` int(11) NOT NULL,
  `ten_vai_tro` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
        DB::unprepared('ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);');
        DB::unprepared('ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);');
        DB::unprepared('ALTER TABLE `cai_dat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cai_dat_khoa_unique` (`khoa`);');
        DB::unprepared('ALTER TABLE `check_in`
  ADD PRIMARY KEY (`check_in_id`),
  ADD KEY `check_in_hoi_vien_id_foreign` (`hoi_vien_id`),
  ADD KEY `check_in_dang_ky_goi_tap_id_foreign` (`dang_ky_goi_tap_id`),
  ADD KEY `check_in_nguoi_thuc_hien_id_foreign` (`nguoi_thuc_hien_id`);');
        DB::unprepared('ALTER TABLE `chi_tiet_hoa_don`
  ADD PRIMARY KEY (`chi_tiet_id`),
  ADD KEY `chi_tiet_hoa_don_hoa_don_id_foreign` (`hoa_don_id`),
  ADD KEY `chi_tiet_hoa_don_goi_tap_id_foreign` (`goi_tap_id`),
  ADD KEY `chi_tiet_hoa_don_goi_pt_id_foreign` (`goi_pt_id`);');
        DB::unprepared('ALTER TABLE `dang_ky_goi_pt`
  ADD PRIMARY KEY (`dang_ky_goi_pt_id`),
  ADD KEY `dang_ky_goi_pt_hoi_vien_id_foreign` (`hoi_vien_id`),
  ADD KEY `dang_ky_goi_pt_goi_pt_id_foreign` (`goi_pt_id`),
  ADD KEY `dang_ky_goi_pt_pt_id_foreign` (`pt_id`);');
        DB::unprepared('ALTER TABLE `dang_ky_goi_tap`
  ADD PRIMARY KEY (`dang_ky_goi_tap_id`),
  ADD KEY `dang_ky_goi_tap_hoi_vien_id_foreign` (`hoi_vien_id`),
  ADD KEY `dang_ky_goi_tap_goi_tap_id_foreign` (`goi_tap_id`);');
        DB::unprepared('ALTER TABLE `dang_ky_lop`
  ADD PRIMARY KEY (`dang_ky_lop_id`),
  ADD KEY `dang_ky_lop_lop_tap_id_foreign` (`lop_tap_id`),
  ADD KEY `dang_ky_lop_hoi_vien_id_foreign` (`hoi_vien_id`);');
        DB::unprepared('ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);');
        DB::unprepared('ALTER TABLE `goi_pt`
  ADD PRIMARY KEY (`goi_pt_id`);');
        DB::unprepared('ALTER TABLE `goi_tap`
  ADD PRIMARY KEY (`goi_tap_id`);');
        DB::unprepared('ALTER TABLE `hoa_don`
  ADD PRIMARY KEY (`hoa_don_id`),
  ADD KEY `hoa_don_hoi_vien_id_foreign` (`hoi_vien_id`),
  ADD KEY `hoa_don_nhan_vien_id_foreign` (`nhan_vien_id`);');
        DB::unprepared('ALTER TABLE `hoi_vien`
  ADD PRIMARY KEY (`hoi_vien_id`),
  ADD UNIQUE KEY `hoi_vien_nguoi_dung_id_unique` (`nguoi_dung_id`);');
        DB::unprepared('ALTER TABLE `huan_luyen_vien`
  ADD PRIMARY KEY (`pt_id`),
  ADD UNIQUE KEY `huan_luyen_vien_nguoi_dung_id_unique` (`nguoi_dung_id`);');
        DB::unprepared('ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);');
        DB::unprepared('ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);');
        DB::unprepared('ALTER TABLE `lich_pt`
  ADD PRIMARY KEY (`lich_pt_id`),
  ADD KEY `lich_pt_dang_ky_goi_pt_id_foreign` (`dang_ky_goi_pt_id`),
  ADD KEY `lich_pt_hoi_vien_id_foreign` (`hoi_vien_id`),
  ADD KEY `lich_pt_pt_id_foreign` (`pt_id`);');
        DB::unprepared('ALTER TABLE `lien_he`
  ADD PRIMARY KEY (`lien_he_id`),
  ADD KEY `idx_lien_he_nhan_vien` (`nhan_vien_id`);');
        DB::unprepared('ALTER TABLE `lop_tap`
  ADD PRIMARY KEY (`lop_tap_id`);');
        DB::unprepared('ALTER TABLE `nguoi_dung`
  ADD PRIMARY KEY (`nguoi_dung_id`),
  ADD UNIQUE KEY `nguoi_dung_email_unique` (`email`),
  ADD KEY `nguoi_dung_vai_tro_id_foreign` (`vai_tro_id`);');
        DB::unprepared('ALTER TABLE `nhan_vien`
  ADD PRIMARY KEY (`nhan_vien_id`),
  ADD UNIQUE KEY `nhan_vien_nguoi_dung_id_unique` (`nguoi_dung_id`);');
        DB::unprepared('ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);');
        DB::unprepared('ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);');
        DB::unprepared('ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);');
        DB::unprepared('ALTER TABLE `thong_bao`
  ADD PRIMARY KEY (`thong_bao_id`),
  ADD KEY `thong_bao_nguoi_dung_id_foreign` (`nguoi_dung_id`);');
        DB::unprepared('ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);');
        DB::unprepared('ALTER TABLE `vai_tro`
  ADD PRIMARY KEY (`vai_tro_id`),
  ADD UNIQUE KEY `vai_tro_ten_vai_tro_unique` (`ten_vai_tro`);');
        DB::unprepared('ALTER TABLE `cai_dat`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;');
        DB::unprepared('ALTER TABLE `check_in`
  MODIFY `check_in_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;');
        DB::unprepared('ALTER TABLE `chi_tiet_hoa_don`
  MODIFY `chi_tiet_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;');
        DB::unprepared('ALTER TABLE `dang_ky_goi_pt`
  MODIFY `dang_ky_goi_pt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;');
        DB::unprepared('ALTER TABLE `dang_ky_goi_tap`
  MODIFY `dang_ky_goi_tap_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;');
        DB::unprepared('ALTER TABLE `dang_ky_lop`
  MODIFY `dang_ky_lop_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;');
        DB::unprepared('ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;');
        DB::unprepared('ALTER TABLE `goi_pt`
  MODIFY `goi_pt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;');
        DB::unprepared('ALTER TABLE `goi_tap`
  MODIFY `goi_tap_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;');
        DB::unprepared('ALTER TABLE `hoa_don`
  MODIFY `hoa_don_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;');
        DB::unprepared('ALTER TABLE `hoi_vien`
  MODIFY `hoi_vien_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;');
        DB::unprepared('ALTER TABLE `huan_luyen_vien`
  MODIFY `pt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;');
        DB::unprepared('ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;');
        DB::unprepared('ALTER TABLE `lich_pt`
  MODIFY `lich_pt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;');
        DB::unprepared('ALTER TABLE `lien_he`
  MODIFY `lien_he_id` int(11) NOT NULL AUTO_INCREMENT;');
        DB::unprepared('ALTER TABLE `lop_tap`
  MODIFY `lop_tap_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;');
        DB::unprepared('ALTER TABLE `nguoi_dung`
  MODIFY `nguoi_dung_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;');
        DB::unprepared('ALTER TABLE `nhan_vien`
  MODIFY `nhan_vien_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;');
        DB::unprepared('ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;');
        DB::unprepared('ALTER TABLE `thong_bao`
  MODIFY `thong_bao_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;');
        DB::unprepared('ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;');
        DB::unprepared('ALTER TABLE `vai_tro`
  MODIFY `vai_tro_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;');
        DB::unprepared('ALTER TABLE `check_in`
  ADD CONSTRAINT `check_in_dang_ky_goi_tap_id_foreign` FOREIGN KEY (`dang_ky_goi_tap_id`) REFERENCES `dang_ky_goi_tap` (`dang_ky_goi_tap_id`),
  ADD CONSTRAINT `check_in_hoi_vien_id_foreign` FOREIGN KEY (`hoi_vien_id`) REFERENCES `hoi_vien` (`hoi_vien_id`),
  ADD CONSTRAINT `check_in_nguoi_thuc_hien_id_foreign` FOREIGN KEY (`nguoi_thuc_hien_id`) REFERENCES `nguoi_dung` (`nguoi_dung_id`);');
        DB::unprepared('ALTER TABLE `chi_tiet_hoa_don`
  ADD CONSTRAINT `chi_tiet_hoa_don_goi_pt_id_foreign` FOREIGN KEY (`goi_pt_id`) REFERENCES `goi_pt` (`goi_pt_id`),
  ADD CONSTRAINT `chi_tiet_hoa_don_goi_tap_id_foreign` FOREIGN KEY (`goi_tap_id`) REFERENCES `goi_tap` (`goi_tap_id`),
  ADD CONSTRAINT `chi_tiet_hoa_don_hoa_don_id_foreign` FOREIGN KEY (`hoa_don_id`) REFERENCES `hoa_don` (`hoa_don_id`);');
        DB::unprepared('ALTER TABLE `dang_ky_goi_pt`
  ADD CONSTRAINT `dang_ky_goi_pt_goi_pt_id_foreign` FOREIGN KEY (`goi_pt_id`) REFERENCES `goi_pt` (`goi_pt_id`),
  ADD CONSTRAINT `dang_ky_goi_pt_hoi_vien_id_foreign` FOREIGN KEY (`hoi_vien_id`) REFERENCES `hoi_vien` (`hoi_vien_id`),
  ADD CONSTRAINT `dang_ky_goi_pt_pt_id_foreign` FOREIGN KEY (`pt_id`) REFERENCES `huan_luyen_vien` (`pt_id`);');
        DB::unprepared('ALTER TABLE `dang_ky_goi_tap`
  ADD CONSTRAINT `dang_ky_goi_tap_goi_tap_id_foreign` FOREIGN KEY (`goi_tap_id`) REFERENCES `goi_tap` (`goi_tap_id`),
  ADD CONSTRAINT `dang_ky_goi_tap_hoi_vien_id_foreign` FOREIGN KEY (`hoi_vien_id`) REFERENCES `hoi_vien` (`hoi_vien_id`);');
        DB::unprepared('ALTER TABLE `dang_ky_lop`
  ADD CONSTRAINT `dang_ky_lop_hoi_vien_id_foreign` FOREIGN KEY (`hoi_vien_id`) REFERENCES `hoi_vien` (`hoi_vien_id`),
  ADD CONSTRAINT `dang_ky_lop_lop_tap_id_foreign` FOREIGN KEY (`lop_tap_id`) REFERENCES `lop_tap` (`lop_tap_id`);');
        DB::unprepared('ALTER TABLE `hoa_don`
  ADD CONSTRAINT `hoa_don_hoi_vien_id_foreign` FOREIGN KEY (`hoi_vien_id`) REFERENCES `hoi_vien` (`hoi_vien_id`),
  ADD CONSTRAINT `hoa_don_nhan_vien_id_foreign` FOREIGN KEY (`nhan_vien_id`) REFERENCES `nhan_vien` (`nhan_vien_id`);');
        DB::unprepared('ALTER TABLE `hoi_vien`
  ADD CONSTRAINT `hoi_vien_nguoi_dung_id_foreign` FOREIGN KEY (`nguoi_dung_id`) REFERENCES `nguoi_dung` (`nguoi_dung_id`);');
        DB::unprepared('ALTER TABLE `huan_luyen_vien`
  ADD CONSTRAINT `huan_luyen_vien_nguoi_dung_id_foreign` FOREIGN KEY (`nguoi_dung_id`) REFERENCES `nguoi_dung` (`nguoi_dung_id`);');
        DB::unprepared('ALTER TABLE `lich_pt`
  ADD CONSTRAINT `lich_pt_dang_ky_goi_pt_id_foreign` FOREIGN KEY (`dang_ky_goi_pt_id`) REFERENCES `dang_ky_goi_pt` (`dang_ky_goi_pt_id`),
  ADD CONSTRAINT `lich_pt_hoi_vien_id_foreign` FOREIGN KEY (`hoi_vien_id`) REFERENCES `hoi_vien` (`hoi_vien_id`),
  ADD CONSTRAINT `lich_pt_pt_id_foreign` FOREIGN KEY (`pt_id`) REFERENCES `huan_luyen_vien` (`pt_id`);');
        DB::unprepared('ALTER TABLE `nguoi_dung`
  ADD CONSTRAINT `nguoi_dung_vai_tro_id_foreign` FOREIGN KEY (`vai_tro_id`) REFERENCES `vai_tro` (`vai_tro_id`);');
        DB::unprepared('ALTER TABLE `nhan_vien`
  ADD CONSTRAINT `nhan_vien_nguoi_dung_id_foreign` FOREIGN KEY (`nguoi_dung_id`) REFERENCES `nguoi_dung` (`nguoi_dung_id`);');
        DB::unprepared('ALTER TABLE `thong_bao`
  ADD CONSTRAINT `thong_bao_nguoi_dung_id_foreign` FOREIGN KEY (`nguoi_dung_id`) REFERENCES `nguoi_dung` (`nguoi_dung_id`);');
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement("DROP TABLE IF EXISTS `vai_tro`");
        DB::statement("DROP TABLE IF EXISTS `users`");
        DB::statement("DROP TABLE IF EXISTS `thong_bao`");
        DB::statement("DROP TABLE IF EXISTS `sessions`");
        DB::statement("DROP TABLE IF EXISTS `personal_access_tokens`");
        DB::statement("DROP TABLE IF EXISTS `password_reset_tokens`");
        DB::statement("DROP TABLE IF EXISTS `nhan_vien`");
        DB::statement("DROP TABLE IF EXISTS `nguoi_dung`");
        DB::statement("DROP TABLE IF EXISTS `lop_tap`");
        DB::statement("DROP TABLE IF EXISTS `lien_he`");
        DB::statement("DROP TABLE IF EXISTS `lich_pt`");
        DB::statement("DROP TABLE IF EXISTS `job_batches`");
        DB::statement("DROP TABLE IF EXISTS `jobs`");
        DB::statement("DROP TABLE IF EXISTS `huan_luyen_vien`");
        DB::statement("DROP TABLE IF EXISTS `hoi_vien`");
        DB::statement("DROP TABLE IF EXISTS `hoa_don`");
        DB::statement("DROP TABLE IF EXISTS `goi_tap`");
        DB::statement("DROP TABLE IF EXISTS `goi_pt`");
        DB::statement("DROP TABLE IF EXISTS `failed_jobs`");
        DB::statement("DROP TABLE IF EXISTS `dang_ky_lop`");
        DB::statement("DROP TABLE IF EXISTS `dang_ky_goi_tap`");
        DB::statement("DROP TABLE IF EXISTS `dang_ky_goi_pt`");
        DB::statement("DROP TABLE IF EXISTS `chi_tiet_hoa_don`");
        DB::statement("DROP TABLE IF EXISTS `check_in`");
        DB::statement("DROP TABLE IF EXISTS `cai_dat`");
        DB::statement("DROP TABLE IF EXISTS `cache_locks`");
        DB::statement("DROP TABLE IF EXISTS `cache`");
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};

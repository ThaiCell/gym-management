<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. nguoi_dung
        Schema::create('nguoi_dung', function (Blueprint $table) {
            $table->integer('nguoi_dung_id')->autoIncrement()->primary();
            $table->integer('vai_tro_id');
            $table->string('ho_ten', 100);
            $table->string('email', 100)->unique();
            $table->string('mat_khau', 255);
            $table->string('trang_thai', 30)->default('hoat_dong');

            $table->foreign('vai_tro_id')
                ->references('vai_tro_id')
                ->on('vai_tro');
        });

        // 2. hoi_vien
        Schema::create('hoi_vien', function (Blueprint $table) {
            $table->integer('hoi_vien_id')->autoIncrement()->primary();
            $table->integer('nguoi_dung_id')->unique();
            $table->date('ngay_sinh')->nullable();
            $table->string('so_dien_thoai', 20)->nullable();
            $table->string('dia_chi', 255)->nullable();
            $table->date('ngay_tham_gia');

            $table->foreign('nguoi_dung_id')
                ->references('nguoi_dung_id')
                ->on('nguoi_dung');
        });

        // 3. nhan_vien
        Schema::create('nhan_vien', function (Blueprint $table) {
            $table->integer('nhan_vien_id')->autoIncrement()->primary();
            $table->integer('nguoi_dung_id')->unique();
            $table->string('chuc_vu', 100)->nullable();

            $table->foreign('nguoi_dung_id')
                ->references('nguoi_dung_id')
                ->on('nguoi_dung');
        });

        // 4. huan_luyen_vien
        Schema::create('huan_luyen_vien', function (Blueprint $table) {
            $table->integer('pt_id')->autoIncrement()->primary();
            $table->integer('nguoi_dung_id')->unique();
            $table->string('chuyen_mon', 255)->nullable();
            $table->string('so_dien_thoai', 20)->nullable();

            $table->foreign('nguoi_dung_id')
                ->references('nguoi_dung_id')
                ->on('nguoi_dung');
        });

        // 5. goi_tap
        Schema::create('goi_tap', function (Blueprint $table) {
            $table->integer('goi_tap_id')->autoIncrement()->primary();
            $table->string('ten_goi', 100);
            $table->decimal('gia', 12, 2);
            $table->integer('thoi_han_ngay');
            $table->integer('so_buoi')->nullable();
            $table->string('trang_thai', 30)->default('hoat_dong');
        });

        // 6. dang_ky_goi_tap
        Schema::create('dang_ky_goi_tap', function (Blueprint $table) {
            $table->integer('dang_ky_goi_tap_id')->autoIncrement()->primary();
            $table->integer('hoi_vien_id');
            $table->integer('goi_tap_id');
            $table->date('ngay_bat_dau');
            $table->date('ngay_ket_thuc');
            $table->integer('so_buoi_con_lai')->default(0);
            $table->string('trang_thai', 30);

            $table->foreign('hoi_vien_id')
                ->references('hoi_vien_id')
                ->on('hoi_vien');

            $table->foreign('goi_tap_id')
                ->references('goi_tap_id')
                ->on('goi_tap');
        });

        // 7. goi_pt
        Schema::create('goi_pt', function (Blueprint $table) {
            $table->integer('goi_pt_id')->autoIncrement()->primary();
            $table->string('ten_goi_pt', 100);
            $table->decimal('gia', 12, 2);
            $table->integer('so_buoi');
            $table->integer('thoi_han_ngay');
            $table->string('trang_thai', 30)->default('hoat_dong');
        });

        // 8. dang_ky_goi_pt
        Schema::create('dang_ky_goi_pt', function (Blueprint $table) {
            $table->integer('dang_ky_goi_pt_id')->autoIncrement()->primary();
            $table->integer('hoi_vien_id');
            $table->integer('goi_pt_id');
            $table->integer('pt_id');
            $table->date('ngay_bat_dau');
            $table->date('ngay_ket_thuc');
            $table->integer('so_buoi_con_lai');
            $table->string('trang_thai', 30);

            $table->foreign('hoi_vien_id')
                ->references('hoi_vien_id')
                ->on('hoi_vien');

            $table->foreign('goi_pt_id')
                ->references('goi_pt_id')
                ->on('goi_pt');

            $table->foreign('pt_id')
                ->references('pt_id')
                ->on('huan_luyen_vien');
        });

        // 9. lich_pt
        Schema::create('lich_pt', function (Blueprint $table) {
            $table->integer('lich_pt_id')->autoIncrement()->primary();
            $table->integer('dang_ky_goi_pt_id');
            $table->integer('hoi_vien_id');
            $table->integer('pt_id');
            $table->dateTime('thoi_gian_bat_dau');
            $table->dateTime('thoi_gian_ket_thuc');
            $table->string('trang_thai', 30);

            $table->foreign('dang_ky_goi_pt_id')
                ->references('dang_ky_goi_pt_id')
                ->on('dang_ky_goi_pt');

            $table->foreign('hoi_vien_id')
                ->references('hoi_vien_id')
                ->on('hoi_vien');

            $table->foreign('pt_id')
                ->references('pt_id')
                ->on('huan_luyen_vien');
        });

        // 10. check_in
        Schema::create('check_in', function (Blueprint $table) {
            $table->integer('check_in_id')->autoIncrement()->primary();
            $table->integer('hoi_vien_id');
            $table->integer('dang_ky_goi_tap_id');
            $table->dateTime('thoi_gian');
            $table->integer('nguoi_thuc_hien_id');

            $table->foreign('hoi_vien_id')
                ->references('hoi_vien_id')
                ->on('hoi_vien');

            $table->foreign('dang_ky_goi_tap_id')
                ->references('dang_ky_goi_tap_id')
                ->on('dang_ky_goi_tap');

            $table->foreign('nguoi_thuc_hien_id')
                ->references('nguoi_dung_id')
                ->on('nguoi_dung');
        });

        // 11. hoa_don
        Schema::create('hoa_don', function (Blueprint $table) {
            $table->integer('hoa_don_id')->autoIncrement()->primary();
            $table->integer('hoi_vien_id');
            $table->integer('nhan_vien_id');
            $table->dateTime('ngay_lap');
            $table->decimal('tong_tien', 12, 2);
            $table->string('trang_thai', 30);
            $table->string('phuong_thuc_thanh_toan', 50)->nullable();

            $table->foreign('hoi_vien_id')
                ->references('hoi_vien_id')
                ->on('hoi_vien');

            $table->foreign('nhan_vien_id')
                ->references('nhan_vien_id')
                ->on('nhan_vien');
        });

        // 12. chi_tiet_hoa_don
        Schema::create('chi_tiet_hoa_don', function (Blueprint $table) {
            $table->integer('chi_tiet_id')->autoIncrement()->primary();
            $table->integer('hoa_don_id');
            $table->integer('goi_tap_id')->nullable();
            $table->integer('goi_pt_id')->nullable();
            $table->integer('so_luong');
            $table->decimal('don_gia', 12, 2);
            $table->decimal('thanh_tien', 12, 2);

            $table->foreign('hoa_don_id')
                ->references('hoa_don_id')
                ->on('hoa_don');

            $table->foreign('goi_tap_id')
                ->references('goi_tap_id')
                ->on('goi_tap');

            $table->foreign('goi_pt_id')
                ->references('goi_pt_id')
                ->on('goi_pt');
        });

        // 13. lop_tap
        Schema::create('lop_tap', function (Blueprint $table) {
            $table->integer('lop_tap_id')->autoIncrement()->primary();
            $table->string('ten_lop', 100);
            $table->text('mo_ta')->nullable();
            $table->string('lich_tap', 255)->nullable();
            $table->integer('suc_chua')->nullable();
            $table->string('trang_thai', 30);
        });

        // 14. dang_ky_lop
        Schema::create('dang_ky_lop', function (Blueprint $table) {
            $table->integer('dang_ky_lop_id')->autoIncrement()->primary();
            $table->integer('lop_tap_id');
            $table->integer('hoi_vien_id');
            $table->dateTime('ngay_dang_ky');
            $table->string('trang_thai', 30);

            $table->foreign('lop_tap_id')
                ->references('lop_tap_id')
                ->on('lop_tap');

            $table->foreign('hoi_vien_id')
                ->references('hoi_vien_id')
                ->on('hoi_vien');
        });

        // 15. thong_bao
        Schema::create('thong_bao', function (Blueprint $table) {
            $table->integer('thong_bao_id')->autoIncrement()->primary();
            $table->integer('nguoi_dung_id');
            $table->string('tieu_de', 150);
            $table->text('noi_dung');
            $table->boolean('da_doc')->default(false);
            $table->dateTime('tao_luc');

            $table->foreign('nguoi_dung_id')
                ->references('nguoi_dung_id')
                ->on('nguoi_dung');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('thong_bao');
        Schema::dropIfExists('dang_ky_lop');
        Schema::dropIfExists('lop_tap');
        Schema::dropIfExists('chi_tiet_hoa_don');
        Schema::dropIfExists('hoa_don');
        Schema::dropIfExists('check_in');
        Schema::dropIfExists('lich_pt');
        Schema::dropIfExists('dang_ky_goi_pt');
        Schema::dropIfExists('goi_pt');
        Schema::dropIfExists('dang_ky_goi_tap');
        Schema::dropIfExists('goi_tap');
        Schema::dropIfExists('huan_luyen_vien');
        Schema::dropIfExists('nhan_vien');
        Schema::dropIfExists('hoi_vien');
        Schema::dropIfExists('nguoi_dung');
    }
};
<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ClassRegistrationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Tạo bảng tạm trong SQLite in-memory.
        // Không sử dụng database demo.
        Schema::create('hoi_vien', function (Blueprint $table) {
            $table->increments('hoi_vien_id');
            $table->integer('nguoi_dung_id');
        });

        Schema::create('lop_tap', function (Blueprint $table) {
            $table->increments('lop_tap_id');
            $table->string('ten_lop');
            $table->text('mo_ta')->nullable();
            $table->string('lich_tap')->nullable();
            $table->integer('suc_chua')->nullable();
            $table->string('trang_thai');
        });

        Schema::create('dang_ky_lop', function (Blueprint $table) {
            $table->increments('dang_ky_lop_id');
            $table->integer('lop_tap_id');
            $table->integer('hoi_vien_id');
            $table->dateTime('ngay_dang_ky')->nullable();
            $table->string('trang_thai');
        });

        Schema::create('thong_bao', function (Blueprint $table) {
            $table->increments('thong_bao_id');
            $table->integer('nguoi_dung_id');
            $table->string('tieu_de');
            $table->text('noi_dung');
            $table->integer('da_doc')->default(0);
            $table->dateTime('tao_luc')->nullable();
        });

        DB::table('hoi_vien')->insert([
            'hoi_vien_id' => 1,
            'nguoi_dung_id' => 100,
        ]);

        DB::table('lop_tap')->insert([
            'lop_tap_id' => 1,
            'ten_lop' => 'Lop test',
            'suc_chua' => 2,
            'trang_thai' => 'hoạt_động',
        ]);

        $this->withSession([
            'user' => (object) ['nguoi_dung_id' => 100],
            'role_id' => 3,
        ]);
    }

    // Đăng ký lớp hợp lệ phải tạo bản ghi
    public function test_member_can_register_for_active_class(): void
    {
        $this->post('/classes/register/1')
            ->assertRedirect('/classes')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('dang_ky_lop', [
            'lop_tap_id' => 1,
            'hoi_vien_id' => 1,
            'trang_thai' => 'đang hoạt động',
        ]);

        $this->assertDatabaseHas('thong_bao', [
            'nguoi_dung_id' => 100,
            'tieu_de' => 'Đăng ký lớp thành công',
        ]);
    }

    // Không được đăng ký trùng lớp đang hoạt động
    public function test_member_cannot_register_for_same_class_twice(): void
    {
        DB::table('dang_ky_lop')->insert([
            'lop_tap_id' => 1,
            'hoi_vien_id' => 1,
            'ngay_dang_ky' => now(),
            'trang_thai' => 'đang hoạt động',
        ]);

        $this->post('/classes/register/1')
            ->assertRedirect('/classes')
            ->assertSessionHas('error');

        $this->assertDatabaseCount('dang_ky_lop', 1);
    }

    // Không được đăng ký khi lớp đã đủ sức chứa
    public function test_member_cannot_register_when_class_is_full(): void
    {
        DB::table('hoi_vien')->insert([
            'hoi_vien_id' => 2,
            'nguoi_dung_id' => 200,
        ]);

        DB::table('dang_ky_lop')->insert([
            'lop_tap_id' => 1,
            'hoi_vien_id' => 2,
            'ngay_dang_ky' => now(),
            'trang_thai' => 'đang hoạt động',
        ]);

        // Sức chứa là 2, đã có 1 người đăng ký.
        // Hội viên hiện tại vẫn có thể đăng ký.
        $this->post('/classes/register/1')
            ->assertRedirect('/classes')
            ->assertSessionHas('success');

        // Lúc này lớp đủ 2 người, hội viên thứ 3 bị từ chối.
        DB::table('hoi_vien')->insert([
            'hoi_vien_id' => 3,
            'nguoi_dung_id' => 300,
        ]);

        $this->withSession([
            'user' => (object) ['nguoi_dung_id' => 300],
            'role_id' => 3,
        ])->post('/classes/register/1')
          ->assertRedirect('/classes')
          ->assertSessionHas('error');

        $this->assertDatabaseCount('dang_ky_lop', 2);
    }
}

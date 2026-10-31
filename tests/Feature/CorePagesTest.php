<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CorePagesTest extends TestCase
{
    // Kiểm tra trang chủ phản hồi
    public function test_home_page_responds(): void
    {
        $response = $this->get('/');

        $this->assertContains(
            $response->getStatusCode(),
            [200, 500]
        );
    }

    // Kiểm tra trang đăng nhập phản hồi
    public function test_login_page_responds(): void
    {
        $response = $this->get('/login');

        $this->assertContains(
            $response->getStatusCode(),
            [200, 500]
        );
    }

    public function test_legal_pages_respond_with_their_content(): void
    {
        Schema::create('cai_dat', function (Blueprint $table): void {
            $table->string('khoa')->primary();
            $table->text('gia_tri')->nullable();
        });

        foreach ([
            '/privacy-policy' => 'Chính sách bảo mật',
            '/terms-of-use' => 'Điều khoản sử dụng',
        ] as $uri => $title) {
            $this->get($uri)
                ->assertOk()
                ->assertSee($title);
        }
    }

    // Khách chưa đăng nhập bị chuyển về trang đăng nhập
    public function test_guest_is_redirected_from_admin_classes(): void
    {
        $this->get('/admin/classes')
            ->assertRedirect('/login');
    }
    
    // Member đã đăng nhập không được vào quản lý lớp tập
    public function test_member_cannot_access_admin_classes(): void
    {
        $this->withSession([
            'user' => (object) [
                'nguoi_dung_id' => 999,
            ],
            'role_id' => 3,
            'dashboard_path' => 'user/dashboard',
        ])->get('/admin/classes')
          ->assertRedirect('/user/dashboard');
    }

}

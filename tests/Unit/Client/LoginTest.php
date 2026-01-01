<?php

namespace Tests\Unit\Client;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{
    protected $user;
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Tắt CSRF để test POST
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        // Đảm bảo không trùng dữ liệu cũ
        User::whereIn('email', [
            'testuser@example.com',
            'admin@gmail.com',
        ])->delete();

        // Tạo user mặc định
        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);
    }

    protected function tearDown(): void
    {
        // Xoá dữ liệu sau mỗi test
        User::whereIn('email', [
            'testuser@example.com',
            'admin1@gmail.com',
        ])->delete();

        parent::tearDown();
    }

    /* =========================
     * TC1: Đăng nhập thành công (User)
     * ========================= */
    public function testLoginSuccess()
    {
        $response = $this->post('/login', [
            'email' => 'testuser@example.com',
            'password' => 'password123',
        ]);

        // Login web → redirect
        $response->assertStatus(302);

        // Kiểm tra đã đăng nhập đúng user
        $this->assertAuthenticatedAs($this->user);
    }

    /* =========================
     * TC2: Đăng nhập thành công (Admin)
     * ========================= */
    public function testAdminLoginSuccess()
    {
        // Tạo admin
        $this->admin = User::create([
            'name' => 'Admin',
            'email' => 'admin1@gmail.com',
            'password' => Hash::make('1234567'),
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin1@gmail.com',
            'password' => '1234567',
        ]);

        $response->assertStatus(302);
        $this->assertAuthenticatedAs($this->admin);
    }

    /* =========================
     * TC3: Đăng nhập thất bại
     * ========================= */
    public function testLoginFailure()
    {
        $response = $this->post('/login', [
            'email' => 'wrong@gmail.com',
            'password' => 'wrongpassword',
        ]);

        // Sai thông tin → redirect
        $response->assertStatus(302);

        // Đảm bảo chưa đăng nhập
        $this->assertGuest();
    }
}

<?php

namespace Tests\Unit\Client;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class RegisterTest extends TestCase
{
    protected string $testEmail = 'testuser@example.com';
    protected $file;


    protected function setUp(): void
    {
        parent::setUp();

        // Tắt CSRF để test POST
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    }

    protected function tearDown(): void
    {
        // Xóa user sau khi test
        User::where('email', $this->testEmail)->delete();

        // Xóa ảnh test nếu có
        if ($this->file)
            Storage::disk('public')->delete($this->file);

        parent::tearDown();
    }

    /* =========================
     * TC1: Đăng ký hợp lệ (có ảnh)
     * ========================= */
    public function testRegisterSuccessWithImage()
    {
        // Nếu máy không có GD thì skip
        if (!extension_loaded('gd')) {
            $this->markTestSkipped('GD extension is not installed.');
        }

        Storage::fake('public');
        $this->file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->post('/register', [
            'name' => 'Test User',
            'phone' => '1234567890',
            'birthday' => '2000-01-01',
            'address' => '123 Test Street',
            'email' => $this->testEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'image' => $this->file,
        ]);

        // Web register → redirect
        $response->assertStatus(302);
        $response->assertRedirect('/login');

        // User đã được tạo
        $this->assertDatabaseHas('users', [
            'email' => $this->testEmail,
        ]);

        // Ảnh đã được lưu
        Storage::disk('public')
            ->assertExists('images/users/' . $this->file->hashName());
    }

    /* =========================
     * TC2: Đăng ký hợp lệ (không ảnh)
     * ========================= */
    public function testRegisterSuccessWithoutImage()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'phone' => '1234567890',
            'birthday' => '2000-01-01',
            'address' => '123 Test Street',
            'email' => $this->testEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/login');

        $this->assertDatabaseHas('users', [
            'email' => $this->testEmail,
        ]);
    }

    /* =========================
     * TC3: Đăng ký thất bại (dữ liệu sai)
     * ========================= */
    public function testRegisterFailWithInvalidData()
    {
        $response = $this->post('/register', [
            'name' => '',
            'phone' => '',
            'birthday' => '',
            'address' => '',
            'email' => 'invalid-email',
            'password' => '123',
            'password_confirmation' => '123',
        ]);

        // Validation fail → redirect
        $response->assertStatus(302);

        $response->assertSessionHasErrors([
            'name',
            'phone',
            'birthday',
            'address',
            'email',
            'password',
        ]);

        // Không tạo user
        $this->assertDatabaseMissing('users', [
            'email' => 'invalid-email',
        ]);
    }
}

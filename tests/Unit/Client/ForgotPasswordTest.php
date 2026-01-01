<?php

namespace Tests\Unit\Client;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\Client\UserClientController;
use Illuminate\Validation\ValidationException;

class ForgotPasswordTest extends TestCase
{
    protected $controller;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = $this->app->make(UserClientController::class);

        User::where('email', 'minhchi@gmail.com')->delete();

        // Tạo user test
        $this->user = User::create([
            'name'     => 'Minh Chi',
            'email'    => 'minhchi@gmail.com',
            'phone'    => '0327264556',
            'password' => Hash::make('oldpassword'),
        ]);
    }

    protected function tearDown(): void
    {
        // Dọn dữ liệu giống AddToCartTest
        if ($this->user) {
            $this->user->delete();
            $this->user = null;
        }

        parent::tearDown();
    }

    // =========================
    // TC1: Validate fail
    // =========================
    public function test_forgot_password_validate_fail()
    {
        $request = Request::create('/forgot-password', 'POST', [
            'email' => '',
            'phone' => '',
        ]);

        $this->expectException(ValidationException::class);

        $this->controller->checkInfo($request);
    }

    // =========================
    // TC2: Email / phone sai
    // =========================
    public function test_forgot_password_email_or_phone_incorrect()
    {
        $request = Request::create('/forgot-password', 'POST', [
            'email' => 'sai@gmail.com',
            'phone' => '0999999999',
        ]);

        $response = $this->controller->checkInfo($request);

        $this->assertTrue($response->isRedirect());
    }

    // =========================
    // TC3: Thành công → redirect form reset
    // =========================
    public function test_forgot_password_success_redirect_to_reset_form()
    {
        $request = Request::create('/forgot-password', 'POST', [
            'email' => 'minhchi@gmail.com',
            'phone' => '0327264556',
        ]);

        $response = $this->controller->checkInfo($request);

        $this->assertTrue($response->isRedirect());
        $this->assertStringContainsString(
            (string) $this->user->id,
            $response->getTargetUrl()
        );
    }

    // =========================
    // TC4: Reset password validate fail
    // =========================
    public function test_reset_password_validate_fail()
    {
        $request = Request::create('/reset-password', 'POST', [
            'password' => '123',
            'password_confirmation' => '456',
        ]);

        $this->expectException(ValidationException::class);

        $this->controller->updatePassword($request, $this->user->id);
    }

    // =========================
    // TC5: Reset password success
    // =========================
    public function test_reset_password_success()
    {
        $request = Request::create('/reset-password', 'POST', [
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response = $this->controller->updatePassword($request, $this->user->id);

        $this->assertTrue($response->isRedirect());

        $this->assertTrue(
            Hash::check('newpassword123', $this->user->fresh()->password)
        );
    }
}

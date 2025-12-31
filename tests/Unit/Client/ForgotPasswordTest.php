<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Tắt middleware để test luồng logic
        $this->withoutMiddleware();

        /**
         * LƯU Ý:
         * Nếu bạn dùng RefreshDatabase, DB sẽ bị reset.
         * => Cần tạo lại user test với đúng email/phone của bạn.
         */
        User::create([
            'name' => 'Minh Chi',
            'email' => 'minhchi@gmail.com',
            'phone' => '0327264556',
            'password' => Hash::make('oldpassword'),
        ]);
    }

    /** @test */
    public function can_view_forgot_password_form()
    {
        $response = $this->get(route('password.form'));

        $response->assertStatus(200);
        $response->assertViewIs('backend.auth.forgot');
    }

    /** @test */
    public function forgot_password_validate_fail()
    {
        $response = $this->post(route('password.check'), [
            'email' => '',
            'phone' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email', 'phone']);
    }

    /** @test */
    public function forgot_password_email_or_phone_incorrect()
    {
        $response = $this->post(route('password.check'), [
            'email' => 'sai@gmail.com',
            'phone' => '0999999999',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function forgot_password_success_redirect_to_reset_form()
    {
        $user = User::where('email', 'minhchi@gmail.com')
                    ->where('phone', '0327264556')
                    ->firstOrFail();

        $response = $this->post(route('password.check'), [
            'email' => 'minhchi@gmail.com',
            'phone' => '0327264556',
        ]);

        $response->assertRedirect(
            route('password.reset.form', ['user' => $user->id])
        );
    }

    /** @test */
    public function can_view_reset_password_form()
    {
        $user = User::where('email', 'minhchi@gmail.com')->firstOrFail();

        $response = $this->get(
            route('password.reset.form', ['user' => $user->id])
        );

        $response->assertStatus(200);
        $response->assertViewIs('backend.auth.reset');
        $response->assertViewHas('user');
    }

    /** @test */
    public function reset_password_validate_fail()
    {
        $user = User::where('email', 'minhchi@gmail.com')->firstOrFail();

        $response = $this->post(
            route('password.update.simple', ['user' => $user->id]),
            [
                'password' => '123',
                'password_confirmation' => '456',
            ]
        );

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function reset_password_success()
    {
        $user = User::where('email', 'minhchi@gmail.com')->firstOrFail();

        $response = $this->post(
            route('password.update.simple', ['user' => $user->id]),
            [
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]
        );

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');

        $this->assertTrue(
            Hash::check('newpassword123', $user->fresh()->password)
        );
    }
}

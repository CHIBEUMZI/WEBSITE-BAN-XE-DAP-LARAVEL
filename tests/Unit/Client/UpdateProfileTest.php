<?php

namespace Tests\Feature\Client;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateProfileTest extends TestCase
{

    /** TC1 – Cập nhật thành công */
    public function test_TC1_update_profile_success()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->put(route('profile.update', $user->id), [
            'name'    => 'Nguyen Van A',
            'email'   => 'a@gmail.com',
            'phone'   => '0123456789',
            'address' => 'Ha Noi',
        ]);

        $response->assertRedirect(route('client.home'));
        $this->assertDatabaseHas('users', [
            'id'    => $user->id,
            'email' => 'a@gmail.com',
        ]);
    }

    /** TC2 – Thiếu address */
    public function test_TC2_missing_address()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->put(route('profile.update', $user->id), [
            'name'  => 'Test',
            'email' => 'test@gmail.com',
        ]);

        $response->assertSessionHasErrors('address');
    }

    /** TC3 – Email sai định dạng */
    public function test_TC3_invalid_email()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->put(route('profile.update', $user->id), [
            'name'    => 'Test',
            'email'   => 'abc',
            'address' => 'HN',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** TC4 – Phone sai regex */
    public function test_TC4_invalid_phone()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->put(route('profile.update', $user->id), [
            'name'    => 'Test',
            'email'   => 'test@gmail.com',
            'phone'   => 'abcxyz',
            'address' => 'HN',
        ]);

        $response->assertSessionHasErrors('phone');
    }

    /** TC5 – Ngày sinh trong tương lai */
    public function test_TC5_future_birthday()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->put(route('profile.update', $user->id), [
            'name'    => 'Test',
            'email'   => 'test@gmail.com',
            'address' => 'HN',
            'day'     => 1,
            'month'   => 1,
            'year'    => now()->year + 1,
        ]);

        $response->assertSessionHasErrors('birthday');
    }

    /** TC6 – Không đúng quyền (403) */
    public function test_TC6_unauthorized_update()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $this->actingAs($user1);

        $response = $this->put(route('profile.update', $user2->id), [
            'name'    => 'Hack',
            'email'   => 'hack@gmail.com',
            'address' => 'HN',
        ]);

        $response->assertStatus(403);
    }
}

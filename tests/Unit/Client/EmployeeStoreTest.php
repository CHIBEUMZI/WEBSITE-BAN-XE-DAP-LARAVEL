<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class EmployeeStoreTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        // TẮT TOÀN BỘ middleware (auth, logout, csrf, ...)
        // để test trực tiếp logic controller
        $this->withoutMiddleware();
    }

    // =========================
    // TC1: Dữ liệu không hợp lệ 
    // =========================
    public function store_employee_validate_fail()
    {
        $response = $this->post(route('employees.store'), [
            'name' => '',
            'phone' => '',
            'position' => '',
            'address' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'name',
            'phone',
            'position',
            'address',
        ]);

        $this->assertDatabaseCount('employees', 0);
    }

    // =========================
    // TC2: Dữ liệu hợp lệ + có ảnh
    // =========================
    public function test_store_employee_with_image()
    {
        Storage::fake('public');

        // Không dùng fake()->image() để tránh lỗi GD
        $file = UploadedFile::fake()->create(
            'employee.jpg',
            500,
            'image/jpeg'
        );

        $response = $this->post(route('employees.store'), [
            'name'     => 'Nguyen Van C',
            'phone'    => '0911222333',
            'position' => 'Nhân viên kỹ thuật',
            'address'  => 'Hồ Chí Minh',
            'image'    => $file,
        ]);

        $response->assertRedirect(route('employees.index'));
        $response->assertSessionHas('success');

        // Kiểm tra file đã được lưu
        $this->assertTrue(
            Storage::disk('public')->exists(
                'images/employees/' . $file->hashName()
            )
        );

        // Kiểm tra DB
        $this->assertDatabaseHas('employees', [
            'name'  => 'Nguyen Van C',
            'phone' => '0911222333',
            'image' => 'images/employees/' . $file->hashName(),
        ]);
    }

    // =========================
    // TC3: Dữ liệu hợp lệ + không có ảnh
    // =========================
    public function test_store_employee_success_without_image()
    {
        $response = $this->post(route('employees.store'), [
            'name'     => 'Nguyen Van B',
            'phone'    => '0987654321',
            'position' => 'Nhân viên kho',
            'address'  => 'Đà Nẵng',
        ]);

        $response->assertRedirect(route('employees.index'));

        $this->assertDatabaseHas('employees', [
            'name'  => 'Nguyen Van B',
            'image' => null,
        ]);
    }



    /** @test */
    // public function store_employee_phone_invalid_format()
    // {
    //     $response = $this->post(route('employees.store'), [
    //         'name' => 'Nguyen Van A',
    //         'phone' => '12345', // sai regex (không đủ 10 số)
    //         'position' => 'Nhân viên bán hàng',
    //         'address' => 'Hồ Chí Minh',
    //     ]);

    //     $response->assertStatus(302);
    //     $response->assertSessionHasErrors(['phone']);

    //     $this->assertDatabaseCount('employees', 0);
    // }

    // /** @test */
    // public function store_employee_phone_duplicate()
    // {
    //     // Tạo sẵn 1 nhân viên để kiểm tra unique phone
    //     DB::table('employees')->insert([
    //         'name' => 'Employee Old',
    //         'phone' => '0912345678',
    //         'position' => 'NV',
    //         'address' => 'Hà Nội',
    //         'created_at' => now(),
    //         'updated_at' => now(),
    //     ]);

    //     $response = $this->post(route('employees.store'), [
    //         'name' => 'Employee New',
    //         'phone' => '0912345678', // trùng
    //         'position' => 'NV',
    //         'address' => 'Hồ Chí Minh',
    //     ]);

    //     $response->assertStatus(302);
    //     $response->assertSessionHasErrors(['phone']);

    //     // Vẫn chỉ có 1 bản ghi
    //     $this->assertDatabaseCount('employees', 1);
    // }

    // /** @test */
    // public function store_employee_success_without_image()
    // {
    //     $response = $this->post(route('employees.store'), [
    //         'name' => 'Nguyen Van B',
    //         'phone' => '0987654321',
    //         'position' => 'Nhân viên kho',
    //         'address' => 'Đà Nẵng',
    //     ]);

    //     $response->assertRedirect(route('employees.index'));
    //     $response->assertSessionHas('success');

    //     $this->assertDatabaseHas('employees', [
    //         'name' => 'Nguyen Van B',
    //         'phone' => '0987654321',
    //         'position' => 'Nhân viên kho',
    //         'address' => 'Đà Nẵng',
    //     ]);
    // }
}

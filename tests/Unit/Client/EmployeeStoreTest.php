<?php

namespace Tests\Unit\Client;

use Tests\TestCase;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Backend\EmployeeController;
use Illuminate\Validation\ValidationException;

class EmployeeStoreTest extends TestCase
{
    protected $controller;
    protected $employee;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = $this->app->make(EmployeeController::class);
    }

    protected function tearDown(): void
    {
        if ($this->employee) {
            $this->employee->delete();
            $this->employee = null;
        }

        parent::tearDown();
    }

    // =========================
    // TC1: Dữ liệu không hợp lệ
    // =========================
    public function test_store_employee_validate_fail()
    {
        $request = Request::create('/employees/store', 'POST', [
            'name'     => '',
            'phone'    => '',
            'position' => '',
            'address'  => '',
        ]);

        $this->expectException(ValidationException::class);

        $this->controller->store($request);
    }

    // =========================
    // TC2: Dữ liệu hợp lệ + có ảnh
    // =========================
    public function test_store_employee_with_image()
    {
        Storage::fake('public');
        $phone = fake()->regexify("0[0-9]{9}");

        $file = UploadedFile::fake()->create(
            'employee.jpg',
            500,
            'image/jpeg'
        );

        $request = Request::create('/employees/store', 'POST', [
            'name'     => 'Nguyen Van C',
            'phone'    => $phone,
            'position' => 'Nhân viên kỹ thuật',
            'address'  => 'Hồ Chí Minh',
        ], [], [
            'image' => $file,
        ]);

        $response = $this->controller->store($request);

        $this->assertTrue($response->isRedirect());

        $this->employee = Employee::where('phone', $phone)->first();
        $this->assertNotNull($this->employee);
        $this->assertNotEmpty($this->employee->image);

        Storage::disk('public')->assertExists($this->employee->image);
    }

    // =========================
    // TC3: Dữ liệu hợp lệ + không có ảnh
    // =========================
    public function test_store_employee_success_without_image()
    {
        $phone = fake()->regexify("0[0-9]{9}");
        $request = Request::create('/employees/store', 'POST', [
            'name'     => 'Nguyen Van B',
            'phone'    => $phone,
            'position' => 'Nhân viên kho',
            'address'  => 'Đà Nẵng',
        ]);

        $response = $this->controller->store($request);

        $this->assertTrue($response->isRedirect());

        $this->employee = Employee::where('phone', $phone)->first();
        $this->assertNotNull($this->employee);

        $this->assertTrue(
            $this->employee->image === null || $this->employee->image === ''
        );
    }
}

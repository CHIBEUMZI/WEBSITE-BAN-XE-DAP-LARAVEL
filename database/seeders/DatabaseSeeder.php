<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Trong database/seeders/DatabaseSeeder.php
\App\Models\ChatbotResponse::create([
    'question' => 'xin chào',
    'answer' => 'Chào bạn! Tôi có thể giúp gì cho bạn?',
]);
\App\Models\ChatbotResponse::create([
    'question' => 'giá sản phẩm x',
    'answer' => 'Sản phẩm X có giá 1.000.000 VNĐ.',
]);
    }
}

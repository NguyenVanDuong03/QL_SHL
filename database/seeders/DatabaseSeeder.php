<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Phòng Chính trị và Công tác sinh viên',
            'email' => 'ctsv@e.tlu.edu.vn',
            'password' => bcrypt('12345678'),
            'role' => '1',
        ]);
    }
}

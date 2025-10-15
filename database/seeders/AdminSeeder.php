<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah admin sudah ada
        if (Admin::where('email', 'admin@lgistore.com')->exists()) {
            $this->command->info('Admin sudah ada!');
            return;
        }

        // Buat admin default
        Admin::create([
            'name' => 'Administrator',
            'email' => 'admin@lgistore.com',
            'password' => Hash::make('admin123'), // Password: admin123
        ]);

        $this->command->info('Admin berhasil dibuat!');
        $this->command->info('Email: admin@lgistore.com');
        $this->command->info('Password: admin123');
    }
}
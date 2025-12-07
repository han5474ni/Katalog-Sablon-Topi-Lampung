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
        if (Admin::where('email', 'sablontopilampung@gmail.com')->exists()) {
            $this->command->info('Admin sudah ada!');
            return;
        }

        // Buat admin default
        Admin::create([
            'name' => 'Administrator',
            'email' => 'sablontopilampung@gmail.com',
            'password' => Hash::make('sablontopi@2025#'), // Password: admin123
            'role' => 'super_admin',
        ]);

        $this->command->info('Admin berhasil dibuat!');
        $this->command->info('Email: sablontopilampung@gmail.com');
        $this->command->info('Password: sablontopi@2025#');
    }
}
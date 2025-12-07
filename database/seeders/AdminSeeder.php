<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'sablontopilampung@gmail.com',
            'password' => Hash::make('sablontopi@2025#'),
        ]);

        $this->command->info('Admin berhasil dibuat!');
        $this->command->info('Email: sablontopilampung@gmail.com');
        $this->command->info('Password: sablontopi@2025#');
    }
}

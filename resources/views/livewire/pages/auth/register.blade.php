<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

use function Livewire\Volt\layout;
use function Livewire\Volt\rules;
use function Livewire\Volt\state;

layout('layouts.auth');

state([
    'name' => '',
    'email' => '',
    'password' => '',
    'password_confirmation' => ''
]);

rules([
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
    'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
]);

$register = function () {
    $validated = $this->validate();

    $validated['password'] = Hash::make($validated['password']);

    event(new Registered($user = User::create($validated)));

    Auth::login($user);

    $this->redirect(route('dashboard', absolute: false), navigate: true);
};

?>

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-900 via-gray-800 to-black relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.03"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>
    
    <div class="relative z-10 w-full max-w-md">
        <!-- Logo Header -->
        <div class="bg-gradient-to-r from-gray-900 to-gray-800 p-8 rounded-t-2xl shadow-2xl border border-gray-700">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-full mb-4 shadow-lg">
                    <svg class="w-8 h-8 text-gray-900" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm2 6a2 2 0 114 0 2 2 0 01-4 0zm8 0a2 2 0 114 0 2 2 0 01-4 0z" clip-rule="evenodd" />
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-white tracking-wider">LGI STORE</h1>
                <p class="text-gray-400 text-sm mt-2 tracking-wide">Katalog Sablon Topi Lampung</p>
            </div>
        </div>

        <!-- Register Form -->
        <div class="bg-gradient-to-b from-gray-800 to-gray-900 p-8 rounded-b-2xl shadow-2xl border-x border-b border-gray-700">
            <h2 class="text-2xl font-bold text-white text-center mb-8 tracking-wide">BUAT AKUN BARU</h2>
            
            <form wire:submit="register" class="space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-yellow-400 text-sm font-semibold mb-2 tracking-wide">
                        <i class="fas fa-user mr-2"></i>Nama Lengkap
                    </label>
                    <input wire:model="name" id="name" type="text" name="name" required autofocus autocomplete="name"
                           class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 transition duration-200 text-gray-900"
                           placeholder="Masukkan nama lengkap Anda">
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-400" />
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-yellow-400 text-sm font-semibold mb-2 tracking-wide">
                        <i class="fas fa-envelope mr-2"></i>Email
                    </label>
                    <input wire:model="email" id="email" type="email" name="email" required autocomplete="username"
                           class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 transition duration-200 text-gray-900"
                           placeholder="Masukkan email Anda">
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-yellow-400 text-sm font-semibold mb-2 tracking-wide">
                        <i class="fas fa-lock mr-2"></i>Password
                    </label>
                    <input wire:model="password" id="password" type="password" name="password" required autocomplete="new-password"
                           class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 transition duration-200 text-gray-900"
                           placeholder="Masukkan password Anda">
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-yellow-400 text-sm font-semibold mb-2 tracking-wide">
                        <i class="fas fa-lock mr-2"></i>Konfirmasi Password
                    </label>
                    <input wire:model="password_confirmation" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                           class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 transition duration-200 text-gray-900"
                           placeholder="Konfirmasi password Anda">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-400" />
                </div>

                <!-- Terms -->
                <div class="text-center">
                    <p class="text-gray-400 text-xs leading-relaxed">
                        Dengan membuat akun, Anda setuju dengan 
                        <a href="#" class="text-yellow-400 hover:text-yellow-300">Ketentuan Layanan</a> dan 
                        <a href="#" class="text-yellow-400 hover:text-yellow-300">Kebijakan Privasi</a> kami.
                    </p>
                </div>

                <!-- Register Button -->
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-yellow-400 to-yellow-500 text-gray-900 font-bold py-3 px-4 rounded-lg hover:from-yellow-500 hover:to-yellow-600 transform hover:-translate-y-1 transition duration-200 shadow-lg hover:shadow-xl tracking-wide">
                    <i class="fas fa-user-plus mr-2"></i>DAFTAR
                </button>
            </form>

            <!-- Login Link -->
            <div class="text-center mt-6">
                <p class="text-gray-400 text-sm">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" wire:navigate class="text-yellow-400 hover:text-yellow-300 font-semibold transition duration-200">
                        Masuk sekarang
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

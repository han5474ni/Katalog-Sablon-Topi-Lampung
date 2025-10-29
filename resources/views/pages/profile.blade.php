<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profil - LGI Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/customer/shared.css', 'resources/css/customer/profile-form.css', 'resources/js/customer/profile-dropdown.js'])
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <x-customer-sidebar active="profile" />

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <x-customer-header title="Profile" />

            <div class="content-wrapper">
                <!-- 2 Column Layout -->
                <div class="profile-layout">
                    <!-- Left Column -->
                    <div class="profile-left-column">
                        <!-- Profile Photo -->
                        <div class="profile-card">
                            <h3 class="card-title">Foto Profil</h3>
                            <div class="photo-upload-section">
                                <div class="photo-preview-large">
                                    @if($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" id="avatar-preview">
                                    @else
                                        <div class="no-photo-large">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="photo-actions-centered">
                                    <label for="avatar-input" class="btn-upload">
                                        <i class="fas fa-camera"></i> Upload Foto
                                    </label>
                                    <input type="file" id="avatar-input" accept="image/*" style="display: none;">
                                    @if($user->avatar)
                                        <button type="button" id="delete-avatar-btn" class="btn-delete">
                                            <i class="fas fa-trash"></i> Hapus Foto
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Personal Info -->
                        <div class="profile-card">
                            <h3 class="card-title">Informasi Dasar</h3>
                            <form id="profile-form">
                                @csrf
                                
                                <div class="form-group">
                                    <label for="name">Nama Lengkap *</label>
                                    <input type="text" id="name" name="name" value="{{ $user->name }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="email">Email *</label>
                                    <input type="email" id="email" name="email" value="{{ $user->email }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="phone">No. Telepon</label>
                                    <input type="tel" id="phone" name="phone" value="{{ $user->phone }}" placeholder="081234567890">
                                </div>
                            </form>
                        </div>

                        <!-- Change Password -->
                        <div class="profile-card">
                            <h3 class="card-title">Keamanan Akun</h3>
                            <form id="password-form">
                                @csrf
                                
                                <div class="form-group">
                                    <label for="current_password">Password Saat Ini *</label>
                                    <input type="password" id="current_password" name="current_password" required>
                                </div>

                                <div class="form-group">
                                    <label for="password">Password Baru *</label>
                                    <input type="password" id="password" name="password" required minlength="8">
                                </div>

                                <div class="form-group">
                                    <label for="password_confirmation">Konfirmasi Password Baru *</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8">
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn-save btn-full">
                                        <i class="fas fa-key"></i> Ubah Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="profile-right-column">
                        <!-- Address Information -->
                        <div class="profile-card">
                            <h3 class="card-title">Informasi Alamat</h3>
                            <form id="address-form">
                                @csrf
                                
                                <div class="form-group">
                                    <label for="address">Alamat Lengkap (Jalan, RT/RW) *</label>
                                    <textarea id="address" name="address" rows="3" required placeholder="Jl. Sudirman No. 123, RT 01/RW 02">{{ $user->address }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="province">Provinsi *</label>
                                    <select id="province" name="province" required data-current="{{ $user->province }}">
                                        <option value="">Pilih Provinsi</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="city">Kota/Kabupaten *</label>
                                    <select id="city" name="city" required disabled data-current="{{ $user->city }}">
                                        <option value="">Pilih Kota/Kabupaten</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="district">Kecamatan *</label>
                                    <select id="district" name="district" required disabled data-current="{{ $user->district }}">
                                        <option value="">Pilih Kecamatan</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="postal_code">Kode Pos *</label>
                                    <input type="text" id="postal_code" name="postal_code" value="{{ $user->postal_code }}" required readonly placeholder="Auto-fill dari kecamatan">
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn-save btn-full">
                                        <i class="fas fa-save"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="loading-overlay" class="loading-overlay" style="display: none;">
        <div class="spinner"></div>
    </div>
</body>
</html>
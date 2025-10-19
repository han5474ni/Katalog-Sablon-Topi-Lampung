<x-admin-layout>
    <x-slot name="title">Admin Profile</x-slot>

    @push('styles')
    <style>
        .profile-container {
            padding: 24px;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 24px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .profile-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            height: fit-content;
        }

        .left-column {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .avatar-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
        }

        .avatar-preview {
            width: 128px;
            height: 128px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #ffc107;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .avatar-placeholder {
            width: 128px;
            height: 128px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1a3a52 0%, #0a1d37 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: #ffffff;
            border: 4px solid #e0e0e0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .avatar-actions {
            display: flex;
            flex-direction: column;
            gap: 12px;
            width: 100%;
        }

        .btn-upload {
            padding: 10px 20px;
            background: linear-gradient(to right, #f9a825, #ffc107);
            color: #1a1a2e;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-upload:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 193, 7, 0.4);
        }

        .btn-delete-avatar {
            padding: 10px 16px;
            background: #DC2626;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: transform 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-delete-avatar:hover {
            background: #B91C1C;
            transform: translateY(-2px);
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f0f0f0;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #ffc107;
            box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.1);
        }

        .btn-primary {
            padding: 12px 24px;
            background: linear-gradient(to right, #f9a825, #ffc107);
            color: #1a1a2e;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 193, 7, 0.4);
        }

        @media (max-width: 1024px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    @endpush

    <div class="profile-container">
        <div class="profile-grid">
            <!-- Left Column (1/3 width) -->
            <div class="left-column">
                <!-- Profile Photo Card -->
                <div class="profile-card">
                    <h3 class="section-title">Profile Photo</h3>
                    <form action="{{ route('admin.profile.update-avatar') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="avatar-section">
                            @if($admin->avatar)
                                <img src="{{ Storage::url($admin->avatar) }}" alt="Profile Photo" class="avatar-preview">
                            @else
                                <div class="avatar-placeholder">
                                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                                </div>
                            @endif

                            <div class="avatar-actions">
                                <input type="file" name="avatar" id="avatar" accept="image/*" style="display: none;">
                                <button type="button" onclick="document.getElementById('avatar').click()" class="btn-upload">
                                    <span class="material-icons">photo_camera</span>
                                    Upload Photo
                                </button>
                                
                                @if($admin->avatar)
                                    <form action="{{ route('admin.profile.delete-avatar') }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete-avatar" onclick="return confirm('Are you sure you want to delete your avatar?')">
                                            <span class="material-icons">delete</span>
                                            Remove Photo
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
                
                <!-- Change Password Card -->
                <div class="profile-card">
                    <h3 class="section-title">Change Password</h3>
                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="name" value="{{ $admin->name }}">
                        
                        <div class="form-group">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-input" required>
                            @error('current_password')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" class="form-input" required>
                            @error('password')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-input" required>
                        </div>

                        <button type="submit" class="btn-primary">
                            <span class="material-icons">save</span>
                            Update Password
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Column (2/3 width) -->
            <div>
                <!-- Profile Information Card -->
                <div class="profile-card">
                    <h3 class="section-title">Profile Information</h3>
                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" value="{{ $admin->name }}" class="form-input" required>
                            @error('name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" value="{{ $admin->email }}" class="form-input bg-gray-100" readonly disabled>
                            <p class="text-xs text-gray-500 mt-1">Email cannot be changed</p>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Role</label>
                            <input type="text" value="{{ ucfirst($admin->role) }}" class="form-input bg-gray-100" readonly disabled>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <span class="badge-role">{{ ucfirst($admin->status) }}</span>
                        </div>

                        <button type="submit" class="btn-primary">
                            <span class="material-icons">save</span>
                            Update Profile
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('avatar').addEventListener('change', function() {
            if (this.files && this.files[0]) {
                this.form.submit();
            }
        });

        @if(session('success'))
            alert('{{ session('success') }}');
        @endif
    </script>

    @push('scripts')
    <script src="//unpkg.com/alpinejs" defer></script>
    @endpush
</x-admin-layout>

<x-admin-layout title="Customer Detail - {{ $customer->name }}">
    @push('styles')
    <style>
        .customer-detail-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            color: #374151;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            margin-bottom: 24px;
        }

        .back-button:hover {
            background: #e5e7eb;
            border-color: #d1d5db;
        }

        .customer-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .customer-header {
            background: linear-gradient(135deg, #0a1d37 0%, #1a3a52 100%);
            padding: 40px 32px;
            text-align: center;
            color: white;
        }

        .customer-avatar-large {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin: 0 auto 20px;
            border: 4px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            background: rgba(255, 255, 255, 0.1);
        }

        .customer-avatar-large img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .customer-avatar-placeholder-large {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            font-weight: 700;
            color: white;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        }

        .customer-name {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .customer-email {
            font-size: 16px;
            opacity: 0.9;
        }

        .customer-body {
            padding: 32px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #e5e7eb;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 32px;
        }

        .detail-item {
            padding: 16px;
            background: #f9fafb;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }

        .detail-label {
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .detail-value {
            font-size: 15px;
            color: #111827;
            font-weight: 500;
            word-break: break-word;
        }

        .detail-item-full {
            grid-column: 1 / -1;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            background: #d1fae5;
            color: #065f46;
        }

        .action-buttons-detail {
            display: flex;
            gap: 12px;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid #e5e7eb;
        }

        .btn-action {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: #0a1d37;
            color: white;
        }

        .btn-primary:hover {
            background: #1a3a52;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(10, 29, 55, 0.3);
        }

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .export-buttons-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 32px;
        }

        .btn-export {
            background: #f3f4f6;
            color: #374151;
            border: 2px solid #e5e7eb;
        }

        .btn-export:hover {
            background: #e5e7eb;
            border-color: #d1d5db;
        }

        .btn-export-pdf:hover {
            background: #fee2e2;
            border-color: #fca5a5;
            color: #991b1b;
        }

        .btn-export-excel:hover {
            background: #dcfce7;
            border-color: #86efac;
            color: #166534;
        }

        @media (max-width: 768px) {
            .detail-grid {
                grid-template-columns: 1fr;
            }

            .action-buttons-detail {
                flex-direction: column;
            }

            .export-buttons-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    @endpush

    <div class="customer-detail-container">
        <!-- Back Button -->
        <a href="{{ route('admin.management-users') }}" class="back-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to User Management
        </a>

        <!-- Customer Card -->
        <div class="customer-card">
            <!-- Header with Avatar -->
            <div class="customer-header">
                <div class="customer-avatar-large">
                    @if($customer->avatar)
                        <img src="{{ asset('storage/' . $customer->avatar) }}" alt="{{ $customer->name }}">
                    @else
                        <div class="customer-avatar-placeholder-large">
                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <h1 class="customer-name">{{ $customer->name }}</h1>
                <p class="customer-email">{{ $customer->email }}</p>
            </div>

            <!-- Body with Details -->
            <div class="customer-body">
                <!-- Contact Information -->
                <h2 class="section-title">Contact Information</h2>
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-label">Email Address</div>
                        <div class="detail-value">{{ $customer->email }}</div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Phone Number</div>
                        <div class="detail-value">{{ $customer->phone ?? '-' }}</div>
                    </div>
                </div>

                <!-- Address Information -->
                <h2 class="section-title">Address Information</h2>
                <div class="detail-grid">
                    <div class="detail-item detail-item-full">
                        <div class="detail-label">Full Address</div>
                        <div class="detail-value">{{ $customer->address ?? '-' }}</div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Province</div>
                        <div class="detail-value">{{ $customer->province ?? '-' }}</div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">City</div>
                        <div class="detail-value">{{ $customer->city ?? '-' }}</div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">District</div>
                        <div class="detail-value">{{ $customer->district ?? '-' }}</div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Postal Code</div>
                        <div class="detail-value">{{ $customer->postal_code ?? '-' }}</div>
                    </div>
                </div>

                <!-- Account Information -->
                <h2 class="section-title">Account Information</h2>
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-label">Customer ID</div>
                        <div class="detail-value">#{{ str_pad($customer->id, 6, '0', STR_PAD_LEFT) }}</div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Status</div>
                        <div class="detail-value">
                            <span class="status-badge">Active</span>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Joined Date</div>
                        <div class="detail-value">{{ $customer->created_at->format('d F Y, H:i') }}</div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Last Updated</div>
                        <div class="detail-value">{{ $customer->updated_at->format('d F Y, H:i') }}</div>
                    </div>
                </div>

                <!-- Export Buttons -->
                <h2 class="section-title">Export Customer Data</h2>
                <div class="export-buttons-grid">
                    <a href="{{ route('admin.customer-export-pdf', $customer->id) }}" class="btn-action btn-export btn-export-pdf">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        Export to PDF
                    </a>

                    <a href="{{ route('admin.customer-export-excel', $customer->id) }}" class="btn-action btn-export btn-export-excel">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                        </svg>
                        Export to Excel
                    </a>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons-detail">
                    <button onclick="viewHistory('customer', {{ $customer->id }})" class="btn-action btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        View Activity History
                    </button>

                    <button onclick="deleteUser('customer', {{ $customer->id }})" class="btn-action btn-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                        Delete Customer
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    @vite('resources/js/admin/user-management.js')
    @endpush
</x-admin-layout>

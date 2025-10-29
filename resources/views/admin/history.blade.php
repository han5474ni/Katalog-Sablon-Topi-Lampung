<x-admin-layout title="History">
    @push('styles')
    @vite(['resources/css/admin/user-management.css', 'resources/css/admin/history.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    @endpush

    <div class="history-page-wrapper">
        <!-- Toolbar Card -->
        <div class="history-card">
            <div class="history-header-section">
                <div class="history-toolbar">
                    <div class="history-tabs">
                    <a href="{{ route('admin.history') }}" class="btn-tab {{ request('entity') ? '' : 'btn-tab-active' }}">Semua</a>
                    <a href="{{ route('admin.history', ['entity' => 'order']) }}" class="btn-tab {{ request('entity')==='order' ? 'btn-tab-active' : '' }}">Order</a>
                    <a href="{{ route('admin.history', ['entity' => 'product']) }}" class="btn-tab {{ request('entity')==='product' ? 'btn-tab-active' : '' }}">Produk</a>
                    <a href="{{ route('admin.history', ['entity' => 'user']) }}" class="btn-tab {{ request('entity')==='user' ? 'btn-tab-active' : '' }}">User</a>
                    <a href="{{ route('admin.history', ['entity' => 'chatbot']) }}" class="btn-tab {{ request('entity')==='chatbot' ? 'btn-tab-active' : '' }}">Chatbot</a>
                    <a href="{{ route('admin.history', ['entity' => 'login']) }}" class="btn-tab {{ request('entity')==='login' ? 'btn-tab-active' : '' }}">Login</a>
                    </div>

                    <form method="get" action="{{ route('admin.history') }}" class="history-search">
                        @if(request('entity'))
                            <input type="hidden" name="entity" value="{{ request('entity') }}">
                        @endif
                        <div class="search-input-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            <input name="q" value="{{ request('q') }}" placeholder="Cari nama produk">
                        </div>
                        <select name="range" class="range-select" onchange="this.form.submit()">
                            <option value="30" selected>Rentang : 30 hari</option>
                            <option value="7">Rentang : 7 hari</option>
                            <option value="60">Rentang : 60 hari</option>
                            <option value="90">Rentang : 90 hari</option>
                        </select>
                    </form>
                </div>
            </div>

        <!-- Table Card -->
        <div class="history-card">
            <div class="card-header">
                <h3 class="card-title">History Logs</h3>
                <a href="{{ route('admin.activity-logs.export') }}" class="btn-export">
                    <i class="fas fa-file-excel"></i>
                    <span>Export Excel</span>
                </a>
            </div>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr class="table-header-row">
                            <th class="table-header-cell">Waktu</th>
                            <th class="table-header-cell">Tipe</th>
                            <th class="table-header-cell">Objek</th>
                            <th class="table-header-cell">Pengguna</th>
                            <th class="table-header-cell">Detail</th>
                            <th class="table-header-cell">Status</th>
                            <th class="table-header-cell">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-body">
                        @forelse($logs as $log)
                        @php
                            $isAdmin = $log->user_type === 'App\\Models\\Admin';
                            $pengguna = $isAdmin ? (App\Models\Admin::find($log->user_id)->name ?? 'Unknown Admin') : (App\Models\User::find($log->user_id)->name ?? 'Unknown User');
                            $tipe = class_basename($log->subject_type ?? $log->user_type);
                            $objek = ($log->subject_id ?? '-') . (isset($log->properties['code']) ? ' ' . $log->properties['code'] : '');
                            $status = $log->properties['status'] ?? ($log->action === 'login' ? 'success' : 'success');
                        @endphp
                        <tr class="table-row">
                            <td class="table-cell">{{ $log->created_at->format('Y-m-d H:i') }}</td>
                            <td class="table-cell">{{ $tipe }}</td>
                            <td class="table-cell">{{ $objek }}</td>
                            <td class="table-cell">{{ $pengguna }}</td>
                            <td class="table-cell">{{ $log->description }}</td>
                            <td class="table-cell">
                                <span class="badge {{ $status==='failed' ? 'badge-red' : 'badge-green' }}">{{ ucfirst($status) }}</span>
                            </td>
                            <td class="table-cell">
                                <a href="#" class="btn-small-secondary">Detail</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="table-cell table-empty-state">
                                <p class="empty-text">Belum ada histori.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="history-pagination-footer">
                <span class="pagination-info">Menampilkan <span class="highlight">{{ $logs->firstItem() ?? 0 }}</span> dari <span class="highlight">{{ $logs->total() }}</span> item</span>
                <div class="page-selector">
                    <span>Halaman</span>
                    <select class="page-dropdown">
                        @for($i = 1; $i <= $logs->lastPage(); $i++)
                            <option value="{{ $i }}" {{ $logs->currentPage() == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    @endpush
</x-admin-layout>
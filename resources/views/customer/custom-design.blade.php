 <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Design - Order List</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/customer/shared.css', 'resources/css/guest/custom-design.css', 'resources/css/components/footer.css', 'resources/css/app.css'])
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    @php
        $selectedName = request('name') ?: 'One Life Graphic T-shirt';
        $selectedImage = request('image') ?: 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=300&h=300&fit=crop';
        $estimatedPrice = request('price') ?: '250.000';
    @endphp

    <x-navbar />

    <div class="flex flex-1 min-h-screen">
        <x-customer-sidebar active="custom-design" />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-h-screen overflow-hidden">
            <x-customer-header title="Custom Design" />

            <main class="flex-1 overflow-y-auto min-h-0">
                <div class="main-content">
                    <div class="breadcrumb">
                        <a href="{{ url()->previous() }}">
                            < Kembali ke halaman sebelumnya
                        </a>
                    </div>

                    <div class="content-card">
                        <div class="guide-section">
                            <p class="guide-title">Panduan cetak</p>
                            <h2 class="print-area-title">AREA CETAK</h2>

                            <div class="area-image-container">
                                <img src="https://s.alicdn.com/@sc04/kf/H18f96e8d35554c9e96dcd6ebff3676096/223366470/H18f96e8d35554c9e96dcd6ebff3676096.png" alt="Area Cetak" class="area-image">
                            </div>
                        </div>

                        <div class="divider"></div>

                        <!-- Product Section -->
                        <div class="product-section">
                            <p class="section-label">Item yang dipilih:</p>
                            <h2 class="product-title">{{ $selectedName }}</h2>

                            <div class="product-display">
                                <img src="{{ $selectedImage }}" alt="{{ $selectedName }}" class="product-image" onerror="this.src='https://via.placeholder.com/300x300/ffffff/333333?text=PREVIEW'">
                                <a href="{{ request('preview_url', '#') }}" class="preview-link">Lihat preview 🔍</a>
                            </div>

                            <!-- Upload Bagian Dropdown -->
                            <div class="dropdown-section">
                                <div class="dropdown-header active" onclick="toggleDropdown('uploadBagian')">
                                    <span>Upload Bagian</span>
                                    <span class="dropdown-toggle">▼</span>
                                </div>
                                <div class="dropdown-content active" id="uploadBagian">
                                    <div class="dropdown-item option-item" data-option-group="uploadBagian" data-option-value="A (Dada depan horizontal, uk. A4)">
                                        <span>A (Dada depan horizontal, uk. A4)</span>
                                        <button type="button" class="add-btn" aria-label="Tambah A (Dada depan horizontal, uk. A4)">+</button>
                                    </div>
                                    <div class="dropdown-item option-item" data-option-group="uploadBagian" data-option-value="B (Gambar kantong kiri, uk. 10x10 cm)">
                                        <span>B (Gambar kantong kiri, uk. 10x10 cm)</span>
                                        <button type="button" class="add-btn" aria-label="Tambah B (Gambar kantong kiri, uk. 10x10 cm)">+</button>
                                    </div>
                                    <div class="dropdown-item option-item" data-option-group="uploadBagian" data-option-value="C (Dada siku kanan, uk. 10x10 cm)">
                                        <span>C (Dada siku kanan, uk. 10x10 cm)</span>
                                        <button type="button" class="add-btn" aria-label="Tambah C (Dada siku kanan, uk. 10x10 cm)">+</button>
                                    </div>
                                    <div class="dropdown-item option-item" data-option-group="uploadBagian" data-option-value="D (Dada depan vertikal, uk. A4)">
                                        <span>D (Dada depan vertikal, uk. A4)</span>
                                        <button type="button" class="add-btn" aria-label="Tambah D (Dada depan vertikal, uk. A4)">+</button>
                                    </div>
                                    <div class="dropdown-item option-item" data-option-group="uploadBagian" data-option-value="E (Punggung belakang vertikal, uk. A4)">
                                        <span>E (Punggung belakang vertikal, uk. A4)</span>
                                        <button type="button" class="add-btn" aria-label="Tambah E (Punggung belakang vertikal, uk. A4)">+</button>
                                    </div>
                                    <div class="dropdown-item option-item" data-option-group="uploadBagian" data-option-value="F (Punggung siku kanan, uk. 10x10 cm)">
                                        <span>F (Punggung siku kanan, uk. 10x10 cm)</span>
                                        <button type="button" class="add-btn" aria-label="Tambah F (Punggung siku kanan, uk. 10x10 cm)">+</button>
                                    </div>
                                    <div class="dropdown-item option-item" data-option-group="uploadBagian" data-option-value="G (Dada depan horizontal, uk. A3)">
                                        <span>G (Dada depan horizontal, uk. A3)</span>
                                        <button type="button" class="add-btn" aria-label="Tambah G (Dada depan horizontal, uk. A3)">+</button>
                                    </div>
                                    <div class="dropdown-item option-item" data-option-group="uploadBagian" data-option-value="H (Dada depan ver sisi, uk. A3)">
                                        <span>H (Dada depan ver sisi, uk. A3)</span>
                                        <button type="button" class="add-btn" aria-label="Tambah H (Dada depan ver sisi, uk. A3)">+</button>
                                    </div>
                                    <div class="dropdown-item option-item" data-option-group="uploadBagian" data-option-value="I (Punggung belakang horizontal, uk. A4)">
                                        <span>I (Punggung belakang horizontal, uk. A4)</span>
                                        <button type="button" class="add-btn" aria-label="Tambah I (Punggung belakang horizontal, uk. A4)">+</button>
                                    </div>
                                    <div class="dropdown-item option-item" data-option-group="uploadBagian" data-option-value="J (Punggung belakang horizontal, uk. A3)">
                                        <span>J (Punggung belakang horizontal, uk. A3)</span>
                                        <button type="button" class="add-btn" aria-label="Tambah J (Punggung belakang horizontal, uk. A3)">+</button>
                                    </div>
                                </div>
                            </div>
                            <input type="file" id="uploadBagianFileInput" accept="image/*" hidden>
                            <div id="uploaded-bagian-list" style="margin: 12px 0 20px 0;"></div>

                            <!-- Jenis Cutting Dropdown -->
                            <div class="dropdown-section">
                                <div class="dropdown-header" onclick="toggleDropdown('cutting')">
                                    <span>Jenis Cutting</span>
                                    <span class="dropdown-toggle">▼</span>
                                </div>
                                <div class="dropdown-content" id="cutting">
                                    <div class="dropdown-item" onclick="selectOption('cutting', 'Cutting PVC Flex', this)">
                                        <span>Cutting PVC Flex</span>
                                    </div>
                                    <div class="dropdown-item" onclick="selectOption('cutting', 'Printable', this)">
                                        <span>Printable</span>
                                    </div>
                                    <div class="dropdown-item" onclick="selectOption('cutting', 'Direct Transfer Film', this)">
                                        <span>Direct Transfer Film</span>
                                    </div>
                                </div>
                            </div>
                            <div class="selected-items" id="selected-cutting"></div>

                            <!-- Special Material Dropdown -->
                            <div class="dropdown-section">
                                <div class="dropdown-header" onclick="toggleDropdown('material')">
                                    <span>Special Material (Add On)</span>
                                    <span class="dropdown-toggle">▼</span>
                                </div>
                                <div class="dropdown-content" id="material">
                                    <div class="dropdown-item" onclick="selectOption('material', 'Foil', this)">
                                        <span>Foil</span>
                                    </div>
                                    <div class="dropdown-item" onclick="selectOption('material', 'Glitter', this)">
                                        <span>Glitter</span>
                                    </div>
                                    <div class="dropdown-item" onclick="selectOption('material', 'Spectrum', this)">
                                        <span>Spectrum</span>
                                    </div>
                                    <div class="dropdown-item" onclick="selectOption('material', 'Reflective', this)">
                                        <span>Reflective</span>
                                    </div>
                                    <div class="dropdown-item" onclick="selectOption('material', 'Flock', this)">
                                        <span>Flock</span>
                                    </div>
                                    <div class="dropdown-item" onclick="selectOption('material', 'Glow', this)">
                                        <span>Glow</span>
                                    </div>
                                </div>
                            </div>
                            <div class="selected-items" id="selected-material"></div>

                            <!-- Description Box -->
                            <div class="description-box">
                                <div class="description-label">Deskripsi tambahan:</div>
                                <textarea class="description-text" name="additional_description" placeholder="Tuliskan deskripsi tambahan di sini" rows="4"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="divider"></div>

                    <!-- Footer with Price and Buy Button -->
                    <div class="footer-section">
                        <div class="total-price">
                            Harga Total: <span class="total-amount">Rp {{ $estimatedPrice }}</span>
                        </div>
                        <button class="buy-button" onclick="buyNow()">Beli sekarang!</button>
                    </div>

                    <a href="{{ route('home') }}" class="keluar-btn">Keluar</a>
                </div>
            </main>
        </div>
    </div>

    <!-- Success Notification Modal -->
    <div id="successModal" class="fixed inset-0 hidden items-center justify-center z-50">
        <div class="absolute inset-0 bg-black/40" onclick="closeSuccessModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-xl max-w-xl w-full mx-4 p-10 text-center">
            <div class="mx-auto mb-6 w-20 h-20 rounded-full border-4 border-navy-900 text-navy-900 flex items-center justify-center text-4xl font-bold">✓</div>
            <h2 class="text-2xl font-extrabold text-navy-900 mb-2">Pesanan sudah masuk!</h2>
            <p class="text-gray-600">Silahkan cek notifikasi untuk pemberitahuan lebih lanjut</p>
            <button class="mt-10 bg-yellow-400 hover:bg-yellow-500 text-navy-900 font-bold px-8 py-3 rounded-full" onclick="goToHome()">Kembali ke beranda</button>
        </div>
    </div>

    <x-guest-footer />

    <script>
        // Store selected options
        const selectedOptions = {
            cutting: [],
            material: []
        };

        function toggleDropdown(id) {
            const content = document.getElementById(id);
            const header = content ? content.previousElementSibling : null;
            if (content && header) {
                const willActivate = !content.classList.contains('active');
                content.classList.toggle('active', willActivate);
                header.classList.toggle('active', willActivate);
            }
        }
        function closeDropdown(id) {
            const content = document.getElementById(id);
            const header = content ? content.previousElementSibling : null;
            if (content && header) {
                content.classList.remove('active');
                header.classList.remove('active');
            }
        }

        function selectOption(type, value, element) {
            // Single select for cutting
            if (type === 'cutting') {
                selectedOptions[type] = [value];
                // Visually mark active in list
                document.querySelectorAll(`#${type} .dropdown-item`).forEach(it => it.classList.remove('option-item-active'));
                element.classList.add('option-item-active');
                // Close after select
                closeDropdown(type);
            } else {
                // Multi select for material
                const index = selectedOptions[type].indexOf(value);
                if (index > -1) {
                    selectedOptions[type].splice(index, 1);
                    element.classList.remove('option-item-active');
                } else {
                    selectedOptions[type].push(value);
                    element.classList.add('option-item-active');
                }
            }
            updateSelectedDisplay(type);
            // Always close the dropdown after a selection
            closeDropdown(type);
        }

        function updateSelectedDisplay(type) {
            const container = document.getElementById(`selected-${type}`);
            if (!container) return;
            container.innerHTML = '';
            selectedOptions[type].forEach(option => {
                const badge = document.createElement('div');
                badge.className = 'selected-badge selection-item';
                badge.innerHTML = `
                    <span class="selection-text">${option}</span>
                    <button type="button" class="remove-badge" onclick="removeOption('${type}', '${option}')">×</button>
                `;
                container.appendChild(badge);
            });
        }

        function removeOption(type, value) {
            const index = selectedOptions[type].indexOf(value);
            if (index > -1) {
                selectedOptions[type].splice(index, 1);
                updateSelectedDisplay(type);
                // Also unhighlight in list
                const item = document.querySelector(`#${type} .dropdown-item span:nth-child(1)`) // placeholder
            }
            // Unmark active item in the list
            document.querySelectorAll(`#${type} .dropdown-item`).forEach(it => {
                const text = it.querySelector('span')?.textContent?.trim();
                if (text === value) it.classList.remove('option-item-active');
            });
        }

        function buyNow() {
            const orderData = {
                cutting: selectedOptions.cutting,
                materials: selectedOptions.material
            };
            console.log('Order data:', orderData);
            // Show success modal
            const modal = document.getElementById('successModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeSuccessModal() {
            const modal = document.getElementById('successModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        function goToHome() {
            window.location.href = '/';
        }
    // Upload Bagian logic
        const uploadedSections = new Map();
        let pendingUploadSection = null;

        // bind add buttons in Upload Bagian
        document.querySelectorAll('#uploadBagian .add-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const item = btn.closest('.dropdown-item');
                const label = item?.querySelector('span')?.textContent?.trim() || '';
                if (!label) return;
                pendingUploadSection = { label, button: btn };
                document.getElementById('uploadBagianFileInput').click();
            });
        });

        // handle file pick
        const uploadInput = document.getElementById('uploadBagianFileInput');
        uploadInput.addEventListener('change', () => {
            const file = uploadInput.files && uploadInput.files[0];
            if (!file || !pendingUploadSection) return;
            if (!file.type.startsWith('image/')) {
                alert('Hanya file gambar yang diperbolehkan.');
                uploadInput.value = '';
                pendingUploadSection = null;
                return;
            }
            // Save
            uploadedSections.set(pendingUploadSection.label, { file, name: file.name });
            // Mark button
            pendingUploadSection.button.classList.add('option-select-active');
            // Render
            renderUploadedList();
            // Close the upload bagian dropdown
            closeDropdown('uploadBagian');
            // Reset
            uploadInput.value = '';
            pendingUploadSection = null;
        });

        function renderUploadedList() {
            const container = document.getElementById('uploaded-bagian-list');
            if (!container) return;
            container.innerHTML = '';
            uploadedSections.forEach(({ name }, label) => {
                const row = document.createElement('div');
                row.className = 'file-uploaded uploaded-item';
                row.dataset.sectionLabel = label;
                row.innerHTML = `
                    <span><strong>${label}:</strong> ${name}</span>
                    <button type="button" class="remove-file" aria-label="Hapus file">⊗</button>
                `;
                row.querySelector('.remove-file').addEventListener('click', () => {
                    uploadedSections.delete(label);
                    // unmark the + button for this label
                    const item = [...document.querySelectorAll('#uploadBagian .dropdown-item')]
                        .find(it => it.querySelector('span')?.textContent?.trim() === label);
                    const addBtn = item?.querySelector('.add-btn');
                    if (addBtn) addBtn.classList.remove('option-select-active');
                    renderUploadedList();
                });
                container.appendChild(row);
            });
        }
    </script>
</body>

/**
 * Customer Cart Page - JavaScript
 * Handles cart functionality: add, remove, update quantity, coupon, checkout
 */

// Format IDR currency
const formatIDR = (n) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0
    }).format(n || 0);
};

// Cart state
let cartItems = [];
let appliedCoupon = null;
let shippingMethod = 'STANDARD';

// Initialize cart on page load
document.addEventListener('DOMContentLoaded', () => {
    loadCartFromStorage();
    initializeEventListeners();
    renderCart();
});

// Load cart from localStorage
function loadCartFromStorage() {
    const stored = localStorage.getItem('cart');
    if (stored) {
        try {
            cartItems = JSON.parse(stored);
        } catch (e) {
            cartItems = getMockData();
        }
    } else {
        cartItems = getMockData();
    }
}

// Save cart to localStorage
function saveCartToStorage() {
    localStorage.setItem('cart', JSON.stringify(cartItems));
}

// Mock data for demo
function getMockData() {
    return [
        {
            id: 1,
            name: 'Kaos Sablon Custom Premium',
            variant: 'Hitam / L',
            price: 85000,
            qty: 2,
            image: 'https://picsum.photos/seed/topi1/120/120',
            checked: false
        },
        {
            id: 2,
            name: 'Topi Baseball Bordir',
            variant: 'Navy / All Size',
            price: 65000,
            qty: 1,
            image: 'https://picsum.photos/seed/topi2/120/120',
            checked: false
        }
    ];
}

// Initialize event listeners
function initializeEventListeners() {
    // Select all checkbox
    document.getElementById('select-all')?.addEventListener('change', (e) => {
        const checked = e.target.checked;
        cartItems = cartItems.map(item => ({ ...item, checked }));
        renderCart();
    });

    // Delete selected button
    document.getElementById('delete-selected-btn')?.addEventListener('click', () => {
        if (confirm('Hapus item yang dipilih?')) {
            cartItems = cartItems.filter(item => !item.checked);
            saveCartToStorage();
            renderCart();
        }
    });

    // Clear cart button
    document.getElementById('clear-cart-btn')?.addEventListener('click', () => {
        if (confirm('Kosongkan seluruh keranjang?')) {
            cartItems = [];
            saveCartToStorage();
            renderCart();
        }
    });

    // Apply coupon
    document.getElementById('apply-coupon-btn')?.addEventListener('click', applyCoupon);

    // Shipping method
    document.querySelectorAll('input[name="shipping"]').forEach(radio => {
        radio.addEventListener('change', (e) => {
            shippingMethod = e.target.value;
            renderSummary();
        });
    });

    // Checkout button
    document.getElementById('checkout-btn')?.addEventListener('click', handleCheckout);
}

// Render cart
function renderCart() {
    const isEmpty = cartItems.length === 0;
    
    // Toggle empty state
    document.getElementById('empty-cart').classList.toggle('hidden', !isEmpty);
    document.getElementById('cart-content').classList.toggle('hidden', isEmpty);
    document.getElementById('clear-cart-btn').classList.toggle('hidden', isEmpty);

    if (!isEmpty) {
        renderCartItems();
        renderSummary();
        updateSelectAllState();
    }
}

// Render cart items
function renderCartItems() {
    const container = document.getElementById('cart-items');
    const itemCount = document.getElementById('item-count');
    const deleteBtn = document.getElementById('delete-selected-btn');

    const anyChecked = cartItems.some(item => item.checked);
    deleteBtn.classList.toggle('hidden', !anyChecked);
    itemCount.classList.toggle('hidden', anyChecked);
    itemCount.textContent = `${cartItems.length} item`;

    container.innerHTML = cartItems.map(item => `
        <li class="grid grid-cols-12 items-center gap-3 p-4">
            <!-- Checkbox + Image + Info -->
            <div class="col-span-12 flex items-center gap-3 md:col-span-6">
                <input 
                    type="checkbox" 
                    ${item.checked ? 'checked' : ''} 
                    onchange="toggleItem(${item.id}, this.checked)"
                    class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400"
                >
                <img src="${item.image}" alt="${item.name}" class="h-16 w-16 rounded-xl object-cover">
                <div>
                    <div class="font-medium text-slate-900">${item.name}</div>
                    <div class="text-xs text-slate-500">${item.variant}</div>
                    <button 
                        onclick="removeItem(${item.id})" 
                        class="mt-1 text-xs text-rose-600 hover:underline"
                    >
                        Hapus
                    </button>
                </div>
            </div>

            <!-- Quantity -->
            <div class="col-span-6 flex items-center gap-3 md:col-span-3 md:justify-center">
                <div class="inline-flex items-center rounded-xl border border-slate-200 bg-white">
                    <button 
                        onclick="updateQuantity(${item.id}, ${item.qty - 1})" 
                        class="px-2 py-1 text-slate-600 hover:bg-slate-50"
                    >-</button>
                    <input 
                        type="number" 
                        value="${item.qty}" 
                        onchange="updateQuantity(${item.id}, parseInt(this.value))"
                        class="w-12 px-2 py-1 text-center text-sm outline-none" 
                        min="1" 
                        max="99"
                    >
                    <button 
                        onclick="updateQuantity(${item.id}, ${item.qty + 1})" 
                        class="px-2 py-1 text-slate-600 hover:bg-slate-50"
                    >+</button>
                </div>
            </div>

            <!-- Price -->
            <div class="col-span-6 text-right md:col-span-3">
                <div class="text-sm font-semibold text-slate-900">${formatIDR(item.price * item.qty)}</div>
                <div class="text-xs text-slate-500">${formatIDR(item.price)} / item</div>
            </div>
        </li>
    `).join('');
}

// Toggle item selection
window.toggleItem = (id, checked) => {
    cartItems = cartItems.map(item => 
        item.id === id ? { ...item, checked } : item
    );
    renderCart();
};

// Update quantity
window.updateQuantity = (id, qty) => {
    if (qty < 1 || qty > 99) return;
    cartItems = cartItems.map(item => 
        item.id === id ? { ...item, qty } : item
    );
    saveCartToStorage();
    renderCart();
};

// Remove item
window.removeItem = (id) => {
    if (confirm('Hapus item ini dari keranjang?')) {
        cartItems = cartItems.filter(item => item.id !== id);
        saveCartToStorage();
        renderCart();
    }
};

// Update select all state
function updateSelectAllState() {
    const selectAll = document.getElementById('select-all');
    if (selectAll) {
        selectAll.checked = cartItems.length > 0 && cartItems.every(item => item.checked);
    }
}

// Apply coupon
function applyCoupon() {
    const input = document.getElementById('coupon-input');
    const code = input.value.trim().toUpperCase();
    
    if (!code) return;

    // Mock coupon validation
    if (code === 'HEMAT10') {
        appliedCoupon = { code, type: 'percent', value: 10 };
        showCouponBadge(code);
    } else if (code === 'POTONG5000') {
        appliedCoupon = { code, type: 'flat', value: 5000 };
        showCouponBadge(code);
    } else {
        alert('Kode kupon tidak valid');
        appliedCoupon = null;
        hideCouponBadge();
    }
    
    renderSummary();
}

// Show coupon badge
function showCouponBadge(code) {
    const badge = document.getElementById('coupon-badge');
    const codeSpan = document.getElementById('coupon-code');
    if (badge && codeSpan) {
        codeSpan.textContent = code;
        badge.classList.remove('hidden');
    }
}

// Hide coupon badge
function hideCouponBadge() {
    const badge = document.getElementById('coupon-badge');
    if (badge) {
        badge.classList.add('hidden');
    }
}

// Render summary
function renderSummary() {
    const subtotal = cartItems.reduce((sum, item) => sum + (item.price * item.qty), 0);
    
    let discount = 0;
    if (appliedCoupon) {
        if (appliedCoupon.type === 'percent') {
            discount = Math.floor(subtotal * appliedCoupon.value / 100);
        } else if (appliedCoupon.type === 'flat') {
            discount = appliedCoupon.value;
        }
    }

    const taxable = Math.max(0, subtotal - discount);
    const tax = Math.floor(taxable * 0.11); // PPN 11%
    const shippingFee = cartItems.length > 0 ? (shippingMethod === 'STANDARD' ? 15000 : 30000) : 0;
    const total = Math.max(0, taxable + tax + shippingFee);

    document.getElementById('subtotal').textContent = formatIDR(subtotal);
    document.getElementById('discount').textContent = `-${formatIDR(discount)}`;
    document.getElementById('tax').textContent = formatIDR(tax);
    document.getElementById('total').textContent = formatIDR(total);

    // Enable/disable checkout button
    const checkoutBtn = document.getElementById('checkout-btn');
    if (checkoutBtn) {
        checkoutBtn.disabled = cartItems.length === 0;
    }
}

// Handle checkout
function handleCheckout() {
    if (cartItems.length === 0) return;
    
    // Mock checkout - replace with actual checkout flow
    alert('Lanjut ke halaman checkout (dalam pengembangan)');
    
    // You can redirect to checkout page:
    // window.location.href = '/checkout';
}

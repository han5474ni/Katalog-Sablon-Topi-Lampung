// Close banner
const closeBannerBtn = document.querySelector('.close-btn');
const topBanner = document.querySelector('.top-banner');

if (closeBannerBtn && topBanner) {
    closeBannerBtn.addEventListener('click', function() {
        topBanner.style.display = 'none';
    });
}

// Size selection
document.querySelectorAll('.size-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
    });
});

// Color selection
document.querySelectorAll('.color-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.color-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
    });
});

// Jersey option selection
document.querySelectorAll('.jersey-option').forEach(option => {
    option.addEventListener('click', function() {
        document.querySelectorAll('.jersey-option').forEach(o => o.classList.remove('active'));
        this.classList.add('active');
    });
});

// Profile popup
const profileIcon = document.getElementById('profile-icon');
const profilePopup = document.getElementById('profile-popup');

// Function to toggle popup
function toggleProfilePopup() {
    if (!profilePopup) return;

    const isAuthenticated = profilePopup.dataset.auth === 'true';

    if (!isAuthenticated && profilePopup.dataset.loginUrl) {
        window.location.href = profilePopup.dataset.loginUrl;
        return;
    }

    const isVisible = profilePopup.style.display === 'block';
    profilePopup.style.display = isVisible ? 'none' : 'block';
}

// Function to close popup
function closeProfilePopup() {
    if (!profilePopup) return;
    profilePopup.style.display = 'none';
}

// Toggle popup when clicking profile icon
if (profileIcon) {
    profileIcon.addEventListener('click', function(e) {
        e.stopPropagation();
        toggleProfilePopup();
    });
}

// Close popup when clicking outside
if (profilePopup) {
    document.addEventListener('click', function(e) {
        if (profilePopup.style.display === 'block') {
            if (!profilePopup.contains(e.target) && e.target !== profileIcon) {
                closeProfilePopup();
            }
        }
    });

    // Prevent popup from closing when clicking inside it
    profilePopup.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    // Close popup on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && profilePopup.style.display === 'block') {
            closeProfilePopup();
        }
    });
}

// Product card click handler - redirect to detail page
// Using event delegation to handle both original and cloned cards
document.addEventListener('click', function(e) {
    const productCard = e.target.closest('.product-card');

    if (productCard) {
        const productId = productCard.getAttribute('data-product-id');
        const productName = productCard.getAttribute('data-product-name');
        const productPrice = productCard.getAttribute('data-product-price');
        const productImage = productCard.getAttribute('data-product-image');

        if (productId && productName && productPrice && productImage) {
            window.location.href = `/public/detail?id=${productId}&name=${encodeURIComponent(productName)}&price=${productPrice}&image=${encodeURIComponent(productImage)}`;
        }
    }
});

// Chat popup functionality
const chatBtn = document.getElementById('chat-btn');
const chatPopup = document.getElementById('chat-popup');
const chatPopupClose = document.querySelector('.chat-popup-close');
const chatPopupOverlay = document.querySelector('.chat-popup-overlay');

// Function to show chat popup
function showChatPopup() {
    if (chatPopup) {
        chatPopup.classList.add('show');
        document.body.style.overflow = 'hidden'; // Prevent background scroll
    }
}

// Function to hide chat popup
function hideChatPopup() {
    if (chatPopup) {
        chatPopup.classList.remove('show');
        document.body.style.overflow = ''; // Restore scroll
    }
}

// Chat button click handler - show popup
if (chatBtn) {
    chatBtn.addEventListener('click', function(e) {
        e.preventDefault();
        showChatPopup();
    });
}

// Close popup when clicking close button
if (chatPopupClose) {
    chatPopupClose.addEventListener('click', function() {
        hideChatPopup();
    });
}

// Close popup when clicking overlay
if (chatPopupOverlay) {
    chatPopupOverlay.addEventListener('click', function() {
        hideChatPopup();
    });
}

// Close popup on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && chatPopup && chatPopup.classList.contains('show')) {
        hideChatPopup();
    }
});

// Prevent popup from closing when clicking inside content
if (chatPopup) {
    const chatPopupContent = document.querySelector('.chat-popup-content');
    if (chatPopupContent) {
        chatPopupContent.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
}

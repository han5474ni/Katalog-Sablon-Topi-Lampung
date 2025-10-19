document.addEventListener('DOMContentLoaded', function() {
    // Profile popup functionality
    const profileIcon = document.getElementById('profile-icon');
    const profilePopup = document.getElementById('profile-popup');

    function toggleProfilePopup() {
        if (!profilePopup) return;

        const isAuthenticated = profilePopup.dataset.auth === 'true';

        if (!isAuthenticated) {
            window.location.href = profilePopup.dataset.loginUrl;
            return;
        }

        const isVisible = profilePopup.style.display === 'block';
        profilePopup.style.display = isVisible ? 'none' : 'block';
    }

    function closeProfilePopup() {
        if (!profilePopup) return;
        profilePopup.style.display = 'none';
    }

    if (profileIcon) {
        profileIcon.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleProfilePopup();
        });
    }

    // Close popup when clicking outside
    document.addEventListener('click', function(e) {
        if (profilePopup && profilePopup.style.display === 'block') {
            if (!profilePopup.contains(e.target) && e.target !== profileIcon) {
                closeProfilePopup();
            }
        }
    });

    // Prevent popup from closing when clicking inside it
    if (profilePopup) {
        profilePopup.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }

    // Close popup on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && profilePopup && profilePopup.style.display === 'block') {
            closeProfilePopup();
        }
    });

    // Thumbnail image switching
    const thumbnails = document.querySelectorAll('.thumbnail');
    const mainImage = document.getElementById('mainImage');

    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function() {
            // Remove active class from all thumbnails
            thumbnails.forEach(t => t.classList.remove('active'));
            
            // Add active class to clicked thumbnail
            this.classList.add('active');
            
            // Update main image
            const newImageSrc = this.getAttribute('data-image');
            mainImage.src = newImageSrc;
        });
    });

    // Color selection
    const colorOptions = document.querySelectorAll('.color-option');
    
    colorOptions.forEach(option => {
        option.addEventListener('click', function() {
            colorOptions.forEach(o => o.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Size selection
    const sizeOptions = document.querySelectorAll('.size-option');
    
    sizeOptions.forEach(option => {
        option.addEventListener('click', function() {
            sizeOptions.forEach(o => o.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Quantity controls
    const decreaseBtn = document.getElementById('decreaseQty');
    const increaseBtn = document.getElementById('increaseQty');
    const quantityInput = document.getElementById('quantity');

    if (decreaseBtn) {
        decreaseBtn.addEventListener('click', function() {
            let currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        });
    }

    if (increaseBtn) {
        increaseBtn.addEventListener('click', function() {
            let currentValue = parseInt(quantityInput.value);
            if (currentValue < 99) {
                quantityInput.value = currentValue + 1;
            }
        });
    }

    // Add to cart functionality
    const addToCartBtn = document.querySelector('.add-to-cart-btn');
    
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', function() {
            const selectedColor = document.querySelector('.color-option.active');
            const selectedSize = document.querySelector('.size-option.active');
            const quantity = quantityInput.value;

            if (!selectedColor || !selectedSize) {
                alert('Please select both color and size');
                return;
            }

            // Here you can add AJAX call to add product to cart
            alert(`Added ${quantity} item(s) to cart!\nSize: ${selectedSize.textContent}\nColor: ${selectedColor.getAttribute('data-color')}`);
        });
    }

    // Product card click handler for recommendations
    const productCards = document.querySelectorAll('.product-card');
    
    productCards.forEach(card => {
        card.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const productName = this.getAttribute('data-product-name');
            const productPrice = this.getAttribute('data-product-price');
            const productImage = this.getAttribute('data-product-image');

            // Redirect to product detail page with query parameters
            window.location.href = `/public/detail?id=${productId}&name=${encodeURIComponent(productName)}&price=${productPrice}&image=${encodeURIComponent(productImage)}`;
        });
    });

    // Chat button functionality
    const chatBtn = document.querySelector('.chat-btn');
    
    if (chatBtn) {
        chatBtn.addEventListener('click', function() {
            // Redirect to chatbot or open chat window
            window.location.href = '/chatbot';
        });
    }
});

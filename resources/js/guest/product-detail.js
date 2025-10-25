document.addEventListener('DOMContentLoaded', () => {
    const profileIcon = document.getElementById('profile-icon');
    const profilePopup = document.getElementById('profile-popup');

    function toggleProfilePopup() {
        if (!profilePopup) return;
        const isAuthenticated = profilePopup.dataset.auth === 'true';
        if (!isAuthenticated) {
            const loginUrl = profilePopup.dataset.loginUrl;
            if (loginUrl) {
                window.location.href = loginUrl;
            }
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
        profileIcon.addEventListener('click', event => {
            event.stopPropagation();
            toggleProfilePopup();
        });
    }

    document.addEventListener('click', event => {
        if (!profilePopup || profilePopup.style.display !== 'block') return;
        if (!profilePopup.contains(event.target) && event.target !== profileIcon) {
            closeProfilePopup();
        }
    });

    if (profilePopup) {
        profilePopup.addEventListener('click', event => {
            event.stopPropagation();
        });
    }

    document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && profilePopup && profilePopup.style.display === 'block') {
            closeProfilePopup();
        }
    });

    const thumbnails = document.querySelectorAll('.thumbnail');
    const mainImage = document.getElementById('mainImage');

    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', () => {
            const newImageSrc = thumbnail.dataset.image;
            if (!newImageSrc || !mainImage) return;
            thumbnails.forEach(item => {
                item.classList.remove('active');
                item.setAttribute('aria-selected', 'false');
            });
            thumbnail.classList.add('active');
            thumbnail.setAttribute('aria-selected', 'true');
            mainImage.src = newImageSrc;
        });
    });

    const colorOptions = document.querySelectorAll('.color-swatch');
    colorOptions.forEach(option => {
        option.addEventListener('click', () => {
            colorOptions.forEach(item => {
                item.classList.remove('active');
                item.setAttribute('aria-pressed', 'false');
            });
            option.classList.add('active');
            option.setAttribute('aria-pressed', 'true');
        });
    });

    const sizeOptions = document.querySelectorAll('.size-option');
    sizeOptions.forEach(option => {
        option.addEventListener('click', () => {
            sizeOptions.forEach(item => {
                item.classList.remove('active');
                item.setAttribute('aria-pressed', 'false');
            });
            option.classList.add('active');
            option.setAttribute('aria-pressed', 'true');
        });
    });

    const quantitySelector = document.querySelector('.quantity-selector');
    const quantityValue = document.getElementById('quantityValue');
    let quantity = 1;

    function updateQuantity(newQuantity) {
        quantity = Math.max(1, Math.min(newQuantity, 99));
        if (quantityValue) {
            quantityValue.textContent = quantity.toString();
        }
    }

    if (quantitySelector && quantityValue) {
        quantitySelector.addEventListener('click', event => {
            const action = event.target.dataset.quantityAction;
            if (action === 'increase') {
                updateQuantity(quantity + 1);
            } else if (action === 'decrease') {
                updateQuantity(quantity - 1);
            }
        });
    }

    const addToCartButton = document.querySelector('.add-to-cart-btn');
    if (addToCartButton) {
        addToCartButton.addEventListener('click', () => {
            const selectedColor = document.querySelector('.color-swatch.active');
            const selectedSize = document.querySelector('.size-option.active');
            const colorLabel = selectedColor ? selectedColor.dataset.color : 'Default';
            const sizeLabel = selectedSize ? selectedSize.dataset.size || selectedSize.textContent : 'Default';
            const productName = addToCartButton.dataset.productName || 'Produk';
            alert(`${productName} ditambahkan ke keranjang\nWarna: ${colorLabel}\nUkuran: ${sizeLabel}\nJumlah: ${quantity}`);
        });
    }

    const tabButtons = document.querySelectorAll('.tab-button');
    const tabPanels = document.querySelectorAll('.tab-panel');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const navigateUrl = button.dataset.navigate;
            if (navigateUrl) {
                window.location.href = navigateUrl;
                return;
            }

            const target = button.dataset.tab;
            if (!target) return;
            tabButtons.forEach(item => {
                item.classList.remove('active');
                item.setAttribute('aria-selected', 'false');
            });
            tabPanels.forEach(panel => {
                panel.classList.remove('active');
                panel.hidden = true;
            });
            button.classList.add('active');
            button.setAttribute('aria-selected', 'true');
            const activePanel = document.querySelector(`[data-tab-panel="${target}"]`);
            if (activePanel) {
                activePanel.classList.add('active');
                activePanel.hidden = false;
            }
        });
    });

    tabPanels.forEach((panel, index) => {
        if (!panel.classList.contains('active')) {
            panel.hidden = true;
        } else if (index === 0) {
            panel.hidden = false;
        }
    });

    const recommendationCards = document.querySelectorAll('.recommendation-card');
    recommendationCards.forEach(card => {
        card.addEventListener('click', () => {
            const productId = card.dataset.productId;
            const productName = card.dataset.productName;
            const productPrice = card.dataset.productPrice;
            const productImage = card.dataset.productImage;
            if (!productId || !productName || !productPrice || !productImage) return;
            const query = new URLSearchParams({
                id: productId,
                name: productName,
                price: productPrice,
                image: productImage,
            });
            window.location.href = `/public/detail?${query.toString()}`;
        });

        card.addEventListener('keydown', event => {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                card.click();
            }
        });
    });
});

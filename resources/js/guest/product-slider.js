/**
 * Product Slider - Auto-slide functionality
 * Shows 4 products at a time, slides 1 product per transition
 * Example: [1,2,3,4] → [2,3,4,5] → [3,4,5,1] etc.
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize sliders
    initSlider('arrivals-slider', 3000); // 3 seconds per slide
    initSlider('selling-slider', 3000);

    function initSlider(sliderId, autoSlideInterval) {
        const slider = document.getElementById(sliderId);
        if (!slider) return;

        const track = slider.querySelector('.slider-track');
        const originalCards = Array.from(track.querySelectorAll('.product-card'));
        
        // Clone cards for seamless infinite loop
        // Add copies at the end for smooth looping
        originalCards.forEach(card => {
            const clone = card.cloneNode(true);
            track.appendChild(clone);
        });
        
        const allCards = Array.from(track.querySelectorAll('.product-card'));
        const prevBtn = document.querySelector(`[data-slider="${sliderId.replace('-slider', '')}"].slider-prev`);
        const nextBtn = document.querySelector(`[data-slider="${sliderId.replace('-slider', '')}"].slider-next`);
        
        let currentIndex = 0;
        const cardsToShow = 4;
        const totalCards = originalCards.length;
        
        let autoSlideTimer;
        let isTransitioning = false;

        // Calculate card width including gap
        function getCardWidth() {
            if (allCards.length === 0) return 0;
            const card = allCards[0];
            const cardWidth = card.offsetWidth;
            const gap = 20; // gap from CSS
            return cardWidth + gap;
        }

        // Update slider position
        function updateSlider(instant = false) {
            const cardWidth = getCardWidth();
            const offset = -(currentIndex * cardWidth);
            
            if (instant) {
                track.style.transition = 'none';
            } else {
                track.style.transition = 'transform 0.6s ease-in-out';
            }
            
            track.style.transform = `translateX(${offset}px)`;
            
            // Buttons always enabled for infinite loop
            if (prevBtn && nextBtn) {
                prevBtn.style.opacity = '1';
                nextBtn.style.opacity = '1';
            }
        }

        // Go to next slide (move 1 card)
        function nextSlide() {
            if (isTransitioning) return;
            
            isTransitioning = true;
            currentIndex++;
            updateSlider();
            
            // Check if we need to loop back
            setTimeout(() => {
                if (currentIndex >= totalCards) {
                    // Jump to start without animation
                    currentIndex = 0;
                    updateSlider(true);
                }
                isTransitioning = false;
            }, 600); // Match transition duration
        }

        // Go to previous slide (move 1 card back)
        function prevSlide() {
            if (isTransitioning) return;
            
            isTransitioning = true;
            
            if (currentIndex === 0) {
                // Jump to end without animation
                currentIndex = totalCards;
                updateSlider(true);
                
                // Then animate back one
                setTimeout(() => {
                    currentIndex--;
                    updateSlider();
                }, 50);
            } else {
                currentIndex--;
                updateSlider();
            }
            
            setTimeout(() => {
                isTransitioning = false;
            }, 600);
        }

        // Auto slide
        function startAutoSlide() {
            autoSlideTimer = setInterval(() => {
                nextSlide();
            }, autoSlideInterval);
        }

        function stopAutoSlide() {
            clearInterval(autoSlideTimer);
        }

        function resetAutoSlide() {
            stopAutoSlide();
            startAutoSlide();
        }

        // Event listeners
        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                nextSlide();
                resetAutoSlide();
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                prevSlide();
                resetAutoSlide();
            });
        }

        // Pause auto-slide on hover
        slider.addEventListener('mouseenter', stopAutoSlide);
        slider.addEventListener('mouseleave', startAutoSlide);

        // Handle window resize
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                updateSlider();
            }, 250);
        });

        // Initialize
        updateSlider();
        startAutoSlide();

        // Handle visibility change (pause when tab is not visible)
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                stopAutoSlide();
            } else {
                startAutoSlide();
            }
        });
    }
});

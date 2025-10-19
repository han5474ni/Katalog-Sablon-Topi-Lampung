/**
 * Chatbot JavaScript
 * Handles profile avatar updates
 */

document.addEventListener('DOMContentLoaded', () => {
    // Listen for profile update events
    window.addEventListener('profile-updated', event => {
        const newAvatarUrl = event.detail.avatarUrl;
        
        // Update all avatar images
        document.querySelectorAll('.header-avatar').forEach(img => {
            img.src = newAvatarUrl;
        });
    });
});

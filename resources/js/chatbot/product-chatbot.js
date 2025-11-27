// Product ChatBot Button Handler
// Redirects to full chatbot page when button is clicked
document.addEventListener('DOMContentLoaded', function() {
    console.log('Chat button handler initialized');
    
    // Use event delegation for chat button clicks
    document.addEventListener('click', (e) => {
        if (e.target.closest('.chat-btn')) {
            console.log('Chat button clicked - redirecting to full chatbot page');
            e.preventDefault();
            e.stopPropagation();
            window.location.href = '/chatbot';
        }
    });
});

console.log('Chat button redirect script loaded');
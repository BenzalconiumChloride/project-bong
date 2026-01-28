// Toggle Chat Window
function toggleChat() {
    const chatWindow = document.getElementById('chatWindow');
    const chatBadge = document.getElementById('chatBadge');
    
    chatWindow.classList.toggle('d-none');
    
    if (!chatWindow.classList.contains('d-none')) {
        chatBadge.classList.add('d-none');
    }
}

// Send Message
function sendMessage() {
    const input = document.getElementById('chatInput');
    const messagesContainer = document.getElementById('chatMessages');
    const message = input.value.trim();

    if (message) {
        // User message
        const userMessageHTML = `
            <div class="d-flex justify-content-end mb-3">
                <div class="message-bubble user">
                    <p class="mb-0 small">${message}</p>
                </div>
            </div>
        `;
        messagesContainer.insertAdjacentHTML('beforeend', userMessageHTML);

        // Bot response
        setTimeout(() => {
            const botMessageHTML = `
                <div class="d-flex align-items-start mb-3">
                    <div class="rounded-circle p-2 me-2" style="background: var(--blue-gradient);">
                        <i class="bi bi-robot text-white"></i>
                    </div>
                    <div class="message-bubble bot">
                        <p class="mb-0 small">Thanks for your question! I'm here to help you learn. This is a demo response.</p>
                    </div>
                </div>
            `;
            messagesContainer.insertAdjacentHTML('beforeend', botMessageHTML);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }, 500);

        input.value = '';
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
}
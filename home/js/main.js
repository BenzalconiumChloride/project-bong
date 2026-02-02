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

function scrollToFeatures() {
    const heroSection = document.getElementById('hero');
    const featuresSection = document.getElementById('features');
    
    // Add parallax effect to hero
    heroSection.classList.add('scrolling');
    
    // Smooth scroll to features
    setTimeout(() => {
        featuresSection.scrollIntoView({ 
            behavior: 'smooth',
            block: 'start'
        });
    }, 100);
    
    // Fade in features content
    setTimeout(() => {
        document.querySelector('.features-content').classList.add('visible');
    }, 800);
}

// Intersection Observer for scroll effects
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.target.id === 'hero' && !entry.isIntersecting) {
            entry.target.classList.add('scrolling');
        } else if (entry.target.id === 'hero' && entry.isIntersecting) {
            entry.target.classList.remove('scrolling');
        }
    });
}, { threshold: 0.5 });

observer.observe(document.getElementById('hero'));

// Generate dynamic stars
const cosmosLayer = document.querySelector('.bong-cosmos-layer');
for (let i = 0; i < 100; i++) {
    const star = document.createElement('div');
    star.className = 'bong-star';
    star.style.width = Math.random() * 3 + 1 + 'px';
    star.style.height = star.style.width;
    star.style.left = Math.random() * 100 + '%';
    star.style.top = Math.random() * 100 + '%';
    star.style.animationDelay = Math.random() * 3 + 's';
    cosmosLayer.appendChild(star);
}

function scrollToFeatures() {
    const heroSection = document.getElementById('hero');
    const featuresSection = document.getElementById('features');
    
    featuresSection.scrollIntoView({ 
        behavior: 'smooth',
        block: 'start'
    });
    
    setTimeout(() => {
        document.querySelector('.features-content').classList.add('visible');
    }, 800);
}
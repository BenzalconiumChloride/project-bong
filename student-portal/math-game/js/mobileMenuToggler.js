// Mobile Menu Toggle Functionality
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mobileMenuContent = document.getElementById('mobileMenuContent');
    
    if (mobileMenuToggle && mobileMenuContent) {
        // Toggle menu on button click
        mobileMenuToggle.addEventListener('click', function() {
            this.classList.toggle('active');
            
            if (mobileMenuContent.classList.contains('collapsed')) {
                mobileMenuContent.classList.remove('collapsed');
                mobileMenuContent.classList.add('expanded');
            } else {
                mobileMenuContent.classList.remove('expanded');
                mobileMenuContent.classList.add('collapsed');
            }
        });
        
        // Auto-collapse menu on mobile when window loads
        function checkScreenSize() {
            if (window.innerWidth <= 768) {
                // On mobile, start with menu collapsed
                if (!mobileMenuContent.classList.contains('collapsed')) {
                    mobileMenuContent.classList.remove('expanded');
                    mobileMenuContent.classList.add('collapsed');
                    mobileMenuToggle.classList.remove('active');
                }
            } else {
                // On desktop, always show expanded
                if (!mobileMenuContent.classList.contains('expanded')) {
                    mobileMenuContent.classList.remove('collapsed');
                    mobileMenuContent.classList.add('expanded');
                }
            }
        }
        
        // Check on load
        checkScreenSize();
        
        // Check on resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(checkScreenSize, 250);
        });
        
        // Optional: Auto-collapse menu after selecting a game or difficulty on mobile
        const gameButtons = document.querySelectorAll('.game-btn');
        const diffButtons = document.querySelectorAll('.diff-btn');
        
        function autoCollapseOnMobile() {
            if (window.innerWidth <= 768) {
                mobileMenuContent.classList.remove('expanded');
                mobileMenuContent.classList.add('collapsed');
                mobileMenuToggle.classList.remove('active');
            }
        }
        
        gameButtons.forEach(button => {
            button.addEventListener('click', autoCollapseOnMobile);
        });
        
        diffButtons.forEach(button => {
            button.addEventListener('click', autoCollapseOnMobile);
        });
    }
});
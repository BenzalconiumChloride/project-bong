<nav class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                    </svg>
                </div>
                <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                    LearnHub
                </span>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center gap-6">
                <a href="#features" class="text-gray-700 hover:text-blue-600 transition-colors">
                    Features
                </a>
                <a href="#portals" class="text-gray-700 hover:text-blue-600 transition-colors">
                    Portals
                </a>
                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Get Started
                </button>
            </div>

            <!-- Mobile Menu Button -->
            <button class="md:hidden" id="mobileMenuBtn">
                <svg class="w-6 h-6" id="menuIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                <svg class="w-6 h-6 hidden" id="closeIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Mobile Navigation -->
        <div class="md:hidden py-4 space-y-2 hidden" id="mobileMenu">
            <a href="#features" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 rounded-lg">
                Features
            </a>
            <a href="#portals" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 rounded-lg">
                Portals
            </a>
            <button class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Get Started
            </button>
        </div>
    </div>
</nav>
<div class="left-panel">
    <!-- Mobile Menu Toggle Button (hidden on desktop) -->
    <button class="mobile-menu-toggle" id="mobileMenuToggle">
        <i class="fas fa-bars menu-icon"></i>
        <span>Game Settings</span>
    </button>

    <!-- Collapsible Menu Content -->
    <div class="mobile-menu-content expanded" id="mobileMenuContent">
        <h2 class="panel-title"><i class="fas fa-gamepad"></i> Math Games</h2>
        <div class="game-buttons" id="gameButtons">
            <button class="game-btn active" data-game="addition">
                <i class="fas fa-plus"></i><br>Addition
            </button>
            <button class="game-btn" data-game="subtraction">
                <i class="fas fa-minus"></i><br>Subtraction
            </button>
            <button class="game-btn" data-game="multiplication">
                <i class="fas fa-times"></i><br>Multiplication
            </button>
            <button class="game-btn" data-game="division">
                <i class="fas fa-divide"></i><br>Division
            </button>
            <button class="game-btn" data-game="mixed">
                <i class="fas fa-random"></i><br>Mixed
            </button>
            <button class="game-btn" data-game="counting">
                <i class="fas fa-sort-numeric-up"></i><br>Numbers
            </button>
        </div>

        <h2 class="panel-title"><i class="fas fa-trophy"></i> Difficulty Level</h2>
        <div class="difficulty-buttons" id="difficultyButtons">
            <button class="diff-btn difficulty-easy active" data-difficulty="easy">
                <i class="fas fa-seedling"></i> Easy
            </button>
            <button class="diff-btn difficulty-medium" data-difficulty="medium">
                <i class="fas fa-apple-alt"></i> Medium
            </button>
            <button class="diff-btn difficulty-hard" data-difficulty="hard">
                <i class="fas fa-fire"></i> Hard
            </button>
            <button class="diff-btn difficulty-expert" data-difficulty="expert">
                <i class="fas fa-crown"></i> Expert
            </button>
        </div>
    </div>

    <!-- Timer Always Visible -->
    <div class="timer-container">
        <div class="timer-text">Time Remaining</div>
        <div class="timer-value" id="timer">60</div>
    </div>
</div>
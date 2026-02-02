<link rel="stylesheet" href="<?php echo WEB_ROOT; ?>student-portal/math-game/css/math-adventure.css">
<div class="container">
    <div class="game-area">
       <?php 
            include 'components/leftpanel.php';
       ?>

        <!-- Right Panel - Game -->
        <div class="right-panel">
            <div class="game-display" id="gameDisplay">
                <h2 id="gameQuestion">Solve the math problem:</h2>
                <div class="math-problem" id="mathProblem">5 + 3 = ?</div>

                <div class="answer-input-container">
                    <input type="number" class="answer-input" id="answerInput" placeholder="Enter answer" autofocus>
                </div>

                <div class="feedback" id="feedback">
                    Enter your answer and press Submit!
                </div>

                <div class="controls">
                    <button class="control-btn submit-btn" id="submitBtn">
                        <i class="fas fa-check"></i> Submit Answer
                    </button>
                    <button class="control-btn next-btn" id="nextBtn">
                        <i class="fas fa-forward"></i> Next
                    </button>
                    <button class="control-btn reset-btn" id="resetBtn">
                        <i class="fas fa-redo"></i> Reset
                    </button>
                </div>
            </div>

            <div class="additional-stats">
                <div class="stat-card">
                    <div class="stat-card-value" id="totalQuestions">0</div>
                    <div class="stat-card-label">Questions</div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-value" id="accuracy">0%</div>
                    <div class="stat-card-label">Accuracy</div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-value" id="avgTime">0s</div>
                    <div class="stat-card-label">Avg. Time</div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-value" id="sessionId">-</div>
                    <div class="stat-card-label">Session</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo WEB_ROOT;?>student-portal/math-game/js/mobileMenuToggler.js"></script>
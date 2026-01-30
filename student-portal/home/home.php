<link rel="stylesheet" href="<?php echo WEB_ROOT; ?>student-portal/home/css/dashboard.css">
<link rel="stylesheet" href="<?php echo WEB_ROOT; ?>student-portal/home/css/gameboard.css">

<section id="dashboard">
    <div class="dashboard-container mt-3">
        <div class="dashboard-header d-flex justify-content-center">
            <h3>Learning Analytics</h3>
        </div>

        <div class="row g-4 justify-content-center">

            <div class="col-xl-4 col-md-4 col-sm-12">
                <div class="metric-card facebook">
                    <div class="metric-content">
                        <div class="metric-left">
                            <div class="circular-progress">
                                <svg width="80" height="80">
                                    <circle class="bg-circle" cx="40" cy="40" r="36"></circle>
                                    <circle class="progress-circle" cx="40" cy="40" r="36"
                                        stroke-dasharray="226.19"
                                        stroke-dashoffset="90.48"></circle>
                                </svg>
                                <div class="progress-text">60%</div>
                            </div>
                            <div class="metric-info">
                                <h3>Literacy</h3>
                                <div class="metric-change">
                                    <span>22.14%</span>
                                    <i class="fas fa-arrow-up"></i>
                                </div>
                            </div>
                        </div>
                        <div class="metric-icon">
                            <i class="fa-solid fa-book"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-4 col-sm-12">
                <div class="metric-card facebook">
                    <div class="metric-content">
                        <div class="metric-left">
                            <div class="circular-progress">
                                <svg width="80" height="80">
                                    <circle class="bg-circle" cx="40" cy="40" r="36"></circle>
                                    <circle class="progress-circle" cx="40" cy="40" r="36"
                                        stroke-dasharray="226.19"
                                        stroke-dashoffset="90.48"></circle>
                                </svg>
                                <div class="progress-text">60%</div>
                            </div>
                            <div class="metric-info">
                                <h3>Numeracy</h3>
                                <div class="metric-change">
                                    <span>22.14%</span>
                                    <i class="fas fa-arrow-up"></i>

                                </div>
                            </div>
                        </div>
                        <div class="metric-icon">
                            <i class="fa-solid fa-calculator"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-4 col-sm-12">
                <div class="metric-card facebook">
                    <div class="metric-content">
                        <div class="metric-left">
                            <div class="circular-progress">
                                <svg width="80" height="80">
                                    <circle class="bg-circle" cx="40" cy="40" r="36"></circle>
                                    <circle class="progress-circle" cx="40" cy="40" r="36"
                                        stroke-dasharray="226.19"
                                        stroke-dashoffset="90.48"></circle>
                                </svg>
                                <div class="progress-text">60%</div>
                            </div>
                            <div class="metric-info">
                                <h3>Overall</h3>
                                <div class="metric-change">
                                    <span>22.14%</span>
                                    <i class="fas fa-arrow-up"></i>

                                </div>
                            </div>
                        </div>
                        <div class="metric-icon">
                            <i class="fa-solid fa-brain"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="suggestion-card">
                    <div class="metric-content" style="margin-left: 25px; margin-right: 25px;">
                        <div class="metric-left">
                            <div class="circular-progress">
                                <div class="progress-text">Suggestion</div>
                            </div>
                            <div class="metric-info">
                                <div class="metric-change">
                                    <div class="suggestion-text">
                                        <i class="bi bi-stars"></i> Focus More on Numeracy
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="metric-icon">
                            <i class="bi bi-lightbulb-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section id="hub" class="container">
    <div class="hub-wrapper">
        <div class="container">
            <div class="row mb-5 mt-5">
                <div class="col-12">
                    <div class="hub-header">
                        <h3 class="mb-3 text-black">Choose Your Challenge</h3>
                        <p class="mb-0 text-black">Select a subject and level up your skills</p>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Literacy Challenge Card -->
                <div class="col-12 col-md-6 col-lg-6">
                    <a href="reading_adventure.php" class="challenge-module literacy-module">
                        <div class="module-banner">
                            <span class="progress-indicator">Level 1</span>
                            <i class="bi bi-calculator"></i>
                        </div>
                        <div class="module-details">
                            <h2 class="module-heading mb-3">Literacy Challenge</h2>
                            <div class="skill-tags mb-3">
                                <span class="skill-badge">Reading Comprehension</span>
                                <span class="skill-badge">Vocabulary</span>
                                <span class="skill-badge">Grammar</span>
                            </div>
                            <p class="module-summary mb-4">
                                Enhance your reading and language skills through engaging challenges. Master comprehension, expand your vocabulary, and perfect your grammar.
                            </p>
                            <span class="action-trigger">Start Challenge <i class="fas fa-arrow-right ms-2"></i></span>
                        </div>
                    </a>
                </div>

                <!-- Math Challenge Card -->
                <div class="col-12 col-md-6 col-lg-6">
                    <a href="<?= WEB_ROOT; ?>student-portal/math-game/" class="challenge-module numeracy-module">
                        <div class="module-banner">
                            <span class="progress-indicator">Level 1</span>
                           <i class="bi bi-plus-slash-minus"></i>
                        </div>
                        <div class="module-details">
                            <h2 class="module-heading mb-3">Math Challenge</h2>
                            <div class="skill-tags mb-3">
                                <span class="skill-badge">Algebra</span>
                                <span class="skill-badge">Geometry</span>
                                <span class="skill-badge">Problem Solving</span>
                            </div>
                            <p class="module-summary mb-4">
                                Sharpen your mathematical thinking with progressively challenging problems. Build confidence in algebra, geometry, and analytical reasoning.
                            </p>
                            <span class="action-trigger">Start Challenge <i class="fas fa-arrow-right ms-2"></i></span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
    // Animate circles on load
    window.addEventListener('load', function() {
        const circles = document.querySelectorAll('.progress-circle');
        circles.forEach(circle => {
            const offset = circle.getAttribute('stroke-dashoffset');
            circle.style.strokeDashoffset = '226.19';
            setTimeout(() => {
                circle.style.strokeDashoffset = offset;
            }, 100);
        });
    });
</script>
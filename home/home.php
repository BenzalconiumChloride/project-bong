<?php
require_once 'global-library/database.php';
require_once 'include/functions.php';

$data = ["emailAddress" => null, "message" => null];

if (isset($_POST['txtEmailAddress']) && isset($_POST['login'])) {
    $result = doLogin();
    if (!empty($result) && is_array($result)) {
        $data = $result;
    }
}

if (isset($_POST['register'])) {
    $fname = trim($_POST['firstname']);
    $lname = trim($_POST['lastname']);
    $email = trim($_POST['txtEmailAddress']);
    $password = password_hash($_POST['txtPassword'], PASSWORD_DEFAULT);
    $userType = $_POST['userType']; // Get user type from form
    $accessLevel = ($userType == 'teacher') ? 1 : 0; // Convert to access level

    $stmt = $conn->prepare("SELECT user_id FROM bs_user WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        $data['message'] = 'Email already registered';
    } else {
        $stmt = $conn->prepare("
            INSERT INTO bs_user (firstname, lastname, email, password, access_level, date_added)
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$fname, $lname, $email, $password, $accessLevel]);
        $data['message'] = 'success'; // Changed to trigger SweetAlert
    }

    $data['emailAddress'] = $email;
}

$isLoggedIn = isset($_SESSION['user_id']);
?>

<link rel="stylesheet" href="<?php echo WEB_ROOT; ?>home/css/login.css">


<section class="py-5">
    <div class="container py-lg-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 mb-3">
                    For Grades 1-12
                </span>
                <h1 class="display-3 fw-bold mb-4">
                    Learning Made
                    <span class="gradient-text">Fun & Easy</span>
                </h1>
                <p class="lead text-muted mb-4">
                    Your all-in-one platform for interactive learning, educational games, and smart tools to help students excel from Grade 1 to Grade 12.
                </p>
                <div class="d-flex flex-column flex-sm-row gap-3">
                    <button class="btn btn-gradient btn-lg rounded-3 px-4" onclick="alert('Creative Hub coming soon!')">
                        Start Learning
                        <i class="bi bi-chevron-right ms-2"></i>
                    </button>
                    <button class="btn btn-outline-secondary btn-lg rounded-3 px-4">
                        Watch Demo
                    </button>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="position-relative">
                    <div class="hero-blur-bg"></div>
                    <img src="https://images.unsplash.com/photo-1719159381916-062fa9f435a6?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&w=800"
                        alt="Students learning"
                        class="img-fluid hero-image">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-5 bg-white">
    <div class="container py-lg-5">
        <div class="text-center mb-5">
            <h2 class="display-4 fw-bold mb-3">
                🎯 Choose Your Adventure!
            </h2>
            <p class="lead text-muted mx-auto" style="max-width: 700px;">
                Tap an app to start learning and having fun! Pick your favorite subject and let's go! 🚀
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- AI Learning Assistant -->
            <div class="col-6 col-sm-4 col-md-3 col-lg-2" onclick="toggleChat()">
                <div class="app-icon-wrapper text-center">
                    <div class="app-icon bg-primary mb-2">
                        <svg class="bi bi-robot" width="2em" height="2em">
                            <use xlink:href="<?php echo WEB_ROOT; ?>assets/home-svg/ai.svg" />
                        </svg>
                    </div>
                    <h6 class="app-icon-title mb-0">Ask BONG AI</h6>
                </div>
            </div>

            <!-- Literacy Games -->
            <div class="col-6 col-sm-4 col-md-3 col-lg-2"  onclick="checkSessionAndRedirect('Literacy Games', 'literacy')">
                <div class="app-icon-wrapper text-center">
                    <div class="app-icon mb-2" style="background: linear-gradient(135deg, #9333ea, #c084fc);">
                        <svg class="bi bi-robot" width="2em" height="2em">
                            <use xlink:href="<?php echo WEB_ROOT; ?>assets/home-svg/literacy.svg" />
                        </svg>
                    </div>
                    <h6 class="app-icon-title mb-0">Literacy Games</h6>
                </div>
            </div>

            <!-- Math Adventures -->
            <div class="col-6 col-sm-4 col-md-3 col-lg-2" onclick="checkSessionAndRedirect('Math Adventiures', 'math')">
                <div class="app-icon-wrapper text-center">
                    <div class="app-icon bg-success mb-2">
                        <svg class="bi bi-robot" width="2em" height="2em">
                            <use xlink:href="<?php echo WEB_ROOT; ?>assets/home-svg/math.svg" />
                        </svg>
                    </div>
                    <h6 class="app-icon-title mb-0">Math Adventures</h6>
                </div>
            </div>

            <!-- Health & Wellness -->
            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <div class="app-icon-wrapper text-center">
                    <div class="app-icon mb-2" style="background: linear-gradient(135deg, #ec4899, #f472b6);">
                        <svg class="bi bi-robot" width="2em" height="2em">
                            <use xlink:href="<?php echo WEB_ROOT; ?>assets/home-svg/health.svg" />
                        </svg>
                    </div>
                    <h6 class="app-icon-title mb-0">Health & Wellness</h6>
                </div>
            </div>

            <!-- Disaster Safety -->
            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <div class="app-icon-wrapper text-center">
                    <div class="app-icon mb-2" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
                        <svg class="bi bi-robot" width="2em" height="2em">
                            <use xlink:href="<?php echo WEB_ROOT; ?>assets/home-svg/disaster.svg" />
                        </svg>
                    </div>
                    <h6 class="app-icon-title mb-0">Disaster Safety</h6>
                </div>
            </div>

            <!-- Learning Games -->
            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <div class="app-icon-wrapper text-center">
                    <div class="app-icon mb-2" style="background: linear-gradient(135deg, #6366f1, #818cf8);">
                        <svg class="bi bi-robot" width="2em" height="2em">
                            <use xlink:href="<?php echo WEB_ROOT; ?>assets/home-svg/learning.svg" />
                        </svg>
                    </div>
                    <h6 class="app-icon-title mb-0">Learning Games</h6>
                </div>
            </div>

            <!-- Science Explorer -->
            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <div class="app-icon-wrapper text-center">
                    <div class="app-icon mb-2" style="background: linear-gradient(135deg, #06b6d4, #67e8f9);">
                        <i class="bi bi-lightbulb-fill"></i>
                    </div>
                    <h6 class="app-icon-title mb-0">Science Explorer</h6>
                </div>
            </div>

            <!-- Art Studio -->
            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <div class="app-icon-wrapper text-center">
                    <div class="app-icon mb-2" style="background: linear-gradient(135deg, #f97316, #fb923c);">
                        <i class="bi bi-palette-fill"></i>
                    </div>
                    <h6 class="app-icon-title mb-0">Art Studio</h6>
                </div>
            </div>

            <!-- Music Class -->
            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <div class="app-icon-wrapper text-center">
                    <div class="app-icon mb-2" style="background: linear-gradient(135deg, #8b5cf6, #a78bfa);">
                        <i class="bi bi-music-note-beamed"></i>
                    </div>
                    <h6 class="app-icon-title mb-0">Music Class</h6>
                </div>
            </div>

            <!-- Geography Quest -->
            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <div class="app-icon-wrapper text-center">
                    <div class="app-icon mb-2" style="background: linear-gradient(135deg, #14b8a6, #5eead4);">
                        <i class="bi bi-globe"></i>
                    </div>
                    <h6 class="app-icon-title mb-0">Geography Quest</h6>
                </div>
            </div>

            <!-- History Time -->
            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <div class="app-icon-wrapper text-center">
                    <div class="app-icon mb-2" style="background: linear-gradient(135deg, #84cc16, #a3e635);">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <h6 class="app-icon-title mb-0">History Time</h6>
                </div>
            </div>

            <!-- Coding Fun -->
            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <div class="app-icon-wrapper text-center">
                    <div class="app-icon mb-2" style="background: linear-gradient(135deg, #ef4444, #f87171);">
                        <i class="bi bi-code-slash"></i>
                    </div>
                    <h6 class="app-icon-title mb-0">Coding Fun</h6>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Portals Section -->
<section id="portals" class="py-5" style="background: linear-gradient(to bottom, #f9fafb, #ffffff);">
    <div class="container py-lg-5">
        <div class="text-center mb-5">
            <h2 class="display-4 fw-bold mb-3">Choose Your Portal</h2>
            <p class="lead text-muted mx-auto" style="max-width: 700px;">
                Access personalized dashboards designed for teachers and students
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Teacher Portal -->
            <div class="col-md-6 col-lg-5">
                <div class="portal-card h-100">
                    <div class="portal-icon mb-4" style="background: #14b8a6;">
                        <i class="bi bi-mortarboard"></i>
                    </div>
                    <h3 class="fw-bold mb-3">Teacher Portal</h3>
                    <p class="text-muted mb-4">
                        Manage classes, track progress, and assign activities
                    </p>
                    <button class="btn btn-gradient w-100 rounded-3 py-3">
                        Access Portal
                        <i class="bi bi-chevron-right ms-2"></i>
                    </button>
                </div>
            </div>

            <!-- Student Portal -->
            <div class="col-md-6 col-lg-5">
                <div class="portal-card h-100">
                    <div class="portal-icon mb-4" style="background: #06b6d4;">
                        <i class="bi bi-people"></i>
                    </div>
                    <h3 class="fw-bold mb-3">Student Portal</h3>
                    <p class="text-muted mb-4">
                        Access your assignments, games, and learning dashboard
                    </p>
                    <button class="btn btn-gradient w-100 rounded-3 py-3">
                        Access Portal
                        <i class="bi bi-chevron-right ms-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5">
    <div class="container py-lg-5">
        <div class="gradient-bg rounded-4 p-5 text-center text-white">
            <h2 class="display-4 fw-bold mb-4">Ready to Start Your Learning Journey?</h2>
            <p class="lead mb-5 mx-auto opacity-90" style="max-width: 700px;">
                Join thousands of students and teachers already using LearnHub to make education more engaging and effective.
            </p>
            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                <button class="btn btn-light btn-lg rounded-3 px-5 text-primary fw-semibold">
                    Sign Up Free
                </button>
                <button class="btn btn-outline-light btn-lg rounded-3 px-5 fw-semibold">
                    Learn More
                </button>
            </div>
        </div>
    </div>
</section>


<!-- modal -->
<div class="modal fade" id="loginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
           
                <div class="login-container">

                    <div class="login-container-content p-4 p-sm-5">


                        <?php if (!empty($data["message"]) && $data["message"] != 'success'): ?>
                            <div class="product-message-error mb-3">
                                <?= htmlspecialchars($data["message"]); ?>
                            </div>
                        <?php endif; ?>

                        <!-- FORM WRAPPER with transition -->
                        <div class="form-wrapper">
                            <!-- LOGIN FORM -->
                            <form id="loginForm" method="post" class="form-slide active">
                                <div class="form-group-neu mb-3">
                                    <label>Email</label>
                                    <input type="email" name="txtEmailAddress" class="form-control"
                                        value="<?= htmlspecialchars($data["emailAddress"]); ?>" required>
                                </div>

                                <div class="form-group-neu mb-4">
                                    <label>Password</label>
                                    <input type="password" name="txtPassword" class="form-control" required>
                                </div>

                                <button type="submit" name="login" class="btn btn-login">Login</button>

                                <p class="text-center mt-3 switch-text">
                                    No account? <a href="#" onclick="toggleForm(); return false;">Register</a>
                                </p>
                            </form>

                            <!-- REGISTER FORM -->
                            <form id="registerForm" method="post" class="form-slide">
                                <div class="form-group-neu mb-3">
                                    <label>First Name</label>
                                    <input type="text" name="firstname" class="form-control" required>
                                </div>

                                <div class="form-group-neu mb-3">
                                    <label>Last Name</label>
                                    <input type="text" name="lastname" class="form-control" required>
                                </div>

                                <div class="form-group-neu mb-3">
                                    <label>Email</label>
                                    <input type="email" name="txtEmailAddress" class="form-control" required>
                                </div>

                                <div class="form-group-neu mb-4">
                                    <label>Password</label>
                                    <input type="password" name="txtPassword" class="form-control" required>
                                </div>

                                <div class="form-group-neu mb-4">
                                    <label class="mb-3">I am a:</label>
                                    <div class="radio-group">
                                        <label class="radio-card">
                                            <input type="radio" name="userType" value="student" checked>
                                            <span class="radio-card-content">
                                                <i class="bi bi-backpack-fill"></i>
                                                <span class="radio-label">Student</span>
                                            </span>
                                        </label>
                                        <label class="radio-card">
                                            <input type="radio" name="userType" value="teacher">
                                            <span class="radio-card-content">
                                                <i class="bi bi-mortarboard-fill"></i>
                                                <span class="radio-label">Teacher</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" name="register" class="btn btn-login">Register</button>

                                <p class="text-center mt-3 switch-text">
                                    Already have an account? <a href="#" onclick="toggleForm(); return false;">Login</a>
                                </p>
                            </form>
                        </div>

                    </div>
                </div>
        </div>
    </div>
</div>

<script>
     // Check if user is logged in
    const isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;

    function checkSessionAndRedirect(appName, appUrl) {
        if (!isLoggedIn) {
            Swal.fire({
                icon: 'warning',
                title: 'Login Required',
                text: 'You need to register or login first to access ' + appName + '!',
                confirmButtonText: 'Register / Login',
                confirmButtonColor: '#667eea',
                showCancelButton: true,
                cancelButtonText: 'Cancel',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Open login modal using Bootstrap 5
                    const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                    loginModal.show();
                }
            });
        } else {
            // User is logged in, redirect to the app
            window.location.href = '<?php echo WEB_ROOT; ?>' + appUrl + '/';
        }
    }
    
    let currentForm = 'login';

    function toggleForm() {
        const login = document.getElementById('loginForm');
        const register = document.getElementById('registerForm');

        if (currentForm === 'login') {
            // Switch to register
            login.classList.remove('active');
            login.classList.add('slide-out-left');

            setTimeout(() => {
                login.classList.remove('slide-out-left');
                register.classList.add('active');
                currentForm = 'register';
            }, 100);
        } else {
            // Switch to login
            register.classList.remove('active');
            register.classList.add('slide-out-left');

            setTimeout(() => {
                register.classList.remove('slide-out-left');
                login.classList.add('active');
                currentForm = 'login';
            }, 100);
        }
    }

    // Add loading state on form submit
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        const btn = this.querySelector('.btn-login');
        btn.classList.add('loading');
        btn.textContent = 'Signing in...';
    });

    document.getElementById('registerForm').addEventListener('submit', function(e) {
        const btn = this.querySelector('.btn-login');
        btn.classList.add('loading');
        btn.textContent = 'Creating account...';
    });

    // Add focus animations
    const inputs = document.querySelectorAll('.form-group-neu input');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.querySelector('label').style.color = '#667eea';
        });
        input.addEventListener('blur', function() {
            if (!this.value) {
                this.parentElement.querySelector('label').style.color = '#333';
            }
        });
    });

    // SweetAlert for successful registration
    <?php if (!empty($data["message"]) && $data["message"] == 'success'): ?>
    Swal.fire({
        icon: 'success',
        title: 'Registration Successful!',
        text: 'You may now login with your credentials.',
        confirmButtonText: 'OK',
        confirmButtonColor: '#667eea',
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            // Switch to login form
            toggleForm();
        }
    });
    <?php endif; ?>
</script>
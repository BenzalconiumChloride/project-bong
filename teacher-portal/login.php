<?php
require_once '../global-library/database.php';
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

    $stmt = $conn->prepare("SELECT user_id FROM bs_user WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        $data['message'] = 'Email already registered';
    } else {
        $stmt = $conn->prepare("
            INSERT INTO bs_user (firstname, lastname, email, password, date_added)
            VALUES (?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$fname, $lname, $email, $password]);
        $data['message'] = 'Registration successful. You may now login.';
    }

    $data['emailAddress'] = $email;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <?php include($_SERVER["DOCUMENT_ROOT"] . '/' . $webRoot . '/include/global-css.php'); ?>

    <title>Login - Welcome Back</title>
</head>

<style>
    body {
        width: 100%;
        min-height: 100dvh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    /* Animated background elements */
    body::before,
    body::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        opacity: 0.1;
        animation: float 20s infinite ease-in-out;
    }

    body::before {
        width: 500px;
        height: 500px;
        background: white;
        top: -250px;
        left: -250px;
        animation-delay: 0s;
    }

    body::after {
        width: 400px;
        height: 400px;
        background: white;
        bottom: -200px;
        right: -200px;
        animation-delay: 5s;
    }

    @keyframes float {

        0%,
        100% {
            transform: translate(0, 0) scale(1);
        }

        33% {
            transform: translate(50px, -50px) scale(1.1);
        }

        66% {
            transform: translate(-30px, 30px) scale(0.9);
        }
    }

    .login-container {
        position: relative;
        z-index: 1;
        animation: slideUp 0.6s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .login-container-header {
        text-align: center;
        animation: fadeIn 0.8s ease-out 0.2s both;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .login-logo {
        width: 100%;
        max-width: 100px;
        height: auto;
        filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.15));
        transition: transform 0.3s ease;
    }

    .login-logo:hover {
        transform: scale(1.05);
    }

    .product-title {
        font-size: 24px;
        font-weight: 700;
        color: white;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .product-subtitle {
        font-size: 15px;
        font-weight: 300;
        color: rgba(255, 255, 255, 0.9);
    }

    .login-container-content {
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3),
            0 0 0 1px rgba(255, 255, 255, 0.1);
        animation: slideUp 0.6s ease-out 0.3s both;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .login-container-content:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 70px rgba(0, 0, 0, 0.35),
            0 0 0 1px rgba(255, 255, 255, 0.1);
    }

    .product-message-error {
        text-align: center;
        font-size: 14px;
        padding: 12px;
        border-radius: 10px;
        background: rgba(220, 53, 69, 0.1);
        border: 1px solid rgba(220, 53, 69, 0.3);
        animation: shake 0.5s ease;
    }

    @keyframes shake {

        0%,
        100% {
            transform: translateX(0);
        }

        25% {
            transform: translateX(-10px);
        }

        75% {
            transform: translateX(10px);
        }
    }

    .form-group-neu label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #333;
        transition: color 0.3s ease;
    }

    .form-group-neu input {
        width: 100%;
        padding: 14px 18px;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s ease;
        background: #f8f9fa;
        color: #333;
    }

    .form-group-neu input:focus {
        outline: none;
        border-color: #667eea;
        background: white;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        transform: translateY(-2px);
    }

    .form-group-neu input:focus+label {
        color: #667eea;
    }

    .form-group-neu input::placeholder {
        color: #adb5bd;
    }

    .btn-login {
        width: 100%;
        padding: 14px;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .btn-login::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s ease, height 0.6s ease;
    }

    .btn-login:hover::before {
        width: 300px;
        height: 300px;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 25px rgba(102, 126, 234, 0.5);
    }

    .btn-login:active {
        transform: translateY(0);
    }

    /* Loading state animation */
    .btn-login.loading {
        pointer-events: none;
        opacity: 0.7;
    }

    .btn-login.loading::after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        top: 50%;
        left: 50%;
        margin-left: -8px;
        margin-top: -8px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* Form transition styles */
    .form-wrapper {
        position: relative;
        overflow: hidden;
    }

    .form-slide {
        transition: all 0.4s ease-in-out;
        opacity: 0;
        transform: translateX(100%);
        position: absolute;
        width: 100%;
        pointer-events: none;
    }

    .form-slide.active {
        opacity: 1;
        transform: translateX(0);
        position: relative;
        pointer-events: all;
    }

    .form-slide.slide-out-left {
        transform: translateX(-100%);
        opacity: 0;
    }

    .switch-text {
        transition: opacity 0.3s ease-in-out;
    }
</style>

<body>
    <div class="container-fluid">
        <div class="row justify-content-center min-vh-100 py-4">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">

                <div class="login-container">
                    <div class="login-container-header mb-4">
                        <img class="login-logo mb-3" src="<?php echo WEB_ROOT; ?>assets/images/depedSilay.png" alt="Logo">
                    </div>

                    <div class="login-container-content p-4 p-sm-5">


                        <?php if (!empty($data["message"])): ?>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
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
    </script>

</body>

</html>
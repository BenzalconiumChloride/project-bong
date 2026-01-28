<?php
require_once 'global-library/database.php';
require_once 'include/functions.php';

$data = ["emailAddress" => null, "message" => null]; // Default structure

if (isset($_POST['txtEmailAddress'])) {
    $result = doLogin();
    if (!empty($result) && is_array($result)) {
        $data = $result;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php include($_SERVER["DOCUMENT_ROOT"] . '/' . $webRoot . '/include/global-css.php'); ?>

    <title>Login</title>
</head>

<style>
    body {
        width: 100%;
        height: 100dvh;
        background-color: red;
    }

    .login-container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
    }

    .login-container-header {
        text-align: center;
        margin-bottom: 1.5rem;
    }
    
    .login-logo{
        width: 250px;
        height: auto;
    }

    .product-title {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .product-subtitle {
        font-size: 13px;
        font-weight: 200;
    }

    .login-container-content {
        width: 100%;
        max-width: 400px;
        padding: 2rem;
        border-radius: var(--radius);
        background: var(--surface);
        box-shadow: 6px 6px 12px var(--shadow-dark),
            -6px -6px 12px var(--shadow-light);
    }

    .product-message-error {
        text-align: center;
        font-size: 13px;
    }
</style>

<body>
    <div class="login-container">
        
        <div class="login-container-header">
            <img class="login-logo" src="<?php echo WEB_ROOT; ?>assets/images/skate-horizontal.png">
        </div>

        <div class="login-container-content">
            <form id="loginform" name="frmLogin" method="post">

                <div class="product-message-error text-danger mb-3">
                    <?php echo htmlspecialchars($data["message"]); ?>
                </div>

                <div class="form-group-neu">
                    <label for="email">Email address</label>
                    <input type="email" name="txtEmailAddress" id="email"
                        value="<?php echo htmlspecialchars($data["emailAddress"]); ?>"
                        placeholder="Enter email" required>
                </div>

                <div class="form-group-neu">
                    <label for="password">Password</label>
                    <input type="password" name="txtPassword" id="password" placeholder="Enter password" required>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-success w-100">Login</button>
                </div>

            </form>
        </div>
    </div>
</body>

<?php include($_SERVER["DOCUMENT_ROOT"] . '/' . $webRoot . '/include/global-js.php'); ?>

</html>
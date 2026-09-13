<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login - E-Ride</title>
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/login.css">
</head>
<body>

<div class="brand-logo"><span class="logo-badge">E</span>-Ride Services</div>

<main class="auth-container">
    <div class="auth-card">
        <h1>Welcome Back </h1>
        <p class="auth-subtitle">Sign in to your E-Ride account</p>

        <?php if (!empty($_SESSION['successMsg'])): ?>
            <p class="success-msg"><?php echo $_SESSION['successMsg']; $_SESSION['successMsg'] = ""; ?></p>
        <?php endif; ?>

        <p class="success-msg" id="forgotPasswordMsg" style="display:none;">An OTP is send to your number</p>

        <?php if (!empty($_SESSION['globalErrMsg'])): ?>
            <p class="error-msg" id="globalErrMsg"><?php echo $_SESSION['globalErrMsg']; $_SESSION['globalErrMsg'] = ""; ?></p>
        <?php endif; ?>

        <form id="loginForm" method="post" action="../controller/LoginController.php" onsubmit="return validateLoginForm(this)" novalidate>
            <div class="auth-row">
                <div class="form-group" style="flex: 2;">
                    <label for="phone">Phone Number</label>
                    <div class="phone-input-group">
                        <span class="phone-prefix">BD +880</span>
                        <input type="text" name="phone" id="phone" placeholder="1XXXXXXXXX"
                               value="<?php echo isset($_SESSION['phone']) ? htmlspecialchars($_SESSION['phone']) : (isset($_COOKIE['remembered_phone']) ? htmlspecialchars($_COOKIE['remembered_phone']) : ''); ?>">
                    </div>
                    <span id="phoneErrMsg" class="field-error"><?php echo isset($_SESSION['phoneErrMsg']) ? $_SESSION['phoneErrMsg'] : ''; $_SESSION['phoneErrMsg'] = ''; ?></span>
                </div>

                <div class="form-group role-select-group">
                    <label for="loginAs">Login as</label>
                    <select name="loginAs" id="loginAs">
                        <option value="Customer">Customer</option>
                        <option value="Manager">Manager</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-input-group">
                    <input type="password" name="password" id="password" placeholder="Enter your Password">
                    <button type="button" class="toggle-password" onclick="togglePassword('password', this)">🔒</button>
                </div>
                <span id="passwordErrMsg" class="field-error"><?php echo isset($_SESSION['passwordErrMsg']) ? $_SESSION['passwordErrMsg'] : ''; $_SESSION['passwordErrMsg'] = ''; ?></span>
            </div>

            <div class="form-group checkbox-group">
                <input type="checkbox" name="rememberMe" id="rememberMe" <?php echo isset($_COOKIE['remembered_phone']) ? 'checked' : ''; ?>>
                <label for="rememberMe" style="margin:0;">Remember Me</label>
            </div>

            <button type="submit" class="btn-primary">Login</button>

            <div class="auth-links">
                <a href="#" id="forgotPasswordLink" onclick="showForgotPasswordMsg(event)">Forgot Password?</a>
            </div>
        </form>

        <p class="auth-switch">Don't have an Account? <a href="register.php">Register Here</a></p>
    </div>
</main>

<script src="../js/validation.js"></script>
</body>
</html>

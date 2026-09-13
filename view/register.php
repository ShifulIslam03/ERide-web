<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Register - E-Ride</title>
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/login.css">
<link rel="stylesheet" href="../css/register.css">
</head>
<body>

<div class="brand-logo"><span class="logo-badge">E</span>-Ride Services</div>

<main class="auth-container">
    <div class="auth-card auth-card-wide">
        <h1>Welcome </h1>
        <p class="auth-subtitle">Create your E-Ride account</p>

        <?php if (!empty($_SESSION['globalErrMsg'])): ?>
            <p class="error-msg" id="globalErrMsg"><?php echo $_SESSION['globalErrMsg']; $_SESSION['globalErrMsg'] = ""; ?></p>
        <?php endif; ?>

        <form id="registerForm" method="post" action="../controller/RegisterController.php" onsubmit="return validateRegisterForm(this)" novalidate>

            <div class="auth-row">
                <div style="flex:2;"></div>
                <div class="form-group role-select-group">
                    <label for="createAs">Create as</label>
                    <select name="createAs" id="createAs">
                        <option value="Customer">Customer</option>
                        <option value="Manager">Manager</option>
                    </select>
                </div>
            </div>

            <div class="field-grid">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" placeholder="Enter your name"
                           value="<?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : ''; ?>">
                    <span id="nameErrMsg" class="field-error"><?php echo isset($_SESSION['nameErrMsg']) ? $_SESSION['nameErrMsg'] : ''; $_SESSION['nameErrMsg'] = ''; ?></span>
                </div>

                <div class="form-group">
                    <label for="dob">Date of birth</label>
                    <input type="date" name="dob" id="dob"
                           value="<?php echo isset($_SESSION['dob']) ? htmlspecialchars($_SESSION['dob']) : ''; ?>">
                    <span id="dobErrMsg" class="field-error"><?php echo isset($_SESSION['dobErrMsg']) ? $_SESSION['dobErrMsg'] : ''; $_SESSION['dobErrMsg'] = ''; ?></span>
                </div>

                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select name="gender" id="gender">
                        <option value="">Select gender</option>
                        <option value="Male" <?php echo (isset($_SESSION['gender']) && $_SESSION['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo (isset($_SESSION['gender']) && $_SESSION['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?php echo (isset($_SESSION['gender']) && $_SESSION['gender'] === 'Other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                    <span id="genderErrMsg" class="field-error"><?php echo isset($_SESSION['genderErrMsg']) ? $_SESSION['genderErrMsg'] : ''; $_SESSION['genderErrMsg'] = ''; ?></span>
                </div>

                <div class="form-group">
                    <label for="area">Area</label>
                    <input type="text" name="area" id="area" placeholder="Enter your area"
                           value="<?php echo isset($_SESSION['area']) ? htmlspecialchars($_SESSION['area']) : ''; ?>">
                    <span id="areaErrMsg" class="field-error"><?php echo isset($_SESSION['areaErrMsg']) ? $_SESSION['areaErrMsg'] : ''; $_SESSION['areaErrMsg'] = ''; ?></span>
                </div>

                <div class="form-group">
                    <label for="block">Block</label>
                    <input type="text" name="block" id="block" placeholder="Enter your block no"
                           value="<?php echo isset($_SESSION['block']) ? htmlspecialchars($_SESSION['block']) : ''; ?>">
                    <span id="blockErrMsg" class="field-error"><?php echo isset($_SESSION['blockErrMsg']) ? $_SESSION['blockErrMsg'] : ''; $_SESSION['blockErrMsg'] = ''; ?></span>
                </div>

                <div class="form-group">
                    <label for="road">Road no</label>
                    <input type="text" name="road" id="road" placeholder="Enter road no"
                           value="<?php echo isset($_SESSION['road']) ? htmlspecialchars($_SESSION['road']) : ''; ?>">
                    <span id="roadErrMsg" class="field-error"><?php echo isset($_SESSION['roadErrMsg']) ? $_SESSION['roadErrMsg'] : ''; $_SESSION['roadErrMsg'] = ''; ?></span>
                </div>

                <div class="form-group full">
                    <label for="phone">Phone Number</label>
                    <div class="phone-input-group">
                        <span class="phone-prefix">BD +880</span>
                        <input type="text" name="phone" id="phone" placeholder="Enter your phone number"
                               value="<?php echo isset($_SESSION['phone']) ? htmlspecialchars($_SESSION['phone']) : ''; ?>">
                    </div>
                    <span id="phoneErrMsg" class="field-error"><?php echo isset($_SESSION['phoneErrMsg']) ? $_SESSION['phoneErrMsg'] : ''; $_SESSION['phoneErrMsg'] = ''; ?></span>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-input-group">
                        <input type="password" name="password" id="password" placeholder="Create a password">
                        <button type="button" class="toggle-password" onclick="togglePassword('password', this)">🔒</button>
                    </div>
                    <span id="passwordErrMsg" class="field-error"><?php echo isset($_SESSION['passwordErrMsg']) ? $_SESSION['passwordErrMsg'] : ''; $_SESSION['passwordErrMsg'] = ''; ?></span>
                </div>

                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <div class="password-input-group">
                        <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirm your password">
                        <button type="button" class="toggle-password" onclick="togglePassword('confirmPassword', this)">🔒</button>
                    </div>
                    <span id="confirmPasswordErrMsg" class="field-error"><?php echo isset($_SESSION['confirmPasswordErrMsg']) ? $_SESSION['confirmPasswordErrMsg'] : ''; $_SESSION['confirmPasswordErrMsg'] = ''; ?></span>
                </div>
            </div>

            <button type="submit" class="btn-primary">Sign Up</button>
        </form>

        <p class="auth-switch">Already have an Account? <a href="login.php">Sign in</a></p>
    </div>
</main>

<script src="../js/validation.js"></script>
</body>
</html>

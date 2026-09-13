<?php
session_start();
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}
require_once '../../model/User.php';
$user = getUser($_SESSION['phone']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Edit Profile - E-Ride</title>
<link rel="stylesheet" href="../../css/style.css">
<link rel="stylesheet" href="../../css/admin.css">
</head>
<body>
<div class="app-shell">
<?php $activePage = 'settings'; include '../Sidebar.php'; ?>
<div class="main-content">

<main class="page-container">
    <a href="profile.php" class="back-link btn-secondary btn-sm">&larr; Back</a>
    <h1>Edit Profile</h1>

    <?php if (!empty($_SESSION['globalErrMsg'])): ?>
        <p class="error-msg"><?php echo $_SESSION['globalErrMsg']; $_SESSION['globalErrMsg'] = ''; ?></p>
    <?php endif; ?>

    <section class="card">
        <h2>Profile Information</h2>
        <form method="post" action="../../controller/UserUpdateController.php" onsubmit="return validateProfileForm(this)" novalidate>
            <input type="hidden" name="action" value="updateProfile">

            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($user['Name']); ?>">
                <span id="nameErrMsg" class="field-error"><?php echo isset($_SESSION['nameErrMsg']) ? $_SESSION['nameErrMsg'] : ''; $_SESSION['nameErrMsg'] = ''; ?></span>
            </div>

            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" name="dob" id="dob" value="<?php echo htmlspecialchars($user['Date_of_Birth']); ?>">
                <span id="dobErrMsg" class="field-error"><?php echo isset($_SESSION['dobErrMsg']) ? $_SESSION['dobErrMsg'] : ''; $_SESSION['dobErrMsg'] = ''; ?></span>
            </div>

            <div class="form-group">
                <label for="gender">Gender</label>
                <select name="gender" id="gender">
                    <option value="Male" <?php echo $user['Gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo $user['Gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                    <option value="Other" <?php echo $user['Gender'] === 'Other' ? 'selected' : ''; ?>>Other</option>
                </select>
                <span id="genderErrMsg" class="field-error"></span>
            </div>

            <div class="form-group">
                <label for="area">Area</label>
                <input type="text" name="area" id="area" value="<?php echo htmlspecialchars($user['Area']); ?>">
                <span id="areaErrMsg" class="field-error"></span>
            </div>

            <div class="form-group">
                <label for="road">Road</label>
                <input type="text" name="road" id="road" value="<?php echo htmlspecialchars($user['Road']); ?>">
                <span id="roadErrMsg" class="field-error"></span>
            </div>

            <div class="form-group">
                <label for="block">Block</label>
                <input type="text" name="block" id="block" value="<?php echo htmlspecialchars($user['Block']); ?>">
                <span id="blockErrMsg" class="field-error"></span>
            </div>

            <button type="submit" class="btn-primary">Save Changes</button>
        </form>
    </section>

    <section class="card">
        <h2>Change Password</h2>
        <form method="post" action="../../controller/UserUpdateController.php" onsubmit="return validatePasswordForm(this)" novalidate>
            <input type="hidden" name="action" value="changePassword">

            <div class="form-group">
                <label for="currentPassword">Current Password</label>
                <input type="password" name="currentPassword" id="currentPassword">
                <span id="currentPasswordErrMsg" class="field-error"></span>
            </div>

            <div class="form-group">
                <label for="newPassword">New Password</label>
                <input type="password" name="newPassword" id="newPassword">
                <span id="newPasswordErrMsg" class="field-error"></span>
            </div>

            <div class="form-group">
                <label for="confirmPassword">Confirm New Password</label>
                <input type="password" name="confirmPassword" id="confirmPassword">
                <span id="confirmPasswordErrMsg" class="field-error"></span>
            </div>

            <button type="submit" class="btn-secondary">Change Password</button>
        </form>
    </section>
</main>

</div>
</div>
<script src="../../js/validation.js"></script>
</body>
</html>

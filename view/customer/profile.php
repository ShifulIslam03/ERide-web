<?php
session_start();
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['role'] !== 'Customer') {
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
<title>My Profile - E-Ride</title>
<link rel="stylesheet" href="../../css/style.css">
<link rel="stylesheet" href="../../css/customer.css">
</head>
<body>
<div class="app-shell">
<?php $activePage = 'settings'; include '../Sidebar.php'; ?>
<div class="main-content">

<main class="page-container">
    <h1>My Profile</h1>

    <?php if (!empty($_SESSION['successMsg'])): ?>
        <p class="success-msg"><?php echo $_SESSION['successMsg']; $_SESSION['successMsg'] = ''; ?></p>
    <?php endif; ?>

    <section class="card">
        <table class="profile-table">
            <tr><th>Phone Number</th><td><?php echo htmlspecialchars($user['Phone_Number']); ?></td></tr>
            <tr><th>Name</th><td><?php echo htmlspecialchars($user['Name']); ?></td></tr>
            <tr><th>Date of Birth</th><td><?php echo htmlspecialchars($user['Date_of_Birth']); ?></td></tr>
            <tr><th>Gender</th><td><?php echo htmlspecialchars($user['Gender']); ?></td></tr>
            <tr><th>Address</th><td><?php echo htmlspecialchars($user['Area'] . ', ' . $user['Road'] . ', ' . $user['Block']); ?></td></tr>
            <tr><th>Role</th><td><?php echo htmlspecialchars($user['Role']); ?></td></tr>
            <tr><th>Manager Request Status</th><td><?php echo htmlspecialchars($user['Manager_Request_Status']); ?></td></tr>
        </table>
        <a href="editProfile.php" class="btn-primary">Edit Profile</a>
    </section>

    <?php if ($user['Manager_Request_Status'] === 'None' || $user['Manager_Request_Status'] === 'Rejected'): ?>
    <section class="card">
        <h2>Become a Station Manager</h2>
        <p>Interested in managing an E-Ride station? Submit a request for Admin approval.</p>
        <form method="post" action="../../controller/UserUpdateController.php">
            <input type="hidden" name="action" value="requestManager">
            <button type="submit" class="btn-secondary">Request Manager Role</button>
        </form>
    </section>
    <?php elseif ($user['Manager_Request_Status'] === 'Pending'): ?>
    <section class="card">
        <p>Your manager request is <strong>pending</strong> Admin approval.</p>
    </section>
    <?php endif; ?>
</main>

</div>
</div>
</body>
</html>

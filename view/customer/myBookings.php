<?php
session_start();
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['role'] !== 'Customer') {
    header("Location: ../login.php");
    exit();
}
require_once '../../model/Booking.php';
require_once '../../model/Ride.php';

$phone = $_SESSION['phone'];
$bookings = getMyBookings($phone);
$activeRide = getActiveRide($phone);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>My Rides - E-Ride</title>
<link rel="stylesheet" href="../../css/style.css">
<link rel="stylesheet" href="../../css/customer.css">
</head>
<body>
<div class="app-shell">
<?php $activePage = 'rides'; include '../Sidebar.php'; ?>
<div class="main-content">
<main class="page-container">
    <h1>My Rides</h1>

    <?php if (!empty($_SESSION['successMsg'])): ?>
        <p class="success-msg"><?php echo $_SESSION['successMsg']; $_SESSION['successMsg'] = ''; ?></p>
    <?php endif; ?>
    <?php if (!empty($_SESSION['globalErrMsg'])): ?>
        <p class="error-msg"><?php echo $_SESSION['globalErrMsg']; $_SESSION['globalErrMsg'] = ''; ?></p>
    <?php endif; ?>

    <?php if ($activeRide): ?>
        <div class="alert-card">
            <p>You have an ongoing ride with <?php echo count($activeRide['Vehicles']); ?> bike(s).</p>
            <a href="ride.php" class="btn-primary">Manage Ride</a>
        </div>
    <?php endif; ?>

    <section class="card">
        <?php if (empty($bookings)): ?>
            <p>You have no bookings yet. <a href="bookBike.php">Rent a bike now</a>.</p>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr><th>Booking ID</th><th>Station</th><th>Bikes</th><th>Status</th><th>Date</th></tr>
                </thead>
                <tbody>
                <?php foreach ($bookings as $b): ?>
                    <tr>
                        <td><?php echo $b['Booking_ID']; ?></td>
                        <td><?php echo htmlspecialchars($b['Station_Name']); ?></td>
                        <td><?php echo $b['Vehicle_Count']; ?></td>
                        <td><span class="status-badge status-<?php echo strtolower($b['Status']); ?>"><?php echo $b['Status']; ?></span></td>
                        <td><?php echo date('d M Y, h:i A', strtotime($b['Booking_Date'])); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>
</main>
</div>
</div>
</body>
</html>

<?php
session_start();
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['role'] !== 'Customer') {
    header("Location: ../login.php");
    exit();
}
require_once '../../model/Ride.php';
require_once '../../model/Payment.php';

$phone = $_SESSION['phone'];
$rides = getRideHistory($phone);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Ride History - E-Ride</title>
<link rel="stylesheet" href="../../css/style.css">
<link rel="stylesheet" href="../../css/customer.css">
</head>
<body>
<div class="app-shell">
<?php $activePage = 'history'; include '../Sidebar.php'; ?>
<div class="main-content">

<main class="page-container">
    <h1>Ride History</h1>

    <?php if (!empty($_SESSION['successMsg'])): ?>
        <p class="success-msg"><?php echo $_SESSION['successMsg']; $_SESSION['successMsg'] = ''; ?></p>
    <?php endif; ?>

    <section class="card">
        <?php if (empty($rides)): ?>
            <p>No rides yet.</p>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr><th>Ride ID</th><th>From</th><th>To</th><th>Start</th><th>End</th><th>Fare</th><th>Status</th><th>Payment</th></tr>
                </thead>
                <tbody>
                <?php foreach ($rides as $r): ?>
                    <?php $payment = getRidePayment($r['Ride_ID']); ?>
                    <tr>
                        <td><?php echo $r['Ride_ID']; ?></td>
                        <td><?php echo htmlspecialchars($r['Start_Station_Name']); ?></td>
                        <td><?php echo $r['End_Station_Name'] ? htmlspecialchars($r['End_Station_Name']) : '-'; ?></td>
                        <td><?php echo date('d M Y, h:i A', strtotime($r['Start_Time'])); ?></td>
                        <td><?php echo $r['End_Time'] ? date('d M Y, h:i A', strtotime($r['End_Time'])) : '-'; ?></td>
                        <td>৳<?php echo number_format($r['Fare'], 2); ?></td>
                        <td><span class="status-badge status-<?php echo strtolower($r['Status']); ?>"><?php echo $r['Status']; ?></span></td>
                        <td>
                            <?php if ($r['Status'] === 'Completed'): ?>
                                <?php if ($payment): ?>
                                    <span class="status-badge status-paid">Paid</span>
                                <?php else: ?>
                                    <a href="payment.php?rideId=<?php echo $r['Ride_ID']; ?>" class="btn-primary btn-sm">Pay Now</a>
                                <?php endif; ?>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
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

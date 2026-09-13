<?php
session_start();
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}
require_once '../../model/User.php';
require_once '../../model/Station.php';
require_once '../../model/Payment.php';

$totalUsers = count(getAllUsers());
$totalCustomers = count(getAllUsers('Customer'));
$totalManagers = count(getAllUsers('Manager'));
$pendingRequests = count(getManagerRequests());
$stations = getStations();
$payments = getAllPayments();
$totalRevenue = 0;
foreach ($payments as $p) {
    if ($p['Payment_Status'] === 'Paid') $totalRevenue += $p['Amount'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Dashboard - E-Ride</title>
<link rel="stylesheet" href="../../css/style.css">
<link rel="stylesheet" href="../../css/admin.css">
</head>
<body>
<div class="app-shell">
<?php $activePage = 'home'; include '../Sidebar.php'; ?>
<div class="main-content">

<main class="page-container">
    <h1>Admin Dashboard</h1>

    <?php if (!empty($_SESSION['successMsg'])): ?>
        <p class="success-msg"><?php echo $_SESSION['successMsg']; $_SESSION['successMsg'] = ''; ?></p>
    <?php endif; ?>
    <?php if (!empty($_SESSION['globalErrMsg'])): ?>
        <p class="error-msg"><?php echo $_SESSION['globalErrMsg']; $_SESSION['globalErrMsg'] = ''; ?></p>
    <?php endif; ?>

    <div class="stat-row">
        <div class="stat-box"><span class="stat-value"><?php echo $totalUsers; ?></span><span class="stat-label">Total Users</span></div>
        <div class="stat-box"><span class="stat-value"><?php echo $totalCustomers; ?></span><span class="stat-label">Customers</span></div>
        <div class="stat-box"><span class="stat-value"><?php echo $totalManagers; ?></span><span class="stat-label">Managers</span></div>
        <div class="stat-box"><span class="stat-value"><?php echo $pendingRequests; ?></span><span class="stat-label">Pending Requests</span></div>
        <div class="stat-box"><span class="stat-value"><?php echo count($stations); ?></span><span class="stat-label">Stations</span></div>
        <div class="stat-box"><span class="stat-value">৳<?php echo number_format($totalRevenue, 2); ?></span><span class="stat-label">Total Revenue</span></div>
    </div>

    <section class="card">
        <h2>Quick Links</h2>
        <a href="users.php" class="btn-secondary">Manage Users</a>
        <a href="managerRequests.php" class="btn-secondary">Manager Requests</a>
        <a href="stations.php" class="btn-secondary">Manage Stations</a>
        <a href="addStation.php" class="btn-primary">+ Add Station</a>
        <a href="payments.php" class="btn-secondary">View Payments</a>
        <a href="transactions.php" class="btn-secondary">View Transactions</a>
    </section>

    <section class="card">
        <h2>Stations Overview</h2>
        <table class="data-table">
            <thead><tr><th>Station</th><th>Manager</th><th>Available</th><th>Capacity</th><th>Status</th></tr></thead>
            <tbody>
            <?php foreach ($stations as $s): ?>
                <tr>
                    <td><?php echo htmlspecialchars($s['Station_Name']); ?></td>
                    <td><?php echo $s['Manager_Name'] ? htmlspecialchars($s['Manager_Name']) : '<em>Unassigned</em>'; ?></td>
                    <td><?php echo $s['Available_Cycle_Quantity']; ?></td>
                    <td><?php echo $s['Capacity']; ?></td>
                    <td><span class="status-badge status-<?php echo strtolower($s['Status']); ?>"><?php echo $s['Status']; ?></span></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>

</div>
</div>
</body>
</html>

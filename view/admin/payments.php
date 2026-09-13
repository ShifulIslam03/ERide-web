<?php
session_start();
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}
require_once '../../model/Payment.php';

$payments = getAllPayments();

$totalPaid = 0;
$totalPending = 0;
$totalFailed = 0;
foreach ($payments as $p) {
    if ($p['Payment_Status'] === 'Paid') $totalPaid += $p['Amount'];
    elseif ($p['Payment_Status'] === 'Pending') $totalPending += $p['Amount'];
    elseif ($p['Payment_Status'] === 'Failed') $totalFailed += $p['Amount'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Payments - E-Ride</title>
<link rel="stylesheet" href="../../css/style.css">
<link rel="stylesheet" href="../../css/admin.css">
</head>
<body>
<div class="app-shell">
<?php $activePage = 'payments'; include '../Sidebar.php'; ?>
<div class="main-content">

<main class="page-container">
    <h1>Payments Overview</h1>

    <?php if (!empty($_SESSION['successMsg'])): ?>
        <p class="success-msg"><?php echo $_SESSION['successMsg']; $_SESSION['successMsg'] = ''; ?></p>
    <?php endif; ?>

    <div class="stat-row">
        <div class="stat-box"><span class="stat-value">৳<?php echo number_format($totalPaid, 2); ?></span><span class="stat-label">Total Paid</span></div>
        <div class="stat-box"><span class="stat-value">৳<?php echo number_format($totalPending, 2); ?></span><span class="stat-label">Pending</span></div>
        <div class="stat-box"><span class="stat-value">৳<?php echo number_format($totalFailed, 2); ?></span><span class="stat-label">Failed</span></div>
    </div>

    <section class="card">
        <?php if (empty($payments)): ?>
            <p>No payments recorded yet.</p>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr><th>Payment ID</th><th>Customer</th><th>Ride ID</th><th>Amount</th><th>Method</th><th>Date</th><th>Status</th><th>Update</th></tr>
                </thead>
                <tbody>
                <?php foreach ($payments as $p): ?>
                    <tr>
                        <td><?php echo $p['Payment_ID']; ?></td>
                        <td><?php echo htmlspecialchars($p['Customer_Name']); ?></td>
                        <td><?php echo $p['Ride_ID']; ?></td>
                        <td>৳<?php echo number_format($p['Amount'], 2); ?></td>
                        <td><?php echo htmlspecialchars($p['Payment_Method']); ?></td>
                        <td><?php echo date('d M Y, h:i A', strtotime($p['Payment_Date'])); ?></td>
                        <td><span class="status-badge status-<?php echo strtolower($p['Payment_Status']); ?>"><?php echo $p['Payment_Status']; ?></span></td>
                        <td>
                            <form method="post" action="../../controller/PaymentController.php" class="inline-form">
                                <input type="hidden" name="action" value="updateStatus">
                                <input type="hidden" name="paymentId" value="<?php echo $p['Payment_ID']; ?>">
                                <select name="status" onchange="this.form.submit()">
                                    <option value="Pending" <?php echo $p['Payment_Status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="Paid" <?php echo $p['Payment_Status'] === 'Paid' ? 'selected' : ''; ?>>Paid</option>
                                    <option value="Failed" <?php echo $p['Payment_Status'] === 'Failed' ? 'selected' : ''; ?>>Failed</option>
                                </select>
                            </form>
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

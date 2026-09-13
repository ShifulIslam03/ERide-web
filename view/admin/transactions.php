<?php
session_start();
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}
require_once '../../model/Payment.php';

$payments = getAllPayments();
$search = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($search !== '') {
    $payments = array_filter($payments, function($p) use ($search) {
        $needle = strtolower($search);
        return strpos(strtolower($p['Transaction_ID'] ?? ''), $needle) !== false
            || strpos(strtolower($p['Phone_Number']), $needle) !== false
            || strpos(strtolower($p['Customer_Name']), $needle) !== false;
    });
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Transactions - E-Ride</title>
<link rel="stylesheet" href="../../css/style.css">
<link rel="stylesheet" href="../../css/admin.css">
</head>
<body>
<div class="app-shell">
<?php $activePage = 'transactions'; include '../Sidebar.php'; ?>
<div class="main-content">

<main class="page-container">
    <h1>Transaction Ledger</h1>

    <section class="card">
        <form method="get" action="transactions.php" class="inline-form">
            <input type="text" name="q" placeholder="Search by transaction ID, phone, or name" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn-secondary">Search</button>
            <?php if ($search !== ''): ?>
                <a href="transactions.php" class="btn-secondary">Clear</a>
            <?php endif; ?>
        </form>

        <?php if (empty($payments)): ?>
            <p>No transactions found.</p>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr><th>Transaction ID</th><th>Customer</th><th>Phone</th><th>Amount</th><th>Method</th><th>Status</th><th>Date</th></tr>
                </thead>
                <tbody>
                <?php foreach ($payments as $p): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($p['Transaction_ID'] ?? '-'); ?></td>
                        <td><?php echo htmlspecialchars($p['Customer_Name']); ?></td>
                        <td><?php echo htmlspecialchars($p['Phone_Number']); ?></td>
                        <td>৳<?php echo number_format($p['Amount'], 2); ?></td>
                        <td><?php echo htmlspecialchars($p['Payment_Method']); ?></td>
                        <td><span class="status-badge status-<?php echo strtolower($p['Payment_Status']); ?>"><?php echo $p['Payment_Status']; ?></span></td>
                        <td><?php echo date('d M Y, h:i A', strtotime($p['Payment_Date'])); ?></td>
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

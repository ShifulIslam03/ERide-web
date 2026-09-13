<?php
session_start();
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}
require_once '../../model/User.php';

$requests = getManagerRequests();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Manager Requests - E-Ride</title>
<link rel="stylesheet" href="../../css/style.css">
<link rel="stylesheet" href="../../css/admin.css">
</head>
<body>
<div class="app-shell">
<?php $activePage = 'requests'; include '../Sidebar.php'; ?>
<div class="main-content">

<main class="page-container">
    <h1>Manager Candidate Requests</h1>

    <?php if (!empty($_SESSION['successMsg'])): ?>
        <p class="success-msg"><?php echo $_SESSION['successMsg']; $_SESSION['successMsg'] = ''; ?></p>
    <?php endif; ?>

    <section class="card">
        <?php if (empty($requests)): ?>
            <p>No pending manager requests.</p>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr><th>Phone</th><th>Name</th><th>Area</th><th>Requested On</th><th>Action</th></tr>
                </thead>
                <tbody>
                <?php foreach ($requests as $r): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($r['Phone_Number']); ?></td>
                        <td><?php echo htmlspecialchars($r['Name']); ?></td>
                        <td><?php echo htmlspecialchars($r['Area']); ?></td>
                        <td><?php echo date('d M Y, h:i A', strtotime($r['Manager_Request_Date'])); ?></td>
                        <td>
                            <form method="post" action="../../controller/AdminController.php" class="inline-form">
                                <input type="hidden" name="action" value="approveManager">
                                <input type="hidden" name="phone" value="<?php echo htmlspecialchars($r['Phone_Number']); ?>">
                                <button type="submit" class="btn-primary btn-sm">Approve</button>
                            </form>
                            <form method="post" action="../../controller/AdminController.php" class="inline-form">
                                <input type="hidden" name="action" value="rejectManager">
                                <input type="hidden" name="phone" value="<?php echo htmlspecialchars($r['Phone_Number']); ?>">
                                <button type="submit" class="btn-danger btn-sm">Reject</button>
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

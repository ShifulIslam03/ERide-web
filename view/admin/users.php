<?php
session_start();
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}
require_once '../../model/User.php';

$roleFilter = isset($_GET['role']) ? $_GET['role'] : '';
$users = $roleFilter ? getAllUsers($roleFilter) : getAllUsers();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Manage Users - E-Ride</title>
<link rel="stylesheet" href="../../css/style.css">
<link rel="stylesheet" href="../../css/admin.css">
</head>
<body>
<div class="app-shell">
<?php $activePage = 'users'; include '../Sidebar.php'; ?>
<div class="main-content">

<main class="page-container">
    <h1>Manage Users</h1>

    <?php if (!empty($_SESSION['successMsg'])): ?>
        <p class="success-msg"><?php echo $_SESSION['successMsg']; $_SESSION['successMsg'] = ''; ?></p>
    <?php endif; ?>
    <?php if (!empty($_SESSION['globalErrMsg'])): ?>
        <p class="error-msg"><?php echo $_SESSION['globalErrMsg']; $_SESSION['globalErrMsg'] = ''; ?></p>
    <?php endif; ?>

    <section class="card">
        <form method="get" action="users.php" class="inline-form">
            <label for="role">Filter by role:</label>
            <select name="role" id="role" onchange="this.form.submit()">
                <option value="">All</option>
                <option value="Customer" <?php echo $roleFilter === 'Customer' ? 'selected' : ''; ?>>Customer</option>
                <option value="Manager" <?php echo $roleFilter === 'Manager' ? 'selected' : ''; ?>>Manager</option>
                <option value="Admin" <?php echo $roleFilter === 'Admin' ? 'selected' : ''; ?>>Admin</option>
            </select>
        </form>

        <table class="data-table">
            <thead>
                <tr><th>Phone</th><th>Name</th><th>Gender</th><th>Area</th><th>Role</th><th>Change Role</th><th>Action</th></tr>
            </thead>
            <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><?php echo htmlspecialchars($u['Phone_Number']); ?></td>
                    <td><?php echo htmlspecialchars($u['Name']); ?></td>
                    <td><?php echo htmlspecialchars($u['Gender']); ?></td>
                    <td><?php echo htmlspecialchars($u['Area']); ?></td>
                    <td><span class="status-badge"><?php echo htmlspecialchars($u['Role']); ?></span></td>
                    <td>
                        <?php if ($u['Phone_Number'] !== $_SESSION['phone']): ?>
                        <form method="post" action="../../controller/AdminController.php" class="inline-form">
                            <input type="hidden" name="action" value="updateRole">
                            <input type="hidden" name="phone" value="<?php echo htmlspecialchars($u['Phone_Number']); ?>">
                            <select name="role" onchange="this.form.submit()">
                                <option value="Customer" <?php echo $u['Role'] === 'Customer' ? 'selected' : ''; ?>>Customer</option>
                                <option value="Manager" <?php echo $u['Role'] === 'Manager' ? 'selected' : ''; ?>>Manager</option>
                                <option value="Admin" <?php echo $u['Role'] === 'Admin' ? 'selected' : ''; ?>>Admin</option>
                            </select>
                        </form>
                        <?php else: ?>
                            <small>(You)</small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($u['Phone_Number'] !== $_SESSION['phone']): ?>
                        <form method="post" action="../../controller/AdminController.php" class="inline-form" onsubmit="return confirm('Delete this user account permanently?')">
                            <input type="hidden" name="action" value="deleteUser">
                            <input type="hidden" name="phone" value="<?php echo htmlspecialchars($u['Phone_Number']); ?>">
                            <button type="submit" class="btn-danger btn-sm">Delete</button>
                        </form>
                        <?php endif; ?>
                    </td>
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

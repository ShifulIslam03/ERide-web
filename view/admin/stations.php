<?php
session_start();
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}
require_once '../../model/Station.php';
require_once '../../model/User.php';

$stations = getStations();
$managers = getAllUsers('Manager');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Manage Stations - E-Ride</title>
<link rel="stylesheet" href="../../css/style.css">
<link rel="stylesheet" href="../../css/admin.css">
</head>
<body>
<div class="app-shell">
<?php $activePage = 'stations'; include '../Sidebar.php'; ?>
<div class="main-content">

<main class="page-container">
    <h1>Manage Stations</h1>

    <?php if (!empty($_SESSION['successMsg'])): ?>
        <p class="success-msg"><?php echo $_SESSION['successMsg']; $_SESSION['successMsg'] = ''; ?></p>
    <?php endif; ?>
    <?php if (!empty($_SESSION['globalErrMsg'])): ?>
        <p class="error-msg"><?php echo $_SESSION['globalErrMsg']; $_SESSION['globalErrMsg'] = ''; ?></p>
    <?php endif; ?>

    <a href="addStation.php" class="btn-primary">+ Add Station</a>

    <section class="card">
        <table class="data-table">
            <thead>
                <tr><th>Name</th><th>Area</th><th>Capacity</th><th>Available</th><th>Status</th><th>Manager</th><th>Assign Manager</th><th>Action</th></tr>
            </thead>
            <tbody>
            <?php foreach ($stations as $s): ?>
                <tr>
                    <td><?php echo htmlspecialchars($s['Station_Name']); ?></td>
                    <td><?php echo htmlspecialchars($s['Area']); ?></td>
                    <td><?php echo $s['Capacity']; ?></td>
                    <td><?php echo $s['Available_Cycle_Quantity']; ?></td>
                    <td><span class="status-badge status-<?php echo strtolower($s['Status']); ?>"><?php echo $s['Status']; ?></span></td>
                    <td><?php echo $s['Manager_Name'] ? htmlspecialchars($s['Manager_Name']) : '<em>None</em>'; ?></td>
                    <td>
                        <form method="post" action="../../controller/StationController.php" class="inline-form">
                            <input type="hidden" name="action" value="assignManager">
                            <input type="hidden" name="stationId" value="<?php echo $s['Station_ID']; ?>">
                            <select name="managerPhone" onchange="this.form.submit()">
                                <option value="">-- Select Manager --</option>
                                <?php foreach ($managers as $m): ?>
                                    <option value="<?php echo htmlspecialchars($m['Phone_Number']); ?>" <?php echo ($s['Manager_Phone_Number'] === $m['Phone_Number']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($m['Name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </td>
                    <td>
                        <a href="editStation.php?stationId=<?php echo $s['Station_ID']; ?>" class="btn-secondary btn-sm">Edit</a>
                        <?php if ($s['Status'] === 'Active'): ?>
                        <form method="post" action="../../controller/StationController.php" class="inline-form" onsubmit="return confirm('Deactivate this station?')">
                            <input type="hidden" name="action" value="deleteStation">
                            <input type="hidden" name="stationId" value="<?php echo $s['Station_ID']; ?>">
                            <button type="submit" class="btn-danger btn-sm">Deactivate</button>
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

<?php
session_start();
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['role'] !== 'Manager') {
    header("Location: ../login.php");
    exit();
}
require_once '../../model/Station.php';
require_once '../../model/Vehicle.php';

$phone = $_SESSION['phone'];
$stations = getStationByManager($phone);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Manager Dashboard - E-Ride</title>
<link rel="stylesheet" href="../../css/style.css">
<link rel="stylesheet" href="../../css/manager.css">
</head>
<body>
<div class="app-shell">
<?php $activePage = 'home'; include '../Sidebar.php'; ?>
<div class="main-content">

<main class="page-container">
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></h1>

    <?php if (!empty($_SESSION['successMsg'])): ?>
        <p class="success-msg"><?php echo $_SESSION['successMsg']; $_SESSION['successMsg'] = ''; ?></p>
    <?php endif; ?>
    <?php if (!empty($_SESSION['globalErrMsg'])): ?>
        <p class="error-msg"><?php echo $_SESSION['globalErrMsg']; $_SESSION['globalErrMsg'] = ''; ?></p>
    <?php endif; ?>

    <?php if (empty($stations)): ?>
        <section class="card">
            <p>You have not been assigned to a station yet. Please contact the Admin.</p>
        </section>
    <?php else: ?>
        <?php foreach ($stations as $station): ?>
        <section class="card">
            <h2><?php echo htmlspecialchars($station['Station_Name']); ?></h2>
            <p><?php echo htmlspecialchars($station['Area'] . ', ' . $station['Block'] . ', ' . $station['Road']); ?></p>
            <div class="stat-row">
                <div class="stat-box">
                    <span class="stat-value" id="liveAvailability-<?php echo $station['Station_ID']; ?>"><?php echo $station['Available_Cycle_Quantity']; ?></span>
                    <span class="stat-label">Available Bikes</span>
                </div>
                <div class="stat-box">
                    <span class="stat-value"><?php echo $station['Capacity']; ?></span>
                    <span class="stat-label">Capacity</span>
                </div>
                <div class="stat-box">
                    <span class="stat-value"><?php echo count(getVehicles($station['Station_ID'])); ?></span>
                    <span class="stat-label">Total Bikes</span>
                </div>
            </div>
            <a href="station.php?id=<?php echo $station['Station_ID']; ?>" class="btn-secondary">Manage Station</a>
            <a href="vehicles.php?id=<?php echo $station['Station_ID']; ?>" class="btn-secondary">Manage Bikes</a>
            <a href="addVehicle.php?id=<?php echo $station['Station_ID']; ?>" class="btn-primary">Add New Bike</a>
        </section>
        <?php endforeach; ?>
    <?php endif; ?>
</main>

</div>
</div>
<script src="../../js/station.js"></script>
<?php foreach ($stations as $station): ?>
<script>startAvailabilityPolling(<?php echo $station['Station_ID']; ?>, 'liveAvailability-<?php echo $station['Station_ID']; ?>');</script>
<?php endforeach; ?>
</body>
</html>

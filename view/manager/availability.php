<?php
session_start();
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['role'] !== 'Manager') {
    header("Location: ../login.php");
    exit();
}
require_once '../../model/Station.php';
require_once '../../model/Vehicle.php';

$stations = getStationByManager($_SESSION['phone']);
$stationId = isset($_GET['id']) ? intval($_GET['id']) : (!empty($stations) ? $stations[0]['Station_ID'] : 0);
$station = getManagerStation($_SESSION['phone'], $stationId);
$vehicles = $station ? getVehicles($station['Station_ID']) : [];
$statusCounts = ['Available' => 0, 'Booked' => 0, 'In Ride' => 0, 'Maintenance' => 0];
foreach ($vehicles as $v) {
    if (isset($statusCounts[$v['Status']])) {
        $statusCounts[$v['Status']]++;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Station Availability - E-Ride</title>
<link rel="stylesheet" href="../../css/style.css">
<link rel="stylesheet" href="../../css/manager.css">
</head>
<body>
<div class="app-shell">
<?php $activePage = 'availability'; include '../Sidebar.php'; ?>
<div class="main-content">

<main class="page-container">
    <h1>Station Availability</h1>

    <?php if (count($stations) > 1): ?>
        <div class="station-switch">
            <?php foreach ($stations as $s): ?>
                <a href="availability.php?id=<?php echo $s['Station_ID']; ?>" class="btn-secondary btn-sm <?php echo $station && $s['Station_ID'] == $station['Station_ID'] ? 'active' : ''; ?>"><?php echo htmlspecialchars($s['Station_Name']); ?></a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (!$station): ?>
        <section class="card"><p>No station assigned.</p></section>
    <?php else: ?>
        <section class="card">
            <h2><?php echo htmlspecialchars($station['Station_Name']); ?></h2>
            <div class="stat-row">
                <div class="stat-box">
                    <span class="stat-value" id="liveAvailability"><?php echo $station['Available_Cycle_Quantity']; ?></span>
                    <span class="stat-label">Available Now</span>
                </div>
                <div class="stat-box">
                    <span class="stat-value"><?php echo $statusCounts['Booked']; ?></span>
                    <span class="stat-label">Booked</span>
                </div>
                <div class="stat-box">
                    <span class="stat-value"><?php echo $statusCounts['In Ride']; ?></span>
                    <span class="stat-label">In Ride</span>
                </div>
                <div class="stat-box">
                    <span class="stat-value"><?php echo $statusCounts['Maintenance']; ?></span>
                    <span class="stat-label">Maintenance</span>
                </div>
            </div>
            <p><small>This panel refreshes automatically every 10 seconds.</small></p>
        </section>
    <?php endif; ?>
</main>

</div>
</div>
<script src="../../js/station.js"></script>
<?php if ($station): ?>
<script>startAvailabilityPolling(<?php echo $station['Station_ID']; ?>, 'liveAvailability');</script>
<?php endif; ?>
</body>
</html>

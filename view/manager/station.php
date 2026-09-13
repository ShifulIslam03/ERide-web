<?php
session_start();
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['role'] !== 'Manager') {
    header("Location: ../login.php");
    exit();
}
require_once '../../model/Station.php';

$stations = getStationByManager($_SESSION['phone']);
if (empty($stations)) {
    header("Location: dashboard.php");
    exit();
}
$stationId = isset($_GET['id']) ? intval($_GET['id']) : $stations[0]['Station_ID'];
$station = getManagerStation($_SESSION['phone'], $stationId);
if (!$station) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>My Station - E-Ride</title>
<link rel="stylesheet" href="../../css/style.css">
<link rel="stylesheet" href="../../css/manager.css">
</head>
<body>
<div class="app-shell">
<?php $activePage = 'station'; include '../Sidebar.php'; ?>
<div class="main-content">

<main class="page-container">
    <h1>My Station</h1>

    <?php if (count($stations) > 1): ?>
        <div class="station-switch">
            <?php foreach ($stations as $s): ?>
                <a href="station.php?id=<?php echo $s['Station_ID']; ?>" class="btn-secondary btn-sm <?php echo $s['Station_ID'] == $station['Station_ID'] ? 'active' : ''; ?>"><?php echo htmlspecialchars($s['Station_Name']); ?></a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['successMsg'])): ?>
        <p class="success-msg"><?php echo $_SESSION['successMsg']; $_SESSION['successMsg'] = ''; ?></p>
    <?php endif; ?>

    <section class="card">
        <table class="profile-table">
            <tr><th>Station Name</th><td><?php echo htmlspecialchars($station['Station_Name']); ?></td></tr>
            <tr><th>Area</th><td><?php echo htmlspecialchars($station['Area']); ?></td></tr>
            <tr><th>Block</th><td><?php echo htmlspecialchars($station['Block']); ?></td></tr>
            <tr><th>Road</th><td><?php echo htmlspecialchars($station['Road']); ?></td></tr>
            <tr><th>Capacity</th><td><?php echo $station['Capacity']; ?></td></tr>
            <tr><th>Available Bikes</th><td id="liveAvailability"><?php echo $station['Available_Cycle_Quantity']; ?></td></tr>
            <tr><th>Status</th><td><?php echo htmlspecialchars($station['Status']); ?></td></tr>
        </table>
        <a href="editStation.php?id=<?php echo $station['Station_ID']; ?>" class="btn-primary">Edit Station Information</a>
    </section>
</main>

</div>
</div>
<script src="../../js/station.js"></script>
<script>startAvailabilityPolling(<?php echo $station['Station_ID']; ?>, 'liveAvailability');</script>
</body>
</html>

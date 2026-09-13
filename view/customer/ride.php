<?php
session_start();
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['role'] !== 'Customer') {
    header("Location: ../login.php");
    exit();
}
require_once '../../model/Ride.php';
require_once '../../model/Station.php';
require_once '../../model/Vehicle.php';

$phone = $_SESSION['phone'];
$activeRide = getActiveRide($phone);
$vehicleType = isset($_GET['type']) ? trim($_GET['type']) : '';

if ($vehicleType !== '') {
    $stations = getStationsWithVehicleType($vehicleType);
} else {
    $stations = getStations();
}
$allStationsForEnd = getStations();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Manage Ride - E-Ride</title>
<link rel="stylesheet" href="../../css/style.css">
<link rel="stylesheet" href="../../css/customer.css">
</head>
<body>
<div class="app-shell">
<?php $activePage = 'rent'; include '../Sidebar.php'; ?>
<div class="main-content">
<main class="page-container">
    <a href="bookBike.php" class="back-link btn-secondary btn-sm">&larr; Back to Catalog</a>
    <h1><?php echo $vehicleType ? htmlspecialchars($vehicleType) : 'Manage Your Ride'; ?></h1>

    <?php if (!empty($_SESSION['successMsg'])): ?>
        <p class="success-msg"><?php echo $_SESSION['successMsg']; $_SESSION['successMsg'] = ''; ?></p>
    <?php endif; ?>
    <?php if (!empty($_SESSION['globalErrMsg'])): ?>
        <p class="error-msg"><?php echo $_SESSION['globalErrMsg']; $_SESSION['globalErrMsg'] = ''; ?></p>
    <?php endif; ?>

    <section class="card ride-panel">
        <div class="ride-controls-row">
            <form method="post" action="../../controller/RideController.php" id="startForm" class="ride-inline-form">
                <input type="hidden" name="action" value="quickStart">
                <input type="hidden" name="vehicleType" value="<?php echo htmlspecialchars($vehicleType); ?>">

                <div class="form-group ride-control">
                    <label>Start Station</label>
                    <select name="stationId" id="startStationSelect" <?php echo $activeRide ? 'disabled' : ''; ?> onchange="highlightStationRow(this.value)">
                        <?php if (empty($stations)): ?>
                            <option value="">No stations available</option>
                        <?php endif; ?>
                        <?php foreach ($stations as $s): ?>
                            <option value="<?php echo $s['Station_ID']; ?>"><?php echo htmlspecialchars($s['Station_Name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group ride-control">
                    <label>Number of Cycle</label>
                    <input type="number" name="quantity" id="quantityInput" value="1" min="1" max="10" <?php echo $activeRide ? 'disabled' : ''; ?>>
                </div>

                <button type="submit" class="btn-primary" <?php echo ($activeRide || empty($stations)) ? 'disabled' : ''; ?>>Start</button>
            </form>

            <form method="post" action="../../controller/RideController.php" id="endForm" class="ride-inline-form">
                <input type="hidden" name="action" value="completeRide">
                <input type="hidden" name="rideId" value="<?php echo $activeRide ? $activeRide['Ride_ID'] : ''; ?>">

                <div class="form-group ride-control">
                    <label>End Station</label>
                    <select name="endStationId" <?php echo !$activeRide ? 'disabled' : ''; ?>>
                        <?php foreach ($allStationsForEnd as $s): ?>
                            <option value="<?php echo $s['Station_ID']; ?>"><?php echo htmlspecialchars($s['Station_Name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn-primary" <?php echo !$activeRide ? 'disabled' : ''; ?>>End and Pay</button>
            </form>
        </div>

        <?php if ($activeRide): ?>
            <div class="alert-card" style="margin-top:16px;">
                <p>Ride #<?php echo $activeRide['Ride_ID']; ?> in progress since <?php echo date('d M, h:i A', strtotime($activeRide['Start_Time'])); ?> &mdash; <?php echo count($activeRide['Vehicles']); ?> bike(s) from <?php echo htmlspecialchars($activeRide['Start_Station_Name']); ?>.</p>
            </div>
        <?php endif; ?>

        <button type="button" class="btn-secondary" onclick="toggleStationTable()" style="margin-top:16px;">Station Details</button>

        <div id="stationTableWrap" class="station-table-wrap">
            <table class="data-table" id="stationTable">
                <thead>
                    <tr><th></th><th>Station_Name</th><th>Block</th><th>Road</th><th>Available</th></tr>
                </thead>
                <tbody>
                <?php foreach ($stations as $s): ?>
                    <tr data-station-id="<?php echo $s['Station_ID']; ?>">
                        <td class="row-marker"></td>
                        <td><?php echo htmlspecialchars($s['Station_Name']); ?></td>
                        <td><?php echo htmlspecialchars($s['Block']); ?></td>
                        <td><?php echo htmlspecialchars($s['Road']); ?></td>
                        <td><?php echo isset($s['Type_Available_Count']) ? $s['Type_Available_Count'] : $s['Available_Cycle_Quantity']; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="guidance-box">
            <strong>Guidance:</strong>
            <ol>
                <li>Helmet and safety gear included at no extra cost.</li>
                <li>Simple rent and return process with clear start and end options.</li>
                <li>Track your trip history and manage payments easily.</li>
            </ol>
        </div>
    </section>
</main>
</div>
</div>
<script>
function toggleStationTable() {
    document.getElementById('stationTableWrap').classList.toggle('open');
}
function highlightStationRow(stationId) {
    document.querySelectorAll('#stationTable tbody tr').forEach(function (tr) {
        tr.classList.toggle('row-selected', tr.getAttribute('data-station-id') === String(stationId));
    });
}
document.addEventListener('DOMContentLoaded', function () {
    var sel = document.getElementById('startStationSelect');
    if (sel && sel.value) highlightStationRow(sel.value);
});
</script>
</body>
</html>

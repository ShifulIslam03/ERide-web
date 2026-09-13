<?php
session_start();
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}
require_once '../../model/Station.php';

$stationId = isset($_GET['stationId']) ? intval($_GET['stationId']) : 0;
$station = $stationId ? getStation($stationId) : null;

if (!$station) {
    header("Location: stations.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Edit Station - E-Ride</title>
<link rel="stylesheet" href="../../css/style.css">
<link rel="stylesheet" href="../../css/admin.css">
</head>
<body>
<div class="app-shell">
<?php $activePage = 'stations'; include '../Sidebar.php'; ?>
<div class="main-content">

<main class="page-container">
    <a href="stations.php" class="back-link btn-secondary btn-sm">&larr; Back</a>
    <h1>Edit Station</h1>

    <?php if (!empty($_SESSION['globalErrMsg'])): ?>
        <p class="error-msg"><?php echo $_SESSION['globalErrMsg']; $_SESSION['globalErrMsg'] = ''; ?></p>
    <?php endif; ?>

    <section class="card">
        <form method="post" action="../../controller/StationController.php" onsubmit="return validateStationForm(this)" novalidate>
            <input type="hidden" name="action" value="updateStation">
            <input type="hidden" name="stationId" value="<?php echo $station['Station_ID']; ?>">

            <div class="form-group">
                <label for="stationName">Station Name</label>
                <input type="text" name="stationName" id="stationName" value="<?php echo htmlspecialchars($station['Station_Name']); ?>">
                <span id="nameErrMsg" class="field-error"><?php echo isset($_SESSION['nameErrMsg']) ? $_SESSION['nameErrMsg'] : ''; $_SESSION['nameErrMsg'] = ''; ?></span>
            </div>

            <div class="form-group">
                <label for="area">Area</label>
                <input type="text" name="area" id="area" value="<?php echo htmlspecialchars($station['Area']); ?>">
                <span id="areaErrMsg" class="field-error"></span>
            </div>

            <div class="form-group">
                <label for="block">Block</label>
                <input type="text" name="block" id="block" value="<?php echo htmlspecialchars($station['Block']); ?>">
                <span id="blockErrMsg" class="field-error"></span>
            </div>

            <div class="form-group">
                <label for="road">Road</label>
                <input type="text" name="road" id="road" value="<?php echo htmlspecialchars($station['Road']); ?>">
                <span id="roadErrMsg" class="field-error"></span>
            </div>

            <div class="form-group">
                <label for="capacity">Capacity</label>
                <input type="number" name="capacity" id="capacity" min="1" value="<?php echo $station['Capacity']; ?>">
                <span id="capacityErrMsg" class="field-error"><?php echo isset($_SESSION['capacityErrMsg']) ? $_SESSION['capacityErrMsg'] : ''; $_SESSION['capacityErrMsg'] = ''; ?></span>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status">
                    <option value="Active" <?php echo $station['Status'] === 'Active' ? 'selected' : ''; ?>>Active</option>
                    <option value="Inactive" <?php echo $station['Status'] === 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn-primary">Save Changes</button>
        </form>
    </section>
</main>

</div>
</div>
<script src="../../js/station.js"></script>
</body>
</html>

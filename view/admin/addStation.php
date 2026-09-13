<?php
session_start();
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Add Station - E-Ride</title>
<link rel="stylesheet" href="../../css/style.css">
<link rel="stylesheet" href="../../css/admin.css">
</head>
<body>
<div class="app-shell">
<?php $activePage = 'stations'; include '../Sidebar.php'; ?>
<div class="main-content">

<main class="page-container">
    <a href="stations.php" class="back-link btn-secondary btn-sm">&larr; Back</a>
    <h1>Add New Station</h1>

    <?php if (!empty($_SESSION['globalErrMsg'])): ?>
        <p class="error-msg"><?php echo $_SESSION['globalErrMsg']; $_SESSION['globalErrMsg'] = ''; ?></p>
    <?php endif; ?>

    <section class="card">
        <form method="post" action="../../controller/StationController.php" onsubmit="return validateStationForm(this)" novalidate>
            <input type="hidden" name="action" value="addStation">

            <div class="form-group">
                <label for="stationName">Station Name</label>
                <input type="text" name="stationName" id="stationName">
                <span id="nameErrMsg" class="field-error"><?php echo isset($_SESSION['nameErrMsg']) ? $_SESSION['nameErrMsg'] : ''; $_SESSION['nameErrMsg'] = ''; ?></span>
            </div>

            <div class="form-group">
                <label for="area">Area</label>
                <input type="text" name="area" id="area">
                <span id="areaErrMsg" class="field-error"><?php echo isset($_SESSION['areaErrMsg']) ? $_SESSION['areaErrMsg'] : ''; $_SESSION['areaErrMsg'] = ''; ?></span>
            </div>

            <div class="form-group">
                <label for="block">Block</label>
                <input type="text" name="block" id="block">
                <span id="blockErrMsg" class="field-error"><?php echo isset($_SESSION['blockErrMsg']) ? $_SESSION['blockErrMsg'] : ''; $_SESSION['blockErrMsg'] = ''; ?></span>
            </div>

            <div class="form-group">
                <label for="road">Road</label>
                <input type="text" name="road" id="road">
                <span id="roadErrMsg" class="field-error"><?php echo isset($_SESSION['roadErrMsg']) ? $_SESSION['roadErrMsg'] : ''; $_SESSION['roadErrMsg'] = ''; ?></span>
            </div>

            <div class="form-group">
                <label for="capacity">Capacity</label>
                <input type="number" name="capacity" id="capacity" min="1">
                <span id="capacityErrMsg" class="field-error"><?php echo isset($_SESSION['capacityErrMsg']) ? $_SESSION['capacityErrMsg'] : ''; $_SESSION['capacityErrMsg'] = ''; ?></span>
            </div>

            <button type="submit" class="btn-primary">Add Station</button>
        </form>
    </section>
</main>

</div>
</div>
<script src="../../js/station.js"></script>
</body>
</html>

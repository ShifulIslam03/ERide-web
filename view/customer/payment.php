<?php
session_start();
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['role'] !== 'Customer') {
    header("Location: ../login.php");
    exit();
}
require_once '../../model/Ride.php';
require_once '../../model/Payment.php';
require_once '../../model/User.php';

$rideId = isset($_GET['rideId']) ? intval($_GET['rideId']) : 0;
$ride = $rideId ? getRide($rideId) : null;

if (!$ride || $ride['Phone_Number'] !== $_SESSION['phone'] || $ride['Status'] !== 'Completed') {
    header("Location: rideHistory.php");
    exit();
}

$existingPayment = getRidePayment($rideId);
$user = getUser($_SESSION['phone']);
$nameParts = explode(' ', trim($user['Name']), 2);
$firstName = $nameParts[0];
$lastName = isset($nameParts[1]) ? $nameParts[1] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Payment - E-Ride</title>
<link rel="stylesheet" href="../../css/style.css">
<link rel="stylesheet" href="../../css/customer.css">
</head>
<body>
<div class="app-shell">
<?php $activePage = 'rides'; include '../Sidebar.php'; ?>
<div class="main-content">
<main class="page-container">
    <a href="rideHistory.php" class="back-link btn-secondary btn-sm">&larr; Back</a>

    <?php if (!empty($_SESSION['globalErrMsg'])): ?>
        <p class="error-msg"><?php echo $_SESSION['globalErrMsg']; $_SESSION['globalErrMsg'] = ''; ?></p>
    <?php endif; ?>

    <section class="card">
        <p class="fare-banner">Ride #<?php echo $ride['Ride_ID']; ?> &mdash; Total Fare: <strong>৳<?php echo number_format($ride['Fare'], 2); ?></strong></p>

        <?php if ($existingPayment): ?>
            <p class="success-msg">This ride has already been paid via <?php echo htmlspecialchars($existingPayment['Payment_Method']); ?> (Transaction ID: <?php echo htmlspecialchars($existingPayment['Transaction_ID']); ?>)</p>
        <?php else: ?>

            <div class="method-tabs">
                <span class="method-tab-label">Method</span>
                <label class="method-radio"><input type="radio" name="methodTab" value="Card" checked onchange="switchMethod('Card')"> Card</label>
                <label class="method-radio"><input type="radio" name="methodTab" value="Bkash" onchange="switchMethod('Bkash')"> Bkash</label>
            </div>

            <form method="post" action="../../controller/PaymentController.php" id="paymentForm" onsubmit="return validatePaymentForm(this)">
                <input type="hidden" name="action" value="pay">
                <input type="hidden" name="rideId" value="<?php echo $ride['Ride_ID']; ?>">
                <input type="hidden" name="method" id="methodField" value="Card">

                <div id="cardPanel" class="payment-panel">
                    <div class="payment-columns">
                        <div class="card-visual">
                            <div class="card-visual-brand">VISA</div>
                            <label>Card number</label>
                            <div class="card-number-display">0000 0000 0000 0000</div>
                            <div class="card-visual-row">
                                <div>
                                    <label>Month &amp; year</label>
                                    <div class="card-number-display small">00 / 00</div>
                                </div>
                                <div>
                                    <label>CVV code</label>
                                    <div class="card-number-display small">•••</div>
                                </div>
                            </div>
                        </div>

                        <div class="personal-info">
                            <h3>Personal Information</h3>
                            <div class="field-grid">
                                <div class="form-group"><input type="text" placeholder="First name" value="<?php echo htmlspecialchars($firstName); ?>"></div>
                                <div class="form-group"><input type="text" placeholder="Last name" value="<?php echo htmlspecialchars($lastName); ?>"></div>
                                <div class="form-group full"><input type="text" placeholder="Country"></div>
                                <div class="form-group"><input type="text" placeholder="City"></div>
                                <div class="form-group"><input type="text" placeholder="Zip code"></div>
                                <div class="form-group full"><input type="email" placeholder="E-mail"></div>
                                <div class="form-group full"><input type="text" placeholder="Phone number" value="<?php echo htmlspecialchars($user['Phone_Number']); ?>"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="bkashPanel" class="payment-panel" style="display:none;">
                    <h3>Select Payment Method</h3>
                    <p class="field-label">Saved Payment Method</p>
                    <div class="saved-method-card">
                        <span class="bkash-icon">bK</span>
                        <div>
                            <strong>bKash</strong><br>
                            <span>01XXX-XXXXXX</span><br>
                            <small><?php echo htmlspecialchars($user['Name']); ?></small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Payment Amount</label>
                        <input type="text" value="৳<?php echo number_format($ride['Fare'], 2); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label>Remarks</label>
                        <input type="text" placeholder="Comment">
                    </div>
                </div>

                <span id="methodErrMsg" class="field-error"></span>
                <button type="submit" class="btn-pay">Pay ৳<?php echo number_format($ride['Fare'], 2); ?></button>
            </form>
        <?php endif; ?>
    </section>
</main>
</div>
</div>
<script src="../../js/payment.js"></script>
<script>
function switchMethod(method) {
    document.getElementById('methodField').value = method;
    document.getElementById('cardPanel').style.display = (method === 'Card') ? 'block' : 'none';
    document.getElementById('bkashPanel').style.display = (method === 'Bkash') ? 'block' : 'none';
}
</script>
</body>
</html>

<?php
// E-Ride Services - Application Entry Point (Procedural, Role Detection, Figma Home Page)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] === true) {
    $role = $_SESSION['role'] ?? 'Customer';
    if ($role === 'Admin') {
        header("Location: view/admin/dashboard.php");
        exit();
    } elseif ($role === 'Manager') {
        header("Location: view/manager/dashboard.php");
        exit();
    } else {
        header("Location: view/customer/dashboard.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ride Services | Smart, Eco-Friendly, Affordable</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background-color: var(--figma-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 20px;
            min-height: 100vh;
        }
        .home-container {
            width: 100%;
            max-width: 960px;
            background: #9bb7c6;
            border-radius: 28px;
            padding: 24px 36px 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }
        .home-top-bar {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            margin-bottom: 20px;
        }
        .home-brand-center {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 1.8rem;
            font-weight: 800;
            color: #0f172a;
        }
        .home-about-btn {
            position: absolute;
            right: 0;
            background: #00b074;
            color: #ffffff !important;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 8px 24px;
            border-radius: var(--radius-pill);
            text-decoration: none;
            box-shadow: 0 4px 10px rgba(0, 176, 116, 0.25);
            transition: all 0.2s;
        }
        .home-about-btn:hover {
            background: #009662;
            transform: translateY(-1px);
        }
        .hero-tagline {
            color: #00b074;
            font-size: 1.7rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 6px;
        }
        .hero-subtext {
            color: #0f172a;
            font-size: 1.05rem;
            font-weight: 600;
            text-align: center;
            margin-bottom: 24px;
        }
        .hero-image-wrapper {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-bottom: 36px;
        }
        .hero-image-wrapper img {
            width: 100%;
            max-width: 820px;
            height: auto;
            border-radius: 20px;
            box-shadow: 0 8px 28px rgba(0, 0, 0, 0.12);
        }
        .home-action-buttons {
            display: flex;
            gap: 32px;
            width: 100%;
            max-width: 820px;
            justify-content: space-between;
        }
        .home-btn-pill {
            flex: 1;
            background: #00b074;
            color: #0f172a !important;
            font-size: 1.35rem;
            font-weight: 800;
            padding: 16px 0;
            border-radius: var(--radius-pill);
            text-align: center;
            text-decoration: none !important;
            box-shadow: 0 6px 16px rgba(0, 176, 116, 0.3);
            transition: all 0.2s;
        }
        .home-btn-pill:hover {
            background: #009662;
            transform: translateY(-2px);
        }
        @media (max-width: 680px) {
            .home-top-bar {
                flex-direction: column;
                gap: 12px;
            }
            .home-about-btn {
                position: static;
            }
            .home-action-buttons {
                flex-direction: column;
                gap: 16px;
            }
        }
    </style>
</head>
<body>

<div class="home-container">
    <div class="home-top-bar">
        <div class="home-brand-center">
            <span class="brand-badge">E</span>
            <span class="brand-name">-Ride Services</span>
        </div>
        <a href="#aboutModal" onclick="document.getElementById('aboutModal').style.display='flex'; return false;" class="home-about-btn">About</a>
    </div>

    <h2 class="hero-tagline">Smart. Eco-Friendly. Affordable.</h2>
    <p class="hero-subtext">Electric bike rental & food delivery across Bashundhara R/A, Dhaka</p>

    <div class="hero-image-wrapper">
        <img src="image.jpg" alt="E-Ride City Commute Banner">
    </div>

    <div class="home-action-buttons">
        <a href="view/login.php" class="home-btn-pill">Login</a>
        <a href="view/register.php" class="home-btn-pill">Register</a>
    </div>
</div>

<!-- About Modal Popup -->
<div id="aboutModal" class="figma-modal-backdrop" onclick="if(event.target===this) this.style.display='none';">
    <div class="figma-modal-card">
        <button class="modal-close-btn" onclick="document.getElementById('aboutModal').style.display='none';">&times;</button>
        <span class="modal-badge">About E-Ride Services</span>
        <p style="font-size: 0.95rem; color: #334155; line-height: 1.6; margin-bottom: 14px;">
            <strong>E-Ride Services</strong> is a self-driven, eco-friendly electric bike and scooter rental platform designed for students and professionals in <strong>Bashundhara R/A, Dhaka</strong>.
        </p>
        <ul style="padding-left: 20px; font-size: 0.9rem; color: #475569; line-height: 1.8;">
            <li>Rent electric bikes & scooters from nearby stations.</li>
            <li>Transparent fare: ৳2/min (minimum charge ৳3).</li>
            <li>Helmets and safety gear included at no extra cost.</li>
            <li>Instant return at any station with seamless digital payments.</li>
        </ul>
        <div style="text-align: center; margin-top: 20px;">
            <button class="btn-card-action" onclick="document.getElementById('aboutModal').style.display='none';">Close</button>
        </div>
    </div>
</div>

</body>
</html>

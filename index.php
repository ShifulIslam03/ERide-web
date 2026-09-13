<?php
session_start();

// If the user is already logged in, skip the landing page and go straight to
// their dashboard - same behavior the project had before this landing page.
if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']) {
    $role = strtolower($_SESSION['role']);
    header("Location: view/$role/dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>E-Ride Services</title>
<style>
    :root {
        --color-primary: #10b981;
        --color-primary-dark: #0b7d57;
        --color-page-bg: #9dbfcc;
        --color-card: #f3f4f6;
        --color-text: #111827;
        --color-text-muted: #33424f;
        --shadow: 0 1px 3px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.06);
    }
    * {
        box-sizing: border-box;
    }
    body {
        margin: 0;
        background: var(--color-page-bg);
        color: var(--color-text);
        font-family: 'Segoe UI', Roboto, Arial, sans-serif;
        line-height: 1.5;
    }
    a {
        text-decoration: none;
    }

    .landing-container {
        max-width: 760px;
        margin: 0 auto;
        padding: 24px 20px 50px;
    }

    
    .landing-header {
        display: flex;
        align-items: center;
        justify-content: center; 
        position: relative;      
        margin-bottom: 22px;
    }
    .brand-logo {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 1.25rem;
        font-weight: 700;
        color: #10151a;
    }
    .logo-badge {
        align-items: center;
        background: var(--color-primary);
        color: #fff;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.95rem;
    }
    .about-btn {
        position: absolute;      /* Added absolute positioning */
        right: 0;                /* Stick to the right side */
        top: 50%;                /* Center vertically */
        transform: translateY(-50%); /* Center vertically */
        background: #f3f4f6;
        color: #10151a;
        border-radius: 20px;
        padding: 8px 20px;
        font-weight: 700;
        font-size: 0.9rem;
        box-shadow: var(--shadow);
    }
    .about-btn:hover {
        background: #e5e7eb;
    }

    /* ===== Tagline ===== */
    .landing-tagline {
        text-align: center;
        margin-bottom: 20px;
    }
    .landing-tagline h1 {
        color: var(--color-primary-dark);
        font-size: 1.35rem;
        margin: 0 0 6px;
    }
    .landing-tagline p {
        color: var(--color-text-muted);
        margin: 0;
        font-size: 0.95rem;
    }

    /* ===== Hero image placeholder ===== */
    .hero-image-wrap {
        background: var(--color-card);
        border-radius: 18px;
        box-shadow: var(--shadow);
        overflow: hidden;
        min-height: 260px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 26px;
    }
    .hero-image-wrap img {
        
        width: 100%;
        height: auto;
        max-height: 340px;
        display: block;
        object-fit: cover;
    }

    /* ===== Login / Register buttons ===== */
    .landing-actions {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }
    .landing-btn {
        flex: 1;
        min-width: 200px;
        text-align: center;
        background: var(--color-primary);
        color: #fff;
        padding: 16px;
        border-radius: 28px;
        font-weight: 700;
        font-size: 1.05rem;
        box-shadow: var(--shadow);
    }
    .landing-btn:hover {
        background: var(--color-primary-dark);
    }

    /* ===== About section ===== */
    .about-section {
        margin-top: 30px;
    }
</style>
</head>
<body>

<div class="landing-container">
    <header class="landing-header">
        <div class="brand-logo"><span class="logo-badge">E</span>-Ride Services</div>
        
    </header>

    <div class="landing-tagline">
        <h1>Smart. Eco-Friendly. Affordable.</h1>
        <p>Electric bike rental across Bashundhara R/A, Dhaka</p>
    </div>

    <div class="hero-image-wrap">
        <img src="image.jpg" alt="E-Ride bike and scooter rental illustration">
    </div>

    <div class="landing-actions">
        <a href="view/login.php" class="landing-btn">Login</a>
        <a href="view/register.php" class="landing-btn">Register</a>
    </div>

    
</div>

</body>
</html>

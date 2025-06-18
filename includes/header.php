<!DOCTYPE html>
<html>
<head>
    <title>CODIL</title>
</head>
<body>
    
<style>
    /* White Cyberpunk Navigation */
    nav {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-radius: 15px;
        padding: 1rem 2rem;
        display: flex;
        gap: 1.5rem;
        box-shadow: 0 0 25px rgba(0, 243, 255, 0.1);
        border: 1px solid rgba(66, 225, 234, 0.87);
        z-index: 1000;
        transition: all 0.3s ease;
    }

    nav:hover {
        box-shadow: 0 0 35px rgba(0, 243, 255, 0.2);
        transform: translateX(-50%) translateY(-2px);
    }

    nav a {
        color: #2d2d44;
        text-decoration: none;
        font-family: 'Inter', sans-serif;
        font-size: 1rem;
        padding: 0.5rem 1.2rem;
        border-radius: 8px;
        transition: all 0.3s ease;
        position: relative;
        font-weight: 500;
    }

    nav a::after {
        content: '';
        position: absolute;
        bottom: -3px;
        left: 50%;
        transform: translateX(-50%);
        width: 0;
        height: 2px;
        background: linear-gradient(90deg, #00f3ff 0%, #ff0080 100%);
        transition: width 0.3s ease;
    }

    nav a:hover {
        color: #1a1a2e;
        background: rgba(0, 243, 255, 0.05);
    }

    nav a:hover::after {
        width: 80%;
    }

    /* Active link state */
    nav a.active {
        background: linear-gradient(90deg, #00f3ff 0%, #ff0080 100%);
        color: white !important;
        box-shadow: 0 2px 12px rgba(0, 243, 255, 0.3);
    }

    /* Mobile responsive */
    @media (max-width: 768px) {
        nav {
            padding: 0.8rem 1rem;
            gap: 0.8rem;
            top: 10px;
        }
        
        nav a {
            font-size: 0.9rem;
            padding: 0.4rem 0.8rem;
        }
    }

    /* Link entrance animation */
    @keyframes navLinkFade {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    nav a {
        animation: navLinkFade 0.6s ease forwards;
        animation-delay: calc(var(--i) * 0.1s);
    }
</style>
<nav>
    <a href="index.php" style="--i: 0">Home</a>
    <a href="compiler.php" style="--i: 1">Problems</a>
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="profile.php" style="--i: 2">Profile</a>
        <a href="logout.php" style="--i: 3">Logout</a>
    <?php else: ?>
        <a href="login.php" style="--i: 2">Login</a>
        <a href="signup.php" style="--i: 3">Signup</a>
    <?php endif; ?>
    <a href="leaderboard.php" style="--i: 4">Leaderboard</a>
</nav>

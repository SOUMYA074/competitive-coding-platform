<?php include './config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>CODIL - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }

    .all {
        background: linear-gradient(135deg, #f8f9fa, #ffffff, #f1f3ff);
        background-size: 400% 400%;
        animation: bgPulse 15s ease infinite;
        min-height: 90vh;
        position: relative;
        overflow: hidden;
    }

    /* Cyber Grid Overlay */
    .all::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: 
            linear-gradient(rgba(0, 243, 255, 0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0, 243, 255, 0.05) 1px, transparent 1px);
        background-size: 40px 40px;
        pointer-events: none;
        z-index: 1;
    }

    .container {
        position: relative;
        z-index: 2;
        height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .jumbotron {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px);
        
        border-radius: 30px;
        padding: 4rem 3rem;
        box-shadow: 0 0 40px rgba(0, 243, 255, 0.2),
                    0 8px 32px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
        
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .jumbotron:hover {
        transform: translateY(-5px);
        box-shadow: 0 0 60px rgba(0, 243, 255, 0.3),
                    0 12px 40px rgba(0, 0, 0, 0.1);
    }

    .jumbotron::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, 
            transparent 45%, 
            rgba(255, 0, 128, 0.1) 50%,
            transparent 55%);
        animation: borderFlow 8s linear infinite;
        z-index: -1;
    }

    .jumbotron h1 {
        color: #1a1a2e;
        font-size: 3.5rem;
        font-family: 'Orbitron', sans-serif;
        text-shadow: 0 0 10px rgba(0, 243, 255, 0.3);
        margin-bottom: 1.5rem;
        position: relative;
    }

    .jumbotron h1::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 60%;
        height: 3px;
        background: linear-gradient(90deg, transparent, #00f3ff, transparent);
    }

    .jumbotron p {
        color: #2d2d44;
        font-size: 1.4rem;
        margin-bottom: 2.5rem;
        letter-spacing: 0.5px;
        line-height: 1.6;
    }

    .btn-primary {
        background: linear-gradient(135deg, #00f3ff 0%, #ff0080 100%);
        border: none;
        padding: 1.2rem 2.5rem;
        font-size: 1.2rem;
        color: white;
        border-radius: 12px;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.2);
        font-family: 'Orbitron', sans-serif;
        letter-spacing: 1px;
    }

    .btn-primary::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, 
            transparent 20%, 
            rgba(255, 255, 255, 0.3) 50%,
            transparent 80%);
        animation: buttonShine 4s infinite linear;
    }

    .btn-primary:hover {
        transform: scale(1.05);
        box-shadow: 0 0 40px rgba(0, 243, 255, 0.3);
    }

    /* Animations */
    @keyframes bgPulse {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    @keyframes borderFlow {
        0% { transform: translateX(-25%) rotate(45deg); }
        100% { transform: translateX(25%) rotate(45deg); }
    }

    @keyframes buttonShine {
        0% { transform: translateX(-100%) rotate(45deg); }
        100% { transform: translateX(100%) rotate(45deg); }
    }

    @media (max-width: 768px) {
        .jumbotron {
            padding: 2.5rem 1.5rem;
            margin: 1rem;
        }
        
        .jumbotron h1 {
            font-size: 2.2rem;
        }
        
        .jumbotron p {
            font-size: 1.1rem;
        }

        .btn-primary {
            padding: 1rem 2rem;
            font-size: 1rem;
        }
    }
    </style>
</head>
<body>
    <?php include 'D:/XAMPP/htdocs/coding-platform/includes/header.php'; ?>
    <div class="all">
        <div class="container">
            <div class="jumbotron">
                <h1>WELCOME TO CODIL</h1>
                <p>Enter the digital arena where code warriors rise. Hone your skills, conquer challenges, and dominate the leaderboards.</p>
                <a href="compiler.php" class="btn btn-primary">INITIATE SYSTEM</a>
            </div>
        </div>
    </div>
    <?php include 'D:/XAMPP/htdocs/coding-platform/includes/footer.php'; ?>
</body>
</html>
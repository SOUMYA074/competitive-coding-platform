<?php session_start();?>
<?php include './config.php'; ?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = $conn->query("SELECT * FROM users 
        WHERE username='$username' AND password='$password'")->fetch_assoc();

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: profile.php");
    } else {
        echo "Invalid credentials!";
    }
}
?>

<?php include 'D:/XAMPP/htdocs/coding-platform/includes/header.php'; ?>


<style>
    body {
        background: linear-gradient(
    145deg,
    rgba(255, 255, 255, 0.9) 0%,
    rgba(93, 197, 208, 0.67) 50%,
    rgba(107, 237, 242, 0.81) 100%
);
        background-size: 400% 400%;
        animation: gradientFlow 5s ease infinite;
        min-height: 100vh;
        margin: 0;
        font-family: 'Inter', sans-serif;
        display: flex;
        flex-direction: column;
    }

    @keyframes gradientFlow {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    .container {
        flex: 1;
        display: grid;
        place-items: center;
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }

    form {
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(16px);
        border-radius: 24px;
        padding: 1.6rem 4rem;
        width: 100%;
        max-width: 440px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.1);
        position: relative;
        animation: formRise 0.8s cubic-bezier(0.23, 1, 0.32, 1);
    }

    @keyframes formRise {
        from { opacity: 0; transform: translateY(40px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    input {
        width: 100%;
        padding: 1.2rem;
        margin: 1.2rem 0;
        background: rgba(255, 255, 255, 0.34);
        border: 1px solid rgba(222, 36, 216, 0.83);
        border-radius: 12px;
        color: #fff;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    input:focus {
        outline: none;
        border-color: #ff0080;
        box-shadow: 0 0 20px rgba(255, 0, 128, 0.2);
        background: rgba(255, 255, 255, 0.1);
    }

    input::placeholder {
        color: rgba(26, 217, 208, 0.6);
    }

    button {
        width: 100%;
        padding: 1.2rem;
        margin-top: 2rem;
        background: linear-gradient(135deg, #ff0080 0%, #7928ca 100%);
        border: none;
        border-radius: 12px;
        color: white;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    button::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 200%;
        height: 100%;
        background: linear-gradient(
            90deg,
            transparent,
            rgba(255, 255, 255, 0.15),
            transparent
        );
        transform: skewX(-20deg);
        transition: left 0.6s ease;
    }

    button:hover::after {
        left: 120%;
    }

    button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(121, 40, 202, 0.3);
    }

    /* Neon Particle Effect */
    .particles {
        position: fixed;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 0;
    }

    .particle {
        position: absolute;
        background: radial-gradient(circle,rgba(179, 203, 212, 0.9) 0%, transparent 70%);
        animation: particleFloat 8s infinite linear;
    }

    @keyframes particleFloat {
        0% { transform: translateY(0) scale(0); opacity: 0; }
        50% { transform: translateY(-50vh) scale(1); opacity: 0.3; }
        100% { transform: translateY(-100vh) scale(0); opacity: 0; }
    }

    /* Error Message Styling */
    .error-message {
        color: #ff0080;
        text-align: center;
        margin: 1rem 0;
        padding: 1rem;
        background: rgba(255, 0, 128, 0.1);
        border-radius: 8px;
        animation: neonPulse 1.5s ease infinite;
    }

    @keyframes neonPulse {
        0%, 100% { text-shadow: 0 0 10px rgba(255, 0, 128, 0.3); }
        50% { text-shadow: 0 0 20px rgba(255, 0, 128, 0.5); }
    }

    @media (max-width: 480px) {
        form {
            padding: 2rem 1.5rem;
            border-radius: 20px;
        }
        
        input {
            padding: 1rem;
        }
    }
</style>

<!-- Add particles -->
<div class="particles">
    <?php for($i=0; $i<20; $i++): ?>
        <div class="particle" style="
            left: <?= rand(0,100) ?>%;
            top: <?= rand(0,100) ?>%;
            width: <?= rand(2,4) ?>px;
            height: <?= rand(2,4) ?>px;
            animation-delay: <?= rand(0,5) ?>s;"></div>
    <?php endfor; ?>
</div>

<div class="container">
    <form method="POST">
        <?php if(isset($error)): ?>
            <div class="error-message">⚠️ <?php echo $error; ?></div>
        <?php endif; ?>
        
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</div>

<?php include 'D:/XAMPP/htdocs/coding-platform/includes/footer.php'; ?>
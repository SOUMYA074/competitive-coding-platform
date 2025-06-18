<?php session_start();?>
<?php include './config.php'; ?>
<?php
$user_id = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id=$user_id")->fetch_assoc();
?>
<?php include 'D:/XAMPP/htdocs/coding-platform/includes/header.php'; ?>

<style>
    .profile-container {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 4rem 2rem;
        min-height: calc(100vh - 120px);
    }

    .profile-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(16px);
        border-radius: 24px;
        padding: 3rem 4rem;
        width: 100%;
        max-width: 600px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: 2px solid #00f3ff;
        text-align: center;
        transition: all 0.3s ease;
    }

    .profile-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px rgba(0, 243, 255, 0.2);
    }

    h2 {
        color: #2d2d44;
        font-family: 'Orbitron', sans-serif;
        font-size: 2.5rem;
        margin-bottom: 2rem;
        position: relative;
    }

    h2::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 50%;
        height: 3px;
        background: linear-gradient(90deg, #00f3ff, #ff0080);
    }

    .points-display {
        background: linear-gradient(135deg, #00f3ff, #ff0080);
        color: white;
        padding: 1.5rem;
        border-radius: 12px;
        font-size: 2rem;
        font-family: 'Orbitron', sans-serif;
        margin: 2rem 0;
        box-shadow: 0 4px 20px rgba(0, 243, 255, 0.3);
    }

    .stats-container {
        display: grid;
        gap: 1.5rem;
        margin-top: 2rem;
    }

    .stat-item {
        background: rgba(255, 255, 255, 0.95);
        padding: 1.5rem;
        border-radius: 12px;
        border: 1px solid #00f3ff;
        box-shadow: 0 2px 10px rgba(0, 243, 255, 0.1);
    }

    @media (max-width: 768px) {
        .profile-card {
            padding: 2rem;
            margin: 1rem;
        }
        
        h2 {
            font-size: 2rem;
        }
        
        .points-display {
            font-size: 1.5rem;
        }
    }
</style>

<div class="profile-container">
    <div class="profile-card">
        <h2>CODIL PROFILE: <?= strtoupper($user['username']) ?></h2>
        
        <div class="points-display">
            XP: <?= number_format($user['points']) ?>
        </div>

        <div class="stats-container">
            <!-- <div class="stat-item">
                <h3>Rank Status</h3>
                <p>#<?= $user['rank'] ?? 'N/A' ?></p>
            </div> -->
            <div class="stat-item">
                <h3>Code Battles</h3>
                <p><?= $user['problems_solved'] ?? 0 ?> Completed</p>
            </div>
        </div>
    </div>
</div>

<?php include 'D:/XAMPP/htdocs/coding-platform/includes/footer.php'; ?>
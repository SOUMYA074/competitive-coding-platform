<?php include './config.php'; ?>
<?php include 'D:/XAMPP/htdocs/coding-platform/includes/header.php'; ?>

<style>
    .leaderboard-container {
        padding: 2rem;
        max-width: 1000px;
        min-height: 72vh;
        margin: 2rem auto;
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        border: 1px solid rgba(74, 194, 228, 0.84);
        box-shadow: 0 0 30px rgba(43, 147, 208, 0.3);
    }

    .leaderboard-title {
        color: #ff0080;
        text-align: center;
        font-size: 2.5rem;
        margin-bottom: 2rem;
        text-shadow: 0 0 15px rgba(255, 0, 128, 0.4);
        background: linear-gradient(90deg,rgba(255, 0, 128, 0.53), #7928ca);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .leaderboard-table {
        width: 100%;
        border-collapse: collapse;
        background: rgba(0, 0, 0, 0.2);
        border-radius: 12px;
        overflow: hidden;
    }

    .leaderboard-table th {
        background: linear-gradient(135deg,rgba(255, 0, 128, 0.47), #7928ca);
        padding: 1.2rem;
        color: white;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 1.1rem;
    }

    .leaderboard-table td {
        padding: 1rem 1.2rem;
        color: rgba(33, 25, 196, 0.9);
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .leaderboard-table tr:nth-child(even) {
        background: rgba(255, 255, 255, 0.02);
    }

    .leaderboard-table tr:hover {
        background: rgba(255, 255, 255, 0.08);
    }

    .leaderboard-rank {
        font-weight: bold;
        color: #ff0080;
        position: relative;
        padding-left: 2rem;
    }

    .leaderboard-rank::before {
        content: '#';
        position: absolute;
        left: 0.5rem;
        opacity: 0.6;
    }

    /* Top 3 Badges */
    .leaderboard-table tr:first-child td:first-child { color:rgb(5, 128, 17); }
    .leaderboard-table tr:nth-child(2) td:first-child { color:rgb(66, 40, 240); }
    .leaderboard-table tr:nth-child(3) td:first-child { color: #cd7f32; }

    @media (max-width: 768px) {
        .leaderboard-container {
            padding: 1rem;
            margin: 1rem;
        }
        
        .leaderboard-table th,
        .leaderboard-table td {
            padding: 0.8rem;
            font-size: 0.9rem;
        }
        
        .leaderboard-title {
            font-size: 2rem;
        }
    }
</style>

<div class="leaderboard-container">
    <h1 class="leaderboard-title">Leaderboard</h1>
    <table class="leaderboard-table">
        <thead>
            <tr>
                <th>Rank</th>
                <th>Username</th>
                <th>Points</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $rank = 1;
            $result = $conn->query("SELECT * FROM users ORDER BY points DESC");
            while ($row = $result->fetch_assoc()):
            ?>
            <tr>
                <td class="leaderboard-rank"><?= $rank++ ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= $row['points'] ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'D:/XAMPP/htdocs/coding-platform/includes/footer.php'; ?>
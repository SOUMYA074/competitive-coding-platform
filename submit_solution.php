<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "User not logged in."]);
    exit;
}

$user_id = $_SESSION['user_id'];
$problem_id = $_POST['problem_id'];
$code = $_POST['code'];
$language = $_POST['language'];

// 1. Get problem difficulty
$diffQuery = $conn->prepare("SELECT difficulty FROM problems WHERE id = ?");
$diffQuery->bind_param("i", $problem_id);
$diffQuery->execute();
$diffResult = $diffQuery->get_result();

if ($diffResult->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "Problem not found."]);
    exit;
}

$difficulty = $diffResult->fetch_assoc()['difficulty'];

// 2. Calculate points
$points = 0;
switch (strtolower($difficulty)) {
    case 'easy':
        $points = 20;
        break;
    case 'medium':
        $points = 30;
        break;
    case 'hard':
        $points = 50;
        break;
    default:
        $points = 10; // fallback
}

// 3. Optional: Check if already submitted
$check = $conn->prepare("SELECT * FROM submissions WHERE user_id = ? AND problem_id = ?");
$check->bind_param("ii", $user_id, $problem_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Problem already solved."]);
    exit;
}

// 4. Update user points
$update = $conn->prepare("UPDATE users SET points = points + ? WHERE id = ?");
$update->bind_param("ii", $points, $user_id);
$update->execute();

// 5. Log the submission
$insert = $conn->prepare("INSERT INTO submissions (user_id, problem_id, code, language, status) VALUES (?, ?, ?, ?, 'passed')");
$insert->bind_param("iiss", $user_id, $problem_id, $code, $language);
$insert->execute();

// 6. Get new point total
$get = $conn->prepare("SELECT points FROM users WHERE id = ?");
$get->bind_param("i", $user_id);
$get->execute();
$newPoints = $get->get_result()->fetch_assoc()['points'];

echo json_encode([
    "success" => true,
    "points" => $points,
    "new_points" => $newPoints
]);
?>

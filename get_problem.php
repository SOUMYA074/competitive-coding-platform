<?php
include 'config.php';

if (!isset($_GET['id'])) {
    header("HTTP/1.1 400 Bad Request");
    exit("Problem ID required");
}

$problemId = intval($_GET['id']);

// Fetch problem details
$problem = [];
$stmt = $conn->prepare("SELECT * FROM problems WHERE id = ?");
$stmt->bind_param("i", $problemId);
$stmt->execute();
$result = $stmt->get_result();
$problem = $result->fetch_assoc();

// Fetch test cases
$stmt = $conn->prepare("SELECT input_data, output_data FROM test_cases WHERE problem_id = ?");
$stmt->bind_param("i", $problemId);
$stmt->execute();
$testCases = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$problem['test_cases'] = $testCases;

// Fetch templates
$stmt = $conn->prepare("SELECT language, code FROM templates WHERE problem_id = ?");
$stmt->bind_param("i", $problemId);
$stmt->execute();
$templates = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$problem['templates'] = $templates;

header('Content-Type: application/json');
echo json_encode($problem);

$conn->close();
?>
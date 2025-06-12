<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo "Unauthorized";
    exit();
}

$user_id = $_SESSION['user_id'];
$game_name = trim($_POST['game_name'] ?? '');
$score = isset($_POST['score']) ? intval($_POST['score']) : -1;

if ($game_name !== '' && $score >= 0) {
    $stmt = $conn->prepare("INSERT INTO scores (user_id, game_name, score) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $user_id, $game_name, $score);
    
    if ($stmt->execute()) {
        echo "Score saved!";
    } else {
        echo "Failed to save score.";
    }

    $stmt->close();
} else {
    echo "Invalid data.";
}
?>

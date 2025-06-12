<?php
session_start();
include 'db.php';

// Fetch distinct games
$gamesQuery = $conn->query("SELECT DISTINCT game_name FROM scores");

$leaderboards = [];

while ($game = $gamesQuery->fetch_assoc()) {
    $gameName = $game['game_name'];
    
    $stmt = $conn->prepare("
        SELECT u.username, s.score, s.date_played 
        FROM scores s
        JOIN users u ON s.user_id = u.id
        WHERE s.game_name = ?
        ORDER BY s.score DESC, s.date_played ASC
        LIMIT 5
    ");
    $stmt->bind_param("s", $gameName);
    $stmt->execute();
    $result = $stmt->get_result();
    $leaderboards[$gameName] = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Leaderboard - EduPlay</title>
  <link rel="stylesheet" href="auth.css" />
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #f9f9f9;
      padding: 40px;
      color: #333;
    }
    .game-section {
      margin-bottom: 50px;
    }
    h2 {
      color: #222;
      margin-bottom: 15px;
    }
    table {
      width: 100%;
      max-width: 600px;
      margin-bottom: 30px;
      border-collapse: collapse;
      background: #fff;
      border-radius: 8px;
      overflow: hidden;
    }
    th, td {
      padding: 12px 15px;
      border-bottom: 1px solid #eee;
      text-align: left;
    }
    th {
      background: #ffcc00;
      color: #000;
    }
    tr:hover {
      background: #f1f1f1;
    }
  </style>
</head>
<body>

  <h1>🏆 EduPlay Leaderboards</h1>

  <?php foreach ($leaderboards as $game => $entries): ?>
    <div class="game-section">
      <h2><?php echo htmlspecialchars($game); ?> - Top Players</h2>
      <?php if (count($entries) > 0): ?>
        <table>
          <thead>
            <tr>
              <th>Username</th>
              <th>Score</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($entries as $entry): ?>
              <tr>
                <td><?php echo htmlspecialchars($entry['username']); ?></td>
                <td><?php echo $entry['score']; ?></td>
                <td><?php echo date("M d, Y", strtotime($entry['date_played'])); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p>No scores yet for this game.</p>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>

</body>
</html>

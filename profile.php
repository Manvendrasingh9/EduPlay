<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, email, photo FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$profilePhoto = $user['photo'] ?: 'default.png';

$scoreStmt = $conn->prepare("SELECT game_name, score, date_played FROM scores WHERE user_id = ? ORDER BY date_played DESC");
$scoreStmt->bind_param("i", $userId);
$scoreStmt->execute();
$scores = $scoreStmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Your Profile - EduPlay</title>
  <link rel="stylesheet" href="auth.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #0a0a0a, #1e1e1e);
      color: white;
    }

    header {
      width: 100%;
      background: rgba(49, 45, 45, 0.8);
      padding: 1px 8%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: fixed;
      top: 0;
      z-index: 1000;
      box-shadow: 0 4px 10px rgba(71, 69, 69, 0.5);
    }

    .logo {
      font-size: 1.8em;
      font-weight: bold;
      color: #ffcc00;
      text-decoration: none;
    }
    .logo-img {
      height: 120px;
      vertical-align: middle;
    }
    nav ul {
      list-style: none;
      display: flex;
      gap: 30px;
    }

    nav a {
      color: white;
      text-decoration: none;
      font-weight: 500;
      transition: color 0.3s;
    }

    nav a:hover {
      color: #ffcc00;
    }

    .profile-icon {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      overflow: hidden;
      border: 2px solid #ffcc00;
    }

    .profile-icon img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 50%;
    }

    .profile-container {
      margin: 120px auto 40px;
      max-width: 800px;
      background: #1e1e1e;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.6);
      text-align: center;
    }

    .profile-pic {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #ffcc00;
      margin-bottom: 20px;
    }

    .score-table {
      width: 100%;
      margin-top: 30px;
      border-collapse: collapse;
    }

    .score-table th, .score-table td {
      padding: 12px;
      border: 1px solid #444;
      background-color: rgba(255,255,255,0.05);
      color: white;
    }

    .score-table th {
      background: #ffcc00;
      color: black;
    }

    .logout-button {
      margin-top: 30px;
      padding: 10px 25px;
      background-color: #e74c3c;
      color: white;
      font-weight: bold;
      border: none;
      border-radius: 30px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .logout-button:hover {
      background-color: #c0392b;
    }
  </style>
</head>
<body>

<header>
  <a href="index.php" class="logo">
  <img src="images/logo.png" alt="EduPlay Logo" class="logo-img">
  </a>
  <nav>
    <ul>
      <li><a href="index.php">Home</a></li>
      <li><a href="#games">Games</a></li>
      <li><a href="contact.php">Contact</a></li>
    </ul>
  </nav>
  <div class="profile-icon">
    <a href="profile.php"><img src="uploads/<?php echo htmlspecialchars($profilePhoto); ?>" alt="Profile" /></a>
  </div>
</header>

<div class="profile-container">
  <img src="uploads/<?php echo htmlspecialchars($profilePhoto); ?>" alt="Profile Picture" class="profile-pic" />
  <h2><?php echo htmlspecialchars($user['username']); ?> 👋</h2>
  <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>

  <h3>Your Game Scores:</h3>
  <?php if ($scores->num_rows > 0): ?>
    <table class="score-table">
      <thead>
        <tr>
          <th>Game</th>
          <th>Score</th>
          <th>Date Played</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $scores->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['game_name']); ?></td>
            <td><?php echo $row['score']; ?></td>
            <td><?php echo date("M d, Y H:i", strtotime($row['date_played'])); ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>You haven’t played any games yet!</p>
  <?php endif; ?>

  <form action="logout.php" method="POST">
    <button class="logout-button" type="submit">Logout</button>
  </form>
</div>

</body>
</html>

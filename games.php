<?php
session_start();
include 'db.php';

$loggedIn = false;
$profilePhoto = 'default.png'; // fallback if no photo uploaded

if (isset($_SESSION['user_id'])) {
    $loggedIn = true;
    $stmt = $conn->prepare("SELECT photo FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $profilePhoto = $row['photo'] ?? 'default.png';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>EduPlay - Game</title>
  <link rel="stylesheet" href="games.css" />
</head>
<body>
  <header>
  <div class="logo">
  <a href="index.php">
    <img src="images/logo.png" alt="EduPlay Logo" class="logo-img">
  </a>
</div>

    <nav>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="#">Games</a></li>
        <li><a href="contact.php">Contact</a></li>
      </ul>
    </nav>
    <div class="auth-buttons">
      <?php if ($loggedIn): ?>
        <a href="profile.php">
          <img src="uploads/<?php echo htmlspecialchars($profilePhoto); ?>" class="profile-icon" alt="Profile" />
        </a>
      <?php else: ?>
        <a href="login.html" class="btn login">Login</a>
        <a href="register.html" class="btn register">Register</a>
      <?php endif; ?>
    </div>
  </header>

  <section class="games-container">
    <h2>Select a Game</h2>

    <div class="game-grid">
      <a href="math-puzzle.html" class="game-card">
        <img src="images/math.png" alt="Math Puzzle" />
        <span>Math Puzzle</span>
      </a>

      <a href="word builder.html" class="game-card">
        <img src="images/word.png" alt="Word Builder" />
        <span>Word Builder</span>
      </a>

      <a href="memory-challenge.html" class="game-card">
        <img src="images/memory.png" alt="Memory Challenge" />
        <span>Memory Challenge</span>
      </a>

      <a href="shape-matcher.html" class="game-card">
        <img src="images/shape.png" alt="Shape Matcher" />
        <span>Shape Matcher</span>
      </a>

      <a href="spelling-bee.html" class="game-card">
        <img src="images/spelling.png" alt="Spelling Bee" />
        <span>Spelling Bee</span>
      </a>
    </div>
  </section>

  <footer>
    <p>&copy; 2025 Play and Learn. All Rights Reserved.</p>
  </footer>
</body>
</html>

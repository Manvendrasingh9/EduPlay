<?php
session_start();
include 'db.php';

$loggedIn = false;
$profilePhoto = 'default.png'; // fallback image

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EduPlay</title>
    <link rel="stylesheet" href="styles2.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600;800&display=swap" rel="stylesheet" />
</head>
<body>
    <header class="navbar">
    <div class="logo">
  <a href="index.php">
    <img src="images/logo.png" alt="EduPlay Logo" class="logo-img">
  </a>
</div>

        <nav>
            <ul class="nav-links">
                <li><a href="#">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="games.php">Games</a></li>
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

    <section class="hero">
        <div class="hero-content">
            <h1>Unleash the Fun in <span>Learning!</span></h1>
            <p>Explore games that make education exciting and enjoyable for kids.</p>
            <a href="games.php" class="btn hero-btn">Start Playing</a>
        </div>
    </section>

    <section id="about">
        <h2>About Us</h2>
        <p>At Play and Learn, we design interactive games that help kids learn while having fun. Perfect for ages 4-12!</p>
    </section>

    <section id="games">
        <h2>Popular Games</h2>
        <div class="game-list">
            <a href="math-puzzle.html" class="game-item">Math Puzzle</a>
            <a href="word builder.html" class="game-item">Word Builder</a>
            <a href="memory-challenge.html" class="game-item">Memory Challenge</a>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 Play and Learn. All rights reserved.</p>
    </footer>
</body>
</html>

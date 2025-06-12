<?php
session_start();
include 'db.php';

$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';

// ✅ Fix: If user is logged in but profile image is not in session
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
  <meta charset="UTF-8">
  <title>Contact Us</title>
  <link rel="stylesheet" href="contact.css">
</head>
<body>
<header>
  <nav class="navbar">
    <div class="logo"><a href="index.php">
    <div class="logo">
  <a href="index.php">
    <img src="images/logo.png" alt="EduPlay Logo" class="logo-img">
  </a>
</div>
    </a></div>
    <ul class="nav-links">
      <li><a href="index.php">Home</a></li>
      <li><a href="games.php">Games</a></li>
      <li><a href="contact.php">Contact</a></li>

      <?php if (isset($_SESSION['user_id'])): ?>
        <li><a href="profile.php">
        <img src="uploads/<?php echo htmlspecialchars($profilePhoto); ?>" class="profile-icon" alt="Profile" />
        </a></li>
      <?php else: ?>
        <li><a href="login.html">Login</a></li>
        <li><a href="register.html">Register</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</header>

  <div class="contact-container">
    <h2>Contact Us</h2>

    <?php if ($success): ?>
      <div class="message success"><?php echo htmlspecialchars($success); ?></div>
    <?php elseif ($error): ?>
      <div class="message error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form action="contact_submit.php" method="POST">
      <input type="text" name="name" placeholder="Your Name" required />
      <input type="email" name="email" placeholder="Your Email" required />
      <textarea name="message" rows="5" placeholder="Your Message..." required></textarea>
      <button type="submit">Send Message</button>
    </form>
  </div>
</body>
</html>

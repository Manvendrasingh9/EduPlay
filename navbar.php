<?php
session_start();
?>

<nav class="navbar">
  <div class="logo">EduPlay</div>
  <ul class="nav-links">
    <li><a href="home.php">Home</a></li>
    <li><a href="games.php">Games</a></li>

    <?php if (isset($_SESSION['username'])): ?>
      <li><a href="profile.php">Profile</a></li>
      <li><a href="logout.php">Logout</a></li>
      <li class="profile-img">
        <img src="<?= $_SESSION['photo'] ?>" alt="Profile" />
      </li>
    <?php else: ?>
      <li><a href="login.html">Login</a></li>
      <li><a href="register.html">Register</a></li>
    <?php endif; ?>
  </ul>
</nav>

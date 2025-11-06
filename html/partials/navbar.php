<!-- NAVBAR -->
<?php
  // Start session to read login state for conditional navbar items
  if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
  }
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand linktest" href="home.php">JazStation</a>
        <button class="navbar-toggler" type="button">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link linktest" href="home.php">Home</a></li>
                <li class="nav-item"><a class="nav-link linktest" href="products.php">Games</a></li>
                <li class="nav-item"><a class="nav-link linktest" href="about.php">About</a></li>
                <li class="nav-item"><a class="nav-link linktest" href="contact.php">Contact</a></li>
                <li class="nav-item"><a class="nav-link linktest" href="cart.php">🛒 Cart</a></li>
                <?php if (!empty($_SESSION['user_id'])): ?>
                  <!-- When logged in: show username and Logout link -->
                  <li class="nav-item"><span class="nav-link disabled">Hi, <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></span></li>
                  <li class="nav-item"><a class="nav-link linktest" href="logout.php">Logout</a></li>
                <?php else: ?>
                  <!-- When logged out: show Login (modal) and Signup links -->
                  <li class="nav-item"><a class="nav-link linktest" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a></li>
                  <li class="nav-item"><a class="nav-link linktest" href="signup.php">Signup</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
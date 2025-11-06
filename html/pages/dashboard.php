<!DOCTYPE html>
<html lang="en">
<?php
  // Protect this page with server-side session check
  require_once __DIR__ . '/../includes/session_check.php';
  require_login($base . '/html/pages/login.php');
  include '../partials/header.php';
?>
<body>
  <?php include '../partials/alert.php'; ?>
  <?php include '../partials/navbar.php'; ?>

  <main>
    <section class="page-fade">
      <div class="container py-5">
        <h2 class="mb-4">Dashboard</h2>
        <p>Welcome, <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?>.</p>
        <p>Your email: <?= htmlspecialchars($_SESSION['user_email'] ?? '') ?></p>
      </div>
    </section>
  </main>

  <?php include '../partials/footer.php'; ?>
  <?php include '../partials/script.php'; ?>
</body>
</html>

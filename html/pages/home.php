<!DOCTYPE html>
<html lang="en">
<?php
  require_once __DIR__ . '/../includes/session_check.php';
  include '../partials/header.php';
?>

<body>

  <?php include '../partials/alert.php'; ?>
  
  <?php include '../partials/navbar.php'; ?>

  <!-- HOME -->
  <main>
    <section id="home" class="page-fade">
      <header class="text-center text-white py-5 bg-dark">
        <div class="container">
          <h1 class="display-4">Welcome to JazStation</h1>
          <p class="lead">Games, unlimited games, but no games.</p>
        </div>
      </header>
      <!-- Featured Games Section -->
      <section class="container py-5">
        <h2 class="text-center mb-4">Featured Games</h2>
        <div id="featured-games" class="row g-4 justify-content-center"></div>
      </section>
    </section>
  </main>

  <?php include '../partials/footer.php'; ?>

  <?php include '../partials/loginModal.php'; ?>

  <?php include '../partials/signupModal.php'; ?>

  <?php include '../partials/script.php'; ?>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<?php
  require_once __DIR__ . '/../includes/session_check.php';
  include '../partials/header.php';
?>

<body>

  <?php include '../partials/alert.php'; ?>

  <?php include '../partials/navbar.php'; ?>

  <!-- ABOUT -->
  <main>
    <section id="about" class="page-fade">
      <div class="container py-5">
        <div class="row align-items-center">
          <div class="col-md-6 mb-4 mb-md-0">
            <img src="./assets/header.png" class="img-fluid rounded shadow" alt="About JazStation">
          </div>
          <div class="col-md-6">
            <h2 class="mb-3 fw-bold text-primary">About JazStation</h2>
            <p class="lead text-secondary text-white-50">
              <strong>JazStation</strong> is your digital gateway to the world of games. We are the best game store
              platform. No. 1 in the world.
            </p>
            <p class="mb-0 text-white">
              Whether you're a casual player or a hardcore gamer, <span class="fw-semibold text-light">JazStation</span>
              is here to power your next adventure.
            </p>
          </div>
        </div>
        <!-- Creators NYEHEHEHEHE -->
        <div class="mt-5">
          <h3 class="text-center fw-bold mb-4 text-primary">Meet the Creators</h3>
          <div class="row justify-content-center g-4">
            <div class="col-12 col-md-5 d-flex flex-column align-items-center">
              <div class="bg-gradient rounded shadow p-3 w-100 text-center">
                <img src="./assets/jd.jpg" alt="JD" class="rounded-circle shadow mb-3 creatorpage">
                <h5 class="fw-bold mb-1">Jhondrei Apeta</h5>
                <div class="text-light mb-2">Lead Designer &amp; Lead Conceptualist</div>
                <p class="mb-0 small">Passionate about crafting beautiful, user-friendly digital experiences for gamers
                  everywhere.</p>
              </div>
            </div>
            <div class="col-12 col-md-5 d-flex flex-column align-items-center">
              <div class="bg-gradient rounded shadow p-3 w-100 text-center">
                <img src="./assets/jaz.jpg" alt="Jaz" class="rounded-circle shadow mb-3 creatorpage">
                <h5 class="fw-bold mb-1">Jazper Bonagua</h5>
                <div class="text-light mb-2">Fullstack Developer &amp; Game Curator</div>
                <p class="mb-0 small">Ensuring smooth performance and curating the best games for the JazStation
                  community.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <?php include '../partials/footer.php'; ?>

  <?php include '../partials/loginModal.php'; ?>

  <?php include '../partials/signupModal.php'; ?>

  <?php include '../partials/script.php'; ?>

</body>
</html>
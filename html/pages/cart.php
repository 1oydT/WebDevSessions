<!DOCTYPE html>
<html lang="en">
<?php
  // Server-side protection: require login before rendering page
  require_once __DIR__ . '/../includes/session_check.php';
  require_login($base . '/html/pages/login.php');
  include '../partials/header.php';
?>

<body>
  
  <?php include '../partials/alert.php'; ?>

  <?php include '../partials/navbar.php'; ?>

  <!-- CART -->
  <main>
    <section id="cart" class="page-fade">
      <div class="container py-5">
        <h2 class="text-center mb-4">Your Cart</h2>
        <div id="cart-items"></div>
        <div id="cart-total" class="text-center mt-4"></div>
        <div id="checkout-btn-row" class="text-center mt-4 d-none">
          <button id="checkout-btn" class="btn btn-success" type="button" data-bs-toggle="modal"
            data-bs-target="#checkoutModal" disabled>Checkout</button>
        </div>
      </div>
    </section>
  </main>

  <?php include '../partials/footer.php'; ?>

  <?php include '../partials/checkoutModal.php'; ?>

  <?php include '../partials/loginModal.php'; ?>

  <?php include '../partials/signupModal.php'; ?>

  <?php
    // Require authentication on this page. If not logged in, the login modal will auto-open.
    $requireAuth = true;
  ?>
  <?php include '../partials/script.php'; ?>

</body>
</html>
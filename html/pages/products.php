<!DOCTYPE html>
<html lang="en">
<?php
  require_once __DIR__ . '/../includes/session_check.php';
  include '../partials/header.php';
?>

<body>
  
  <?php include '../partials/alert.php'; ?>
  
  <?php include '../partials/navbar.php'; ?>

  <!-- PRODUCTS -->
  <main>
    <section id="products" class="page-fade">
      <div class="container py-5">
        <h2 class="text-center mb-4">All Games</h2>
        <!-- Categories/Genres -->
        <div id="product-categories" class="mb-4 text-center"></div>
        <!-- Sort -->
        <div class="d-flex justify-content-end mb-3">
          <select id="product-sort" class="form-select form-select-sm" style="width:auto;">
            <option value="az">A–Z</option>
            <option value="za">Z–A</option>
            <option value="price-asc">Price: Low → High</option>
            <option value="price-desc">Price: High → Low</option>
          </select>
        </div>
        <div id="products-list" class="row g-4"></div>
      </div>
    </section>
  </main>

  <?php include '../partials/footer.php'; ?>

  <?php include '../partials/overviewModal.php'; ?>

  <?php include '../partials/loginModal.php'; ?>

  <?php include '../partials/signupModal.php'; ?>

  <?php include '../partials/script.php'; ?>

</body>
</html>

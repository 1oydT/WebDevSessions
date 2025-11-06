<!DOCTYPE html>
<html lang="en">
<?php
  require_once __DIR__ . '/../includes/session_check.php';
  include '../partials/header.php';
?>

<body>

  <?php include '../partials/alert.php'; ?>

  <?php include '../partials/navbar.php'; ?>

    <!-- CONTACT -->
    <main>
      <section id="contact" class="page-fade">
        <div class="container py-5">
          <h2 class="text-center mb-4">Contact Us</h2>
          <p class="text-center">You can also reach us via email: gameshq@jazstation.com</p>
          <form id="contact-form" class="mx-auto" style="max-width:500px;">
            <div id="contact-success" class="alert alert-success mt-3 d-none" role="alert">
              Message sent successfully!
            </div>
            <div class="mb-3">
              <label for="contact-name" class="form-label">Name</label>
              <input type="text" class="form-control" id="contact-name" name="contact-name" maxlength="30" placeholder="Juan Dela Cruz" required>
              <div class="invalid-feedback" id="contact-name-feedback"></div>
            </div>
            <div class="mb-3">
              <label for="contact-email" class="form-label">Email</label>
              <input type="email" class="form-control" id="contact-email" name="contact-email" placeholder="juan@gmail.com" required>
              <div class="invalid-feedback" id="contact-email-feedback"></div>
            </div>
            <div class="mb-3">
              <label for="contact-message" class="form-label">Message</label>
              <textarea class="form-control" id="contact-message" name="contact-message" rows="4" maxlength="1000" placeholder="Hello World!" required></textarea>
              <div class="invalid-feedback" id="contact-message-feedback"></div>
            </div>
            <button type="submit" class="btn btn-primary">Send Message</button>
          </form>
        </div>
      </section>
    </main>

  <?php include '../partials/footer.php'; ?>

  <?php include '../partials/loginModal.php'; ?>

  <?php include '../partials/signupModal.php'; ?>

  <?php include '../partials/script.php'; ?>
  
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<?php
  // Centralized session and timeout/flash handling
  require_once __DIR__ . '/../includes/session_check.php';
  if (!empty($_SESSION['user_id'])) {
    header('Location: home.php');
    exit();
  }
  include '../partials/header.php';
?>
<body>
  <?php include '../partials/alert.php'; ?>
  <?php include '../partials/navbar.php'; ?>

  <main class="container py-5">
    <section class="row justify-content-center">
      <div class="col-12 col-md-10 col-lg-8">
        <h1 class="mb-4 text-center">Sign Up</h1>

        <div id="signup-message" class="mb-3"></div>

        <form id="signup-form" novalidate>
          <div class="row g-3">
            <div class="col-12">
              <label for="signup-name" class="form-label">Full Name</label>
              <input type="text" class="form-control" id="signup-name" name="signup-name" required>
              <div class="invalid-feedback">Full name is required.</div>
            </div>
            <div class="col-12 col-md-6">
              <label for="signup-email" class="form-label">Email</label>
              <input type="email" class="form-control" id="signup-email" name="signup-email" required placeholder="you@example.com">
              <div class="invalid-feedback">Please enter a valid email.</div>
            </div>
            <div class="col-12 col-md-6">
              <label for="signup-contact" class="form-label">Contact Number</label>
              <input type="text" class="form-control" id="signup-contact" name="signup-contact" required>
              <div class="invalid-feedback">Contact number is required.</div>
            </div>
            <div class="col-12">
              <label for="signup-address" class="form-label">Address</label>
              <input type="text" class="form-control" id="signup-address" name="signup-address" required>
              <div class="invalid-feedback">Address is required.</div>
            </div>
            <div class="col-12 col-md-6">
              <label for="signup-password" class="form-label">Password</label>
              <input type="password" class="form-control" id="signup-password" name="signup-password" required minlength="6" placeholder="At least 6 characters">
              <div class="invalid-feedback">Password must be at least 6 characters.</div>
            </div>
            <div class="col-12 col-md-6">
              <label for="signup-confirm" class="form-label">Confirm Password</label>
              <input type="password" class="form-control" id="signup-confirm" name="signup-confirm" required minlength="6">
              <div class="invalid-feedback">Passwords must match.</div>
            </div>
          </div>

          <button type="submit" class="btn btn-primary w-100 mt-4">Create Account</button>
        </form>

        <p class="mt-3 text-center">
          Already have an account? <a href="login.php">Log in</a>
        </p>
      </div>
    </section>
  </main>

  <?php include '../partials/footer.php'; ?>
  <?php include '../partials/script.php'; ?>

  <script>
    (function() {
      const form = document.getElementById('signup-form');
      const msg = document.getElementById('signup-message');

      const nameEl = document.getElementById('signup-name');
      const emailEl = document.getElementById('signup-email');
      const contactEl = document.getElementById('signup-contact');
      const addressEl = document.getElementById('signup-address');
      const passEl = document.getElementById('signup-password');
      const confirmEl = document.getElementById('signup-confirm');

      function showMessage(type, text) {
        msg.innerHTML = `<div class=\"alert alert-${type}\" role=\"alert\">${text}</div>`;
      }

      // Client-side validation mirrors server checks; then POST to api/signup_submit.php
      form.addEventListener('submit', async (e) => {
        e.preventDefault();
        msg.innerHTML = '';

        // Client-side validation
        let valid = true;
        function req(el) {
          if (!el.value.trim()) { el.classList.add('is-invalid'); valid = false; }
          else { el.classList.remove('is-invalid'); }
        }
        req(nameEl);
        req(contactEl);
        req(addressEl);

        if (!emailEl.value || !/^\S+@\S+\.\S+$/.test(emailEl.value)) {
          emailEl.classList.add('is-invalid'); valid = false;
        } else { emailEl.classList.remove('is-invalid'); }

        if (!passEl.value || passEl.value.length < 6) {
          passEl.classList.add('is-invalid'); valid = false;
        } else { passEl.classList.remove('is-invalid'); }

        if (confirmEl.value !== passEl.value || confirmEl.value.length < 6) {
          confirmEl.classList.add('is-invalid'); valid = false;
        } else { confirmEl.classList.remove('is-invalid'); }

        if (!valid) return;

        const body = new FormData(form);

        try {
          const res = await fetch('<?php echo $base; ?>/api/signup_submit.php', {
            method: 'POST',
            body
          });
          const data = await res.json();
          if (data.success) {
            // On success, show message then redirect users to login page
            showMessage('success', 'Account created successfully. Redirecting to login...');
            setTimeout(() => { window.location.href = 'login.php'; }, 800);
          } else {
            showMessage('danger', data.message || 'Signup failed.');
          }
        } catch (err) {
          showMessage('danger', 'Network error. Please try again.');
        }
      });
    })();
  </script>
</body>
</html>

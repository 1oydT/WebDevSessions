<!DOCTYPE html>
<html lang="en">
<?php
  // Start a session and redirect authenticated users away from the login page
  if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
  }
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
      <div class="col-12 col-md-8 col-lg-6">
        <h1 class="mb-4 text-center">Login</h1>

        <div id="login-message" class="mb-3"></div>

        <form id="login-form" novalidate>
          <div class="mb-3">
            <label for="login-email" class="form-label">Email</label>
            <input type="email" class="form-control" id="login-email" name="login-email" required placeholder="you@example.com">
            <div class="invalid-feedback">Please enter a valid email.</div>
          </div>
          <div class="mb-3">
            <label for="login-password" class="form-label">Password</label>
            <input type="password" class="form-control" id="login-password" name="login-password" required minlength="6" placeholder="••••••">
            <div class="invalid-feedback">Password must be at least 6 characters.</div>
          </div>
          <button type="submit" class="btn btn-primary w-100">Sign In</button>
        </form>

        <p class="mt-3 text-center">
          Don't have an account? <a href="signup.php">Sign up</a>
        </p>
      </div>
    </section>
  </main>

  <?php include '../partials/footer.php'; ?>
  <?php include '../partials/script.php'; ?>

  <script>
    (function() {
      const form = document.getElementById('login-form');
      const emailEl = document.getElementById('login-email');
      const passEl = document.getElementById('login-password');
      const msg = document.getElementById('login-message');

      function showMessage(type, text) {
        msg.innerHTML = `<div class="alert alert-${type}" role="alert">${text}</div>`;
      }

      // Client-side validation and POST to backend API (api/login_submit.php)
      form.addEventListener('submit', async (e) => {
        e.preventDefault();
        msg.innerHTML = '';

        // Client-side validation
        let valid = true;
        if (!emailEl.value || !/^\S+@\S+\.\S+$/.test(emailEl.value)) {
          emailEl.classList.add('is-invalid');
          valid = false;
        } else {
          emailEl.classList.remove('is-invalid');
        }
        if (!passEl.value || passEl.value.length < 6) {
          passEl.classList.add('is-invalid');
          valid = false;
        } else {
          passEl.classList.remove('is-invalid');
        }
        if (!valid) return;

        const body = new FormData();
        body.append('login-email', emailEl.value.trim());
        body.append('login-password', passEl.value);

        try {
          const res = await fetch('<?php echo $base; ?>/api/login_submit.php', {
            method: 'POST',
            body
          });
          const data = await res.json();
          if (data.success) {
            // On success, show message then redirect to home (or previous page)
            showMessage('success', 'Login successful. Redirecting...');
            setTimeout(() => { window.location.href = 'home.php'; }, 600);
          } else {
            showMessage('danger', data.message || 'Login failed.');
          }
        } catch (err) {
          showMessage('danger', 'Network error. Please try again.');
        }
      });
    })();
  </script>
</body>
</html>

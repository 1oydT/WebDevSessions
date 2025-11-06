<script>
  const BASE_URL = "<?= $base ?>";
</script>
<script src="<?= $base ?>/public/script.js?v=<?= time() ?>"></script>
<script src="<?= $base ?>/public/dist/jquery/jquery.min.js"></script>
<script src="<?= $base ?>/public/dist/jquery-validation/jquery.validate.min.js"></script>
<script src="<?= $base ?>/public/dist/bootstrap/bootstrap.bundle.min.js"></script>
<?php
  // Bootstraps a tiny auth bridge for JS consumers and optional guard
  if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
  }
  $authLoggedIn = !empty($_SESSION['user_id']);
  $authUserName = $authLoggedIn ? ($_SESSION['user_name'] ?? 'User') : '';
  // Pages can set $requireAuth = true; before including this partial
  $guardRequired = isset($requireAuth) && $requireAuth === true;
?>
<script>
  // Expose minimal auth state to client code
  window.AUTH = {
    loggedIn: <?= $authLoggedIn ? 'true' : 'false' ?>,
    userName: <?= json_encode($authUserName) ?>
  };
  // Page-level guard: if required and not logged in, open login modal automatically
  window.REQUIRE_AUTH = <?= $guardRequired ? 'true' : 'false' ?>;
  
  function enforceLoginModalLock(modalEl) {
    if (!modalEl) return;
    // Hide the header close button and disable dismissal while unauthenticated
    const closeBtn = modalEl.querySelector('[data-bs-dismiss="modal"]');
    if (closeBtn) closeBtn.classList.add('d-none');
    // Prevent programmatic/user-initiated hide while not logged in
    modalEl.addEventListener('hide.bs.modal', function (e) {
      if (!window.AUTH.loggedIn) {
        e.preventDefault();
      }
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('loginModal');

    // If page requires auth and user not logged in, show and lock the modal
    if (window.REQUIRE_AUTH && !window.AUTH.loggedIn) {
      if (modalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        const modal = new bootstrap.Modal(modalEl, {backdrop: 'static', keyboard: false});
        enforceLoginModalLock(modalEl);
        modal.show();
      } else {
        // Fallback: redirect to dedicated login page if modal is not present
        window.location.href = '<?= $base ?>/html/pages/login.php';
      }
    }

    // Harden any ad-hoc shows of the login modal across pages/actions
    if (modalEl) {
      modalEl.addEventListener('show.bs.modal', function () {
        if (!window.AUTH.loggedIn) {
          enforceLoginModalLock(modalEl);
        }
      });
    }
  });
  // Optional: let other scripts subscribe to auth state easily
  window.addEventListener('auth:check', () => {
    // no-op: placeholder if page scripts want to trigger checks
  });
  
</script>
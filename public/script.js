document.addEventListener("DOMContentLoaded", () => {

  // --- Overview Modal ---
  const overviewModal = document.getElementById("overviewModal");
  const overviewModalLabel = document.getElementById("overviewModalLabel");
  const overviewCarouselInner = document.getElementById("overview-carousel-inner");
  const overviewGenre = document.getElementById("overview-genre");
  const overviewDescription = document.getElementById("overview-description");
  const overviewPrice = document.getElementById("overview-price");
  const overviewAddToCart = document.getElementById("overview-add-to-cart");
  const overviewCarouselPreviews = document.getElementById("overview-carousel-previews");
  let currentOverviewProductId = null;

  // Checkout Modal
  const checkoutBtn = document.getElementById("checkout-btn");
  const checkoutSummary = document.getElementById("checkout-summary");
  const confirmPurchaseBtn = document.getElementById("confirm-purchase-btn");
  const checkoutThankyou = document.getElementById("checkout-thankyou");
  const checkoutForm = document.getElementById("checkout-form");
  const checkoutModalFooter = document.getElementById("checkout-modal-footer");
  const thankyouConfirmBtn = document.getElementById("thankyou-confirm-btn");

  const savedCart = localStorage.getItem("cart");
  const cart = savedCart ? JSON.parse(savedCart) : [];

  let productData = {};
  let genresList = [];
  let currentGenre = "all";
  let currentSort = "az";
  const sortSelect = document.getElementById("product-sort");

  /*
  // Render featured games on load and when navigating to Home
  document.querySelector('a[href="/"]').addEventListener("click", () => {
    renderFeaturedGames();
  });
  
  hide cuz not needed rn
  */

  // fade in on load lulll
  const fades = document.querySelectorAll(".page-fade");
  requestAnimationFrame(() => {
    fades.forEach(elem => elem.classList.add("fadein"));
  });

  // fade out weeeeee
  //const navLinks = document.querySelectorAll("a[href]"); for all stuff even not nav
  const navLinks = document.querySelectorAll("nav a[href]");

  navLinks.forEach(link => {
    link.addEventListener("click", event => {
      const targetUrl = link.getAttribute("href");

      // Ignore links like "#" or same-page anchors
      if (!targetUrl || targetUrl.startsWith("#")) return;

      event.preventDefault(); // stop immediate navigation

      const page = document.querySelector(".page-fade");
      if (page) {
        page.classList.remove("fadein");
        page.classList.add("fadeout");

        // After animation, navigate
        setTimeout(() => {
          window.location.href = targetUrl;
        }, 600); // match fadeout duration
      } else {
        // Fallback: just go immediately
        window.location.href = targetUrl;
      }
    });
  });

  // Add Sort dropdown listener
  if (sortSelect) {
    sortSelect.addEventListener("change", () => {
      currentSort = sortSelect.value;
      renderProductsSection(currentGenre, currentSort);
    });
  }

  $.validator.addMethod("textInput", function (value, element, maxLength) {
    const trimmed = value.trim();
    if (!trimmed) return false;
    if (!/[a-zA-Z0-9]/.test(trimmed)) return false;
    if (trimmed.length > maxLength) return false;
    return true;
  }, "Invalid input.");

  $.validator.addMethod("customEmail", function (value, element) {
    value = value.trim();
    if (!value) return false;

    const parts = value.split('@');
    if (parts.length !== 2) return false;

    const [local, domain] = parts;
    if (!local || !domain) return false;

    if (!/^[A-Za-z0-9](?:[A-Za-z0-9._-]*[A-Za-z0-9])?$/.test(local)) return false;
    if (!/^[A-Za-z0-9.-]+\.[A-Za-z]{2,3}$/.test(domain)) return false;

    const labels = domain.split('.');
    for (const label of labels) {
      if (label.length === 0) return false;
      if (!/[A-Za-z0-9]/.test(label)) return false;
      if (/^-|-$/.test(label)) return false;
    }

    return true;
  }, "Invalid email address");


  $.validator.addMethod("notEmpty", function (value, element) {
    return value.trim().length > 0;
  }, "This field cannot be empty.");

  // Contact number
  $.validator.addMethod("contactNumber", function (value, element) {
    value = value.trim();
    return this.optional(element) || /^\d{6,19}$/.test(value);
  }, "Please enter a valid contact number (6-19 digits).");

  $.validator.addMethod("strongPassword", function (value, element) {
    // At least 8 characters
    if (value.length < 8) return false;

    // At least 1 lowercase letter
    if (!/[a-z]/.test(value)) return false;

    // At least 1 uppercase letter
    if (!/[A-Z]/.test(value)) return false;

    // At least 1 number
    if (!/[0-9]/.test(value)) return false;

    // At least 1 special character
    if (!/[!@#$%^&*(),.?":{}|<>]/.test(value)) return false;

    return true;
  }, "Password must be at least 8 characters, with uppercase, lowercase, number, and special character.");

  $.validator.addMethod("passwordMatch", function (value, element, param) {
    const passwordVal = $(param).val().trim();
    return value.trim() === passwordVal && value.trim().length > 0;
  }, "Passwords do not match.");

  $.validator.addMethod("cardNumber", function (value, element) {
    const digits = value.replace(/\s+/g, "");
    return this.optional(element) || /^\d{16,19}$/.test(digits);
  }, "Invalid card number (16-19 digits).");

  $.validator.addMethod("cardExpiry", function (value, element) {
    if (!/^\d{2}\/\d{2}$/.test(value)) return false;
    const [mm, yy] = value.split("/").map(Number);
    if (mm < 1 || mm > 12) return false;
    const now = new Date();
    const year = 2000 + yy;
    const expiry = new Date(year, mm);
    return expiry > now;
  }, "Invalid expiry date.");

  $.validator.addMethod("cardCVC", function (value, element) {
    return this.optional(element) || /^\d{3,4}$/.test(value);
  }, "Invalid CVC.");

  $.validator.addMethod("paymentSelected", function (value, element) {
    return $('input[name="payment-method"]:checked').length > 0;
  }, "Please select a payment method.");


  renderCart();
  fetchProducts();
  fetchGenres();
  renderProductCategories();
  renderProductsSection();
  renderFeaturedGames();
  setupLoginForm();
  setupSignupForm();
  setupContactForm();
  setupCheckoutForm();
  setupCheckoutSummary();
  setupPasswordToggles();



  // resetModalForm('#loginModal');
  // resetModalForm('#signupModal');
  // resetModalForm('#checkoutModal');

  function goToPage(pageName) {
    const routes = {
      home: `${BASE_URL}/html/pages/home.php`,
      products: `${BASE_URL}/html/pages/products.php`,
      about: `${BASE_URL}/html/pages/about.php`,
      contact: `${BASE_URL}/html/pages/contact.php`,
      cart: `${BASE_URL}/html/pages/cart.php`
    };

    const target = routes[pageName.toLowerCase()];
    if (!target) return;

    const page = document.querySelector(".page-fade");
    if (page) {
      page.classList.remove("fadein");
      page.classList.add("fadeout");

      setTimeout(() => {
        window.location.href = target;
      }, 600);
    } else {
      window.location.href = target;
    }
  }

  function saveCart() {
    localStorage.setItem("cart", JSON.stringify(cart));
  }

  function renderFeaturedGames() {
    const featuredDiv = document.getElementById('featured-games');
    if (!featuredDiv) return;
    const products = Object.entries(productData).map(([id, p]) => ({ ...p, id }));
    if (products.length === 0) {
      featuredDiv.innerHTML = '<p>No games available.</p>';
      return;
    }
    const featured = pickRandom(products, Math.min(3, products.length));
    featuredDiv.innerHTML = featured.map(game => `
    <div class="col-md-4">
      <div class="card h-100">
        <div class="ratio ratio-1x1">
          <img src="${game.images[0]}" class="card-img-top object-fit-cover rounded" alt="${game.name}">
        </div>
        <div class="card-body">
          <h5 class="card-title">${game.name}</h5>
          <p class="card-text">₱${game.price}</p>
        </div>
      </div>
    </div>
  `).join('');
  }

  // pick n random items from array ---
  function pickRandom(arr, n) {
    const copy = arr.slice();
    for (let i = copy.length - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [copy[i], copy[j]] = [copy[j], copy[i]];
    }
    return copy.slice(0, n);
  }

  function updateCheckoutButton() {
    const btn = document.getElementById("checkout-btn");
    const btnRow = document.getElementById("checkout-btn-row");
    if (!btn || !btnRow) return;
    if (cart.length === 0) {
      btn.disabled = true;
      btnRow.classList.add("d-none");
    } else {
      btn.disabled = false;
      btnRow.classList.remove("d-none");
    }
  }

  function renderCart() {
    saveCart();
    const cartItemsDiv = document.getElementById("cart-items");
    const cartTotalDiv = document.getElementById("cart-total");
    const btnRow = document.getElementById("checkout-btn-row");
    if (!cartItemsDiv) return;

    if (cart.length === 0) {
      cartItemsDiv.innerHTML = `<p class="text-center">Your cart is empty 😢</p>`;
      cartTotalDiv.innerHTML = "";
      if (btnRow) btnRow.classList.add("d-none");
      return;
    }

    let html = `<table class="table table-dark table-striped align-middle">
      <thead>
        <tr>
          <th>Game</th>
          <th>Price</th>
          <th>Qty</th>
          <th>Subtotal</th>
          <th></th>
        </tr>
      </thead>
      <tbody>`;
    let total = 0;
    cart.forEach((item, idx) => {
      const subtotal = item.price * item.qty;
      total += subtotal;
      html += `<tr>
        <td>
          <img src="${item.img}" alt="${item.name}" style="width:40px;height:40px;object-fit:cover;margin-right:8px;">
          ${item.name}
        </td>
        <td>₱${item.price}</td>
        <td>
          <input type="number" min="1" value="${item.qty}" data-idx="${idx}" class="cart-qty form-control form-control-sm" style="width:70px;">
        </td>
        <td>₱${subtotal}</td>
        <td>
          <button class="btn btn-danger btn-sm cart-remove" data-idx="${idx}">Remove</button>
        </td>
      </tr>`;
    });
    html += `</tbody></table>`;
    cartItemsDiv.innerHTML = html;
    cartTotalDiv.innerHTML = `<h4>Total: ₱${total}</h4>`;

    // Quantity change
    cartItemsDiv.querySelectorAll(".cart-qty").forEach(input => {
      input.addEventListener("change", e => {
        const idx = parseInt(input.dataset.idx);
        let val = parseInt(input.value);

        if (isNaN(val) || val < 1) val = 1;
        if (val > 99) {
          val = 99;
          showAlert("Max quantity reached (99).");
        }

        cart[idx].qty = val;
        renderCart();
      });
    });

    // Remove item
    cartItemsDiv.querySelectorAll(".cart-remove").forEach(btn => {
      btn.addEventListener("click", e => {
        const idx = parseInt(btn.dataset.idx);
        cart.splice(idx, 1);
        renderCart();
      });
    });

    // Show checkout button row if cart has items
    if (btnRow) btnRow.classList.remove("d-none");
    updateCheckoutButton();
  }

  function setActivePreview(idx) {
    if (!overviewCarouselPreviews) return;
    overviewCarouselPreviews.querySelectorAll("img").forEach((img, i) => {
      img.classList.toggle("active", i === idx);
    });
  }

  function renderProductCategories() {
    const categoriesDiv = document.getElementById('product-categories');
    if (!categoriesDiv) return;

    categoriesDiv.innerHTML = `
    <div class="d-inline-flex flex-wrap gap-2 justify-content-center">
      <button class="btn btn-outline-primary btn-sm category-btn active" data-genre="all">All</button>
      ${genresList.map(g =>
      `<button class="btn btn-outline-primary btn-sm category-btn" data-genre="${g}">${g}</button>`
    ).join('')}
    </div>
  `;

    categoriesDiv.querySelectorAll('.category-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        categoriesDiv.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        currentGenre = btn.getAttribute('data-genre');
        renderProductsSection(currentGenre, currentSort);
      });
    });
  }

  function renderProductsSection(filterGenre = "all", sortBy = "az") {
    const productsList = document.getElementById('products-list');
    if (!productsList) return;

    let entries = Object.entries(productData);

    // Filter
    if (filterGenre !== "all") {
      entries = entries.filter(([id, data]) => {
        if (Array.isArray(data.genres)) {
          return data.genres.includes(filterGenre);
        }
        return false;
      });
    }

    // Sort
    entries.sort(([, a], [, b]) => {
      switch (sortBy) {
        case "az": return a.name.localeCompare(b.name);
        case "za": return b.name.localeCompare(a.name);
        case "price-asc": return a.price - b.price;
        case "price-desc": return b.price - a.price;
        default: return 0;
      }
    });

    // Render cards
    productsList.innerHTML = entries.map(([id, data]) => `
    <div class="col-md-4">
      <div class="card h-100">
        <div class="ratio ratio-1x1">
          <img src="${data.images[0]}" class="card-img-top object-fit-cover rounded" alt="${data.name}">
        </div>
        <div class="card-body">
          <h5 class="card-title">${data.name}</h5>
          <p class="card-text">₱${data.price}</p>
          <a href="/" class="btn btn-primary w-100 overview-btn" data-product-id="${id}">Overview</a>
          <a href="/" class="btn btn-secondary w-100 add-to-cart" data-product-id="${id}">Add to Cart</a>
        </div>
      </div>
    </div>
  `).join('');

    attachProductEventListeners();
    attachOverviewModalListeners();
  }

  function attachProductEventListeners() {
    // Add to Cart handler (Products section)
    document.querySelectorAll(".add-to-cart").forEach(btn => {
      btn.onclick = null;
      btn.addEventListener("click", e => {
        e.preventDefault();
        const productId = btn.getAttribute("data-product-id");
        const data = productData[productId];
        if (!data) return;
        const existing = cart.find(item => item.name === data.name);
        if (existing) {
          existing.qty += 1;
        } else {
          cart.push({ name: data.name, price: data.price, img: data.images[0], qty: 1 });
        }
        renderCart();
        goToPage("cart");
      });
    });
  }

  function attachOverviewModalListeners() {
    // Remove previous listeners to avoid duplicates
    document.querySelectorAll(".overview-btn").forEach(btn => {
      btn.onclick = null;
      btn.addEventListener("click", e => {
        e.preventDefault();
        const productId = btn.getAttribute("data-product-id");
        const data = productData[productId];
        if (!data) return;

        // Title
        overviewModalLabel.textContent = data.name;

        // Carousel (16:9 ratio for each image)
        overviewCarouselInner.innerHTML = data.images.map((img, idx) =>
          `<div class="carousel-item${idx === 0 ? " active" : ""}">
            <div class="ratio ratio-16x9">
              <img src="${img}" class="d-block w-100 rounded" alt="${data.name} image ${idx + 1}" style="object-fit:cover;">
            </div>
          </div>`
        ).join("");

        // Previews below carousel
        if (overviewCarouselPreviews) {
          overviewCarouselPreviews.innerHTML = data.images.map((img, idx) =>
            `<img src="${img}" data-idx="${idx}" alt="Preview ${idx + 1}" class="${idx === 0 ? "active" : ""}">`
          ).join("");
        }

        // Preview click: jump to carousel slide
        if (overviewCarouselPreviews) {
          overviewCarouselPreviews.querySelectorAll("img").forEach((img, idx) => {
            img.onclick = () => {
              const carousel = window.bootstrap && window.bootstrap.Carousel
                ? bootstrap.Carousel.getOrCreateInstance(document.getElementById("overviewCarousel"))
                : null;
              if (carousel) carousel.to(idx);
              setActivePreview(idx);
            };
          });
        }

        // --- Sync preview highlight on carousel slide ---
        const carouselElem = document.getElementById("overviewCarousel");
        if (carouselElem) {
          // Remove previous event listeners to avoid stacking
          carouselElem._previewSyncHandler && carouselElem.removeEventListener("slid.bs.carousel", carouselElem._previewSyncHandler);
          carouselElem._previewSyncHandler = function () {
            const items = Array.from(overviewCarouselInner.querySelectorAll(".carousel-item"));
            const idx = items.findIndex(item => item.classList.contains("active"));
            setActivePreview(idx);
          };
          carouselElem.addEventListener("slid.bs.carousel", carouselElem._previewSyncHandler);
        }

        // Reset carousel to first slide
        if (window.bootstrap && window.bootstrap.Carousel) {
          try {
            const carousel = bootstrap.Carousel.getOrCreateInstance(document.getElementById("overviewCarousel"));
            carousel.to(0);
          } catch { }
        }

        // Genre, Description, Price
        overviewGenre.textContent = data.genre;
        overviewDescription.textContent = data.description;
        overviewPrice.textContent = `₱${data.price}`;

        // Store current product id for add to cart
        currentOverviewProductId = productId;

        // Show modal
        if (window.bootstrap && window.bootstrap.Modal) {
          const modal = bootstrap.Modal.getOrCreateInstance(overviewModal);
          modal.show();
        }
      });
    });

    // Add to Cart from Overview Modal
    if (overviewAddToCart) {
      overviewAddToCart.onclick = () => {
        if (!currentOverviewProductId) return;
        const data = productData[currentOverviewProductId];
        if (!data) return;
        const existing = cart.find(item => item.name === data.name);
        if (existing) {
          existing.qty += 1;
        } else {
          cart.push({ name: data.name, price: data.price, img: data.images[0], qty: 1 });
        }
        renderCart();
        goToPage("cart");

        if (window.bootstrap && window.bootstrap.Modal) {
          const modal = bootstrap.Modal.getInstance(overviewModal);
          if (modal) modal.hide();
        }
      };
    }
  }

  function fetchGenres() {
    return $.ajax({
      url: `${BASE_URL}/api/get_genres.php`,
      type: 'GET',
      dataType: 'json',
      success: function (response) {
        if (response.success) {
          genresList = response.genres || [];
          renderProductCategories();
        } else {
          console.error("Failed to fetch genres:", response.error);
          showAlert("Failed to load genres. See console for details.", "danger");
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error("AJAX error (genres):", textStatus, errorThrown);
        console.error("Response:", jqXHR.responseText);
        showAlert("An error occurred while loading genres.", "danger");
      }
    });
  }

  function fetchProducts() {
    $.ajax({
      url: `${BASE_URL}/api/get_products.php`,
      type: 'GET',
      dataType: 'json',
      success: function (response) {
        if (response.success) {
          productData = response.products;
          renderProductsSection();
          renderFeaturedGames();
        } else {
          console.error("Failed to fetch products:", response.message);
          showAlert("Failed to load products. See console for details.", "danger");
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error("AJAX error:", textStatus, errorThrown);
        console.error("Status code:", jqXHR.status);
        console.error("Response text:", jqXHR.responseText);
        showAlert("An error occurred while loading products. This shouldn't happen.", "danger");
      }
    });
  }

  function setupLoginForm() {
    const $form = $('#login-form');
    const $email = $('#login-email');
    const $invalid = $('#login-invalid-feedback');

    restrictToEmail($email);

    $form.validate({
      onkeyup: function (element) { $(element).valid(); $invalid.hide(); },
      onfocusout: function (element) { $(element).valid(); },
      rules: {
        'login-email': {
          required: true,
          customEmail: true
        },
        'login-password': {
          required: true
        }
      },
      messages: {
        'login-email': {
          required: "Please enter your email",
        },
        'login-password': {
          required: "Please enter your password",
        }
      },
      errorClass: "is-invalid",
      validClass: "is-valid",
      errorPlacement: function (error, element) {
        const $feedback = $('#' + element.attr('id') + '-feedback');
        if ($feedback.length) $feedback.text(error.text());
      },
      highlight: function (element) {
        $(element).addClass('is-invalid').removeClass('is-valid');
      },
      unhighlight: function (element) {
        $(element).removeClass('is-invalid').addClass('is-valid');
        const $feedback = $('#' + element.id + '-feedback');
        if ($feedback.length) $feedback.text('');
      },
      submitHandler: function (form) {
        $.ajax({
          url: `${BASE_URL}/api/login_submit.php`,
          type: 'POST',
          data: $(form).serialize(),
          dataType: 'json',
          success: function (response) {
            if (response.success) {
              $invalid.hide();
              $('#login-email, #login-password').removeClass('is-invalid');
              showAlert("Successfully logged in as user: " + response.name + "\nid: " + response.id, "success", 10000);
              // Update client auth state so modal lock is lifted
              if (window.AUTH) {
                window.AUTH.loggedIn = true;
                window.AUTH.userName = response.name || window.AUTH.userName;
              }
              const modalEl = document.getElementById('loginModal');
              // Restore the close button visibility for future modal uses
              const closeBtn = modalEl?.querySelector('[data-bs-dismiss="modal"]');
              if (closeBtn) closeBtn.classList.remove('d-none');
              // Now safely hide the modal (lock will not block since loggedIn=true)
              bootstrap.Modal.getInstance(modalEl)?.hide();
              // Refresh to re-render server-side navbar state immediately
              setTimeout(() => { window.location.reload(); }, 100);
            } else {
              console.log(response.message);
              $invalid.show().text('Invalid email or password.');
              $('#login-email, #login-password').addClass('is-invalid').removeClass('is-valid').val('');
            }
          },
          error: function (jqXHR, textStatus, errorThrown) {
            console.error("AJAX error:", textStatus, errorThrown);
            console.error("Status code:", jqXHR.status);
            console.error("Response text:", jqXHR.responseText);
            showAlert("An unexpected error occurred. This shouldn't happen.", "danger");
          }
        });
      }
    });
  }

  function setupSignupForm() {
    const $form = $('#signup-form');
    const $name = $('#signup-name');
    const $email = $('#signup-email');
    const $address = $('#signup-address');
    const $contact = $('#signup-contact');
    const $password = $('#signup-password');
    const $confirm = $('#signup-confirm');

    restrictToEmail($email);
    restrictToDigits($contact);

    $form.validate({
      onkeyup: function (element) { $(element).valid(); },
      onfocusout: function (element) { $(element).valid(); },
      rules: {
        'signup-name': {
          required: true,
          textInput: 30
        },
        'signup-email': {
          required: true,
          customEmail: true
        },
        'signup-address': {
          required: true,
          textInput: 50
        },
        'signup-contact': {
          required: true,
          contactNumber: true
        },
        'signup-password': {
          required: true,
          strongPassword: true
        },
        'signup-confirm': {
          required: true,
          passwordMatch: '#signup-password'
        }
      },
      messages: {
        'signup-name': {
          required: "Please enter your name",
        },
        'signup-email': {
          required: "Please enter your email",
        },
        'signup-address': {
          required: "Please enter your address",
        },
        'signup-contact': {
          required: "Please enter your contact number",
        },
        'signup-password': {
          required: "Please enter your password",
        },
        'signup-confirm': {
          required: "Please reenter your password",
        }
      },
      errorClass: "is-invalid",
      validClass: "is-valid",
      errorPlacement: function (error, element) {
        const $feedback = $('#' + element.attr('id') + '-feedback');
        if ($feedback.length) $feedback.text(error.text());
      },
      highlight: function (element) {
        $(element).addClass('is-invalid').removeClass('is-valid');
      },
      unhighlight: function (element) {
        $(element).removeClass('is-invalid').addClass('is-valid');
        const $feedback = $('#' + element.id + '-feedback');
        if ($feedback.length) $feedback.text('');
      },
      submitHandler: function (form) {
        $.ajax({
          type: "POST",
          url: BASE_URL + "/api/signup_submit.php",
          data: $(form).serialize(),
          dataType: "json",
          success: function (response) {
            if (response.success) {
              showAlert(response.message, "success");
              bootstrap.Modal.getInstance(document.getElementById('signupModal'))?.hide();
            } else {
              showAlert(response.message, "danger");
              $email.trigger(focus).val('');
            }
          },
          error: function (jqXHR, textStatus, errorThrown) {
            console.error("AJAX error:", textStatus, errorThrown);
            console.error("Status code:", jqXHR.status);
            console.error("Response text:", jqXHR.responseText);
            showAlert("An unexpected error occurred. This shouldn't happen.", "danger");
          }
        });
      }
    });
  }

  function setupCheckoutForm() {
    const $form = $('#checkout-form');
    const $confirm = $('#confirm-purchase-btn');
    const $thankyou = $('#checkout-thankyou');
    const $modalFooter = $('#checkout-modal-footer');

    $form.validate({
      onkeyup: function (element) { $(element).valid(); },
      onfocusout: function (element) { $(element).valid(); },
      rules: {
        'checkout-email': {
          required: true,
          customEmail: true
        },
        'payment-method': {
          paymentSelected: true
        },
        'card-number': {
          required: function () { return $('input[name="payment-method"]:checked').val() === 'card'; },
          cardNumber: true
        },
        'card-expiry': {
          required: function () { return $('input[name="payment-method"]:checked').val() === 'card'; },
          cardExpiry: true
        },
        'card-cvc': {
          required: function () { return $('input[name="payment-method"]:checked').val() === 'card'; },
          cardCVC: true
        },
        'card-name': {
          required: function () { return $('input[name="payment-method"]:checked').val() === 'card'; },
          textInput: 50
        }
      },
      messages: {
        'checkout-email': {
          required: "Please enter your email",
        },
        'payment-method': {
          required: "Please select a payment method.",
        },
        'card-number': {
          required: "Please enter the Card's Number",
        },
        'card-expiry': {
          required: "Please enter the Card's Expiry",
        },
        'card-cvc': {
          required: "Please enter the Card's CVC",
        },
        'card-name': {
          required: "Please enter the Cardholder's Name",
        }
      },
      errorClass: "is-invalid",
      validClass: "is-valid",
      errorPlacement: function (error, element) {
        const $feedback = $('#' + element.attr('id') + '-feedback');
        if ($feedback.length) $feedback.text(error.text());

        if (element.attr('name') === 'payment-method') {
          $('#payment-method-feedback').show().text(error.text());
        }
      },
      highlight: function (element) {
        $(element).removeClass('is-valid');
        if ($(element).attr('name') !== 'payment-method') {
          $(element).addClass('is-invalid');
        }
        if ($(element).attr('name') === 'payment-method') $('#payment-method-feedback').show();
      },
      unhighlight: function (element) {
        $(element).removeClass('is-invalid');
        if ($(element).attr('name') !== 'payment-method') {
          $(element).addClass('is-valid');
        }
        const $feedback = $('#' + element.id + '-feedback');
        if ($feedback.length) $feedback.text('');
        if ($(element).attr('name') === 'payment-method') $('#payment-method-feedback').hide();
      },
      submitHandler: function (form) {
        console.log('✅ Checkout successful');
        $form.hide();
        if ($modalFooter.length) $modalFooter.hide();
        if ($thankyou.length) $thankyou.show();
        cart.length = 0;
        renderCart();
        updateCheckoutButton();
        $confirm.prop('disabled', true);
        //bootstrap.Modal.getInstance(document.getElementById('checkoutModal'))?.hide();
      }
    });

    // Toggle card fields visibility
    $('input[name="payment-method"]').on('change', () => {
      const checked = $('input[name="payment-method"]:checked').val();
      const $cardDetails = $('#card-details');
      if (checked === 'card') {
        $cardDetails.slideDown();
      } else {
        $cardDetails.slideUp();
        $('#card-number, #card-expiry, #card-cvc, #card-name').removeClass('is-invalid is-valid');
        $form.validate().resetForm(); // reset card validation
      }
    });

    // Enable/disable confirm button dynamically
    $form.find('input, select').add('input[name="payment-method"]').on('input change', () => {
      $confirm.prop('disabled', !$form.valid());
    });

    $('#thankyou-confirm-btn').on('click', () => {
      setTimeout(() => {
        if ($thankyou.length) $thankyou.hide();
        if ($modalFooter.length) $modalFooter.show();
        $form.show();
      }, 300);
    });
  }

  function setupContactForm() {
    const $form = $('#contact-form');
    const $name = $('#contact-name');
    const $email = $('#contact-email');
    const $message = $('#contact-message');
    const $success = $('#contact-success');

    if (!$form.length) return;

    restrictToEmail($email);

    $form.validate({
      onkeyup: function (element) { $(element).valid(); },
      onfocusout: function (element) { $(element).valid(); },
      rules: {
        'contact-name': {
          required: true,
          textInput: 30
        },
        'contact-email': {
          required: true,
          customEmail: true
        },
        'contact-message': {
          required: true,
          textInput: 1000
        }
      },
      messages: {
        'contact-name': {
          required: "Please enter your name",
        },
        'contact-email': {
          required: "Please enter your email",
        },
        'contact-message': {
          required: "Please enter a message",
        }
      },
      errorClass: "is-invalid",
      validClass: "is-valid",
      errorPlacement: function (error, element) {
        const $feedback = $('#' + element.attr('id') + '-feedback');
        if ($feedback.length) $feedback.text(error.text());
      },
      highlight: function (element) {
        $(element).addClass('is-invalid').removeClass('is-valid');
      },
      unhighlight: function (element) {
        $(element).removeClass('is-invalid').addClass('is-valid');
        const $feedback = $('#' + element.id + '-feedback');
        if ($feedback.length) $feedback.text('');
      },
      submitHandler: function (form) {
        $.ajax({
          type: "POST",
          url: BASE_URL + "/api/contact_submit.php",
          data: $(form).serialize(),
          dataType: "json",
          success: function (response) {
            if (response.success) {
              showAlert(response.message, "success");
              form.reset();
              $(form).find(".is-valid").removeClass("is-valid");

              $success.removeClass('d-none');
              setTimeout(() => {
                $success.addClass('d-none');
              }, 3000);
            } else {
              showAlert(response.message, "danger");
            }
          },
          error: function (jqXHR, textStatus, errorThrown) {
            console.error("AJAX error:", textStatus, errorThrown);
            console.error("Status code:", jqXHR.status);
            console.error("Response text:", jqXHR.responseText);
            showAlert("An unexpected error occurred. This shouldn't happen.", "danger");
          }
        });
      }
    });
  }

  function setupPasswordToggles() {
    $('.toggle-password').on('click', function () {
      const target = $($(this).data('target'));
      const icon = $(this).find('i');
      const isPassword = target.attr('type') === 'password';

      target.attr('type', isPassword ? 'text' : 'password');
      icon.toggleClass('bi-eye bi-eye-slash');
    });
  }

  function setupCheckoutSummary() {
    if (checkoutBtn && checkoutSummary) {
      checkoutBtn.addEventListener("click", () => {
        if (cart.length === 0) {
          checkoutSummary.innerHTML = "<p>Your cart is empty.</p>";
          return;
        }

        let html = `<table class="table table-dark table-striped align-middle">
          <thead>
            <tr>
              <th>Game</th>
              <th>Price</th>
              <th>Qty</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>`;
        let total = 0;

        cart.forEach(item => {
          const subtotal = item.price * item.qty;
          total += subtotal;
          html += `
            <tr>
              <td>${item.name}</td>
              <td>₱${item.price}</td>
              <td>${item.qty}</td>
              <td>₱${subtotal}</td>
            </tr>`;
        });

        html += `</tbody></table>
          <div class="text-end"><strong>Total: ₱${total}</strong></div>`;
        checkoutSummary.innerHTML = html;

        // reset UI state if already purchased earlier
        if (checkoutThankyou) checkoutThankyou.style.display = "none";
        if (checkoutForm) checkoutForm.style.display = "";
        if (checkoutModalFooter) checkoutModalFooter.style.display = "";
      });
    }
  }

  //clears modal inputs
  function resetModalForm(modalSelector) {
    const $modal = $(modalSelector);

    // When modal is fully hidden
    $modal.on('hidden.bs.modal', function () {
      const $form = $modal.find('form');
      $form.trigger('reset'); // clear all values

      // remove invalid classes
      $form.find('.is-invalid').removeClass('is-invalid');

      // hide any shared error feedbacks like login
      $form.find('.invalid-feedback').hide();
    });
  }

  function restrictToDigits($el) {
    $el.on('keypress', e => {
      if (!/[0-9]/.test(String.fromCharCode(e.which))) e.preventDefault();
    });
  }

  function restrictToEmail($el) {
    $el.on('keypress', e => {
      const char = String.fromCharCode(e.which);
      const val = $el.val();
      if (!/[a-zA-Z0-9._@-]/.test(char) || (char === '@' && val.includes('@'))) {
        e.preventDefault();
      }
    });
  }

  function showAlert(message, type = "danger", duration = 3000) {
    const container = document.getElementById("global-alert-container");
    if (!container) return;

    // Cap to max 3 alerts, remove oldest if needed
    const alerts = container.querySelectorAll(".alert");
    if (alerts.length >= 3) {
      const oldest = alerts[0];
      const bsOldest = bootstrap.Alert.getOrCreateInstance(oldest);
      oldest.classList.add("alert-exit-active");
      setTimeout(() => bsOldest.close(), 300);
    }

    // Create alert element
    const alertDiv = document.createElement("div");
    alertDiv.className = `alert alert-${type} alert-dismissible text-center shadow fade show alert-enter`;
    alertDiv.setAttribute("role", "alert");
    alertDiv.innerHTML = `
      ${message}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    container.appendChild(alertDiv);

    // Animate in
    requestAnimationFrame(() => {
      alertDiv.classList.remove("alert-enter");
      alertDiv.classList.add("alert-enter-active");
    });

    // Auto-dismiss after duration (if > 0)
    if (duration > 0) {
      setTimeout(() => {
        alertDiv.classList.remove("alert-enter-active");
        alertDiv.classList.add("alert-exit-active");
        const bsAlert = bootstrap.Alert.getOrCreateInstance(alertDiv);
        setTimeout(() => bsAlert.close(), 300);
      }, duration);
    }

    // Animate out when closed manually
    alertDiv.addEventListener('close.bs.alert', () => {
      alertDiv.classList.remove("alert-enter-active");
      alertDiv.classList.add("alert-exit-active");
    });
  }

});

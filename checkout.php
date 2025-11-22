<?php
session_start();

// require login
if (!isset($_SESSION['user'])) {
  header('Location: signin.php');
  exit;
}

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
  header('Location: cart.php');
  exit;
}

$total = 0; foreach($cart as $c) $total += $c['qty'] * $c['price'];
$user = $_SESSION['user'];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Checkout - Anistore</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="assets/style.css">
  <style>
    /* Grid for checkout: left form + right summary */
    .checkout-grid { display:grid; grid-template-columns: 1fr 420px; gap:20px; align-items:start; }
    @media (max-width:980px){ .checkout-grid{grid-template-columns:1fr} .sidebar{display:none!important} }

    /* Card shared style */
    .checkout-card { border-radius:8px; background:#fff; box-shadow:0 10px 30px rgba(13,30,60,0.06); border:1px solid rgba(6,22,40,0.04); }

    /* Make left card look like the sign-in card (stacked, centered fields) */
    .billing-wrapper { padding:28px 32px; max-width:720px; }
    .billing-card {
      width:100%;
      background: transparent;
      border-radius: 6px;
      padding: 0;
      box-shadow: none;
      border: none;
    }

    .billing-card h3 { margin:0 0 10px; font-size:20px; font-weight:700; color:#0b1726; }
    .form-field { margin-bottom:14px; }
    .form-field label { display:block; font-size:13px; color:#55616a; margin-bottom:8px; }
    .form-field input[type="text"],
    .form-field input[type="tel"],
    .form-field textarea,
    .form-field select {
      width:100%; padding:12px 12px; border-radius:8px; border:1px solid #e6eef5; font-size:14px; box-sizing:border-box;
      background:#fff; color:#0b1220;
    }
    .form-field textarea { min-height:100px; resize:vertical; }

    .btn-primary.full {
      width:100%; padding:12px 14px; border-radius:8px; border:0; font-weight:700; cursor:pointer;
      background: linear-gradient(90deg,var(--accent),var(--accent-2)); color:#fff; font-size:15px;
      box-shadow: 0 6px 14px rgba(31, 143, 255, 0.12);
    }

    /* small helper to group name/phone side-by-side on wide screens */
    .row { display:flex; gap:12px; }
    .row .half { flex:1; }

    /* Order summary tweaks */
    .summary-item { display:flex; gap:12px; align-items:center; margin-bottom:12px; }
    .summary-item img{ width:64px;height:64px;object-fit:cover;border-radius:6px }

    /* subtle spacing inside right summary card */
    .checkout-summary { padding:18px; }
    .checkout-summary h3 { margin-top:0; }

    /* responsive: stack nicely */
    @media (max-width:760px){
      .row { flex-direction:column; }
    }
  </style>
</head>
<body>
  <div class="app-shell storefront">
    <?php include __DIR__ . '/_sidebar.php'; ?>
    <main class="main-area">
      <header class="topbar"><div><h1>Checkout</h1></div><div class="top-right"><a class="icon-btn" href="cart.php"><i class="fa-solid fa-cart-shopping"></i></a></div></header>

      <section class="content container">
        <div class="checkout-grid">

          <!-- LEFT: Billing & Shipping - styled like sign-in card -->
          <div class="checkout-card billing-wrapper">
            <div class="billing-card">
              <h3>Billing & Shipping</h3>

              <form method="post" action="place_order.php" novalidate>
                <input type="hidden" name="action" value="place_order">

                <div class="row">
                  <div class="half form-field">
                    <label for="name">Name</label>
                    <input id="name" name="name" required value="<?php echo htmlspecialchars($user['name']); ?>" />
                  </div>
                  <div class="half form-field">
                    <label for="phone">Phone</label>
                    <input id="phone" name="phone" type="tel" required placeholder="e.g. +91 98765 43210" />
                  </div>
                </div>

                <div class="form-field">
                  <label for="address">Address</label>
                  <textarea id="address" name="address" required placeholder="House no, Street, City, State, PIN"></textarea>
                </div>

                <div class="form-field">
                  <label for="payment_method">Payment method</label>
                  <select id="payment_method" name="payment_method">
                    <option value="COD">Cash on delivery (COD)</option>
                  </select>
                </div>

                <div style="margin-top:16px;">
                  <button class="btn-primary full" type="submit">Place Order — ₹<?php echo number_format($total,2); ?></button>
                </div>

              </form>
            </div>
          </div>

          <!-- RIGHT: Order summary (keeps previous look) -->
          <aside class="checkout-card checkout-summary">
            <h3>Order summary</h3>
            <div style="margin-top:10px">
              <?php foreach($cart as $c): ?>
                <div class="summary-item">
                  <img src="<?php echo htmlspecialchars($c['image']); ?>" alt="">
                  <div>
                    <div style="font-weight:700"><?php echo htmlspecialchars($c['name']); ?></div>
                    <div class="muted">Qty: <?php echo intval($c['qty']); ?> × ₹<?php echo number_format($c['price'],2); ?></div>
                    <div style="font-weight:600; margin-top:6px">₹<?php echo number_format($c['qty']*$c['price'],2); ?></div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

            <hr style="border:none;height:1px;background:#f1f5f9;margin:12px 0;">
            <div style="display:flex;justify-content:space-between">
              <div class="muted">Subtotal</div>
              <div>₹<?php echo number_format($total,2); ?></div>
            </div>
            <div style="display:flex;justify-content:space-between;margin-top:6px">
              <div class="muted">Shipping</div>
              <div class="muted">Calculated at delivery</div>
            </div>
            <div style="display:flex;justify-content:space-between;margin-top:12px;font-weight:700">
              <div>Total</div>
              <div>₹<?php echo number_format($total,2); ?></div>
            </div>
          </aside>

        </div>
      </section>

      <footer class="footer"><div class="container muted">© <?php echo date('Y'); ?> Anistore</div></footer>
    </main>
  </div>
</body>
</html>

<?php
session_start();

// require login
if (!isset($_SESSION['user'])) {
  header('Location: signin.php');
  exit;
}

$cart = $_SESSION['cart'] ?? [];
$total = 0; foreach($cart as $c) $total += $c['qty'] * $c['price'];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Cart - Anistore</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <div class="app-shell storefront">
    <?php include __DIR__ . '/_sidebar.php'; ?>
    <main class="main-area">
      <header class="topbar"><div><h1>Your Cart</h1></div><div class="top-right"><a class="icon-btn" href="products.php"><i class="fa-solid fa-box-open"></i></a></div></header>

      <section class="content container">
        <div class="card">
          <?php if(empty($cart)): ?>
            <p>Your cart is empty. <a href="products.php">Shop now</a></p>
          <?php else: ?>
            <table class="cart-table" style="width:100%;border-collapse:collapse">
              <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th><th></th></tr></thead>
              <tbody>
                <?php foreach($cart as $id=>$c): ?>
                <tr>
                  <td style="display:flex;gap:12px;align-items:center">
                    <img src="<?php echo htmlspecialchars($c['image']); ?>" style="width:64px;height:64px;object-fit:cover;border-radius:6px">
                    <div><strong><?php echo htmlspecialchars($c['name']); ?></strong></div>
                  </td>
                  <td>₹<?php echo number_format($c['price'],2); ?></td>
                  <td>
                    <form method="post" action="add_to_cart.php" style="display:flex;gap:6px;align-items:center">
                      <input type="hidden" name="action" value="update">
                      <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                      <input type="number" name="qty" value="<?php echo intval($c['qty']); ?>" min="0" style="width:70px;padding:6px;border-radius:6px;border:1px solid #ddd">
                      <button class="btn-primary small" type="submit">Update</button>
                    </form>
                  </td>
                  <td>₹<?php echo number_format($c['qty']*$c['price'],2); ?></td>
                  <td>
                    <form method="post" action="add_to_cart.php">
                      <input type="hidden" name="action" value="remove">
                      <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                      <button class="btn-ghost small" type="submit">Remove</button>
                    </form>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>

            <div style="display:flex;justify-content:space-between;margin-top:18px;gap:18px;align-items:center">
              <div style="text-align:left">
                <div class="muted">Total items: <?php echo array_sum(array_column($cart,'qty')); ?></div>
              </div>

              <div style="text-align:right">
                <div class="muted">Total</div>
                <div style="font-size:22px;font-weight:700">₹<?php echo number_format($total,2); ?></div>
                <div style="margin-top:12px; display:flex; gap:10px; justify-content:flex-end;">
                  <a href="products.php" class="btn-ghost">Continue shopping</a>
                  <a href="checkout.php" class="btn-primary">Checkout</a>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </section>

      <footer class="footer"><div class="container muted">© <?php echo date('Y'); ?> Anistore</div></footer>
    </main>
  </div>
</body>
</html>

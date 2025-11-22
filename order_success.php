<?php
session_start();
if (!isset($_SESSION['user'])) {
  header('Location: signin.php');
  exit;
}
$order_id = $_GET['order_id'] ?? '';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Order Successful — Anistore</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <div class="app-shell storefront">
    <?php include __DIR__ . '/_sidebar.php'; ?>
    <main class="main-area">
      <header class="topbar"><div><h1>Order placed</h1></div></header>
      <section class="content container">
        <div class="card" style="max-width:800px;margin:20px auto;text-align:center">
          <h2>Thank you — your order is confirmed</h2>
          <p style="margin-top:6px">Order ID: <strong><?php echo htmlspecialchars($order_id); ?></strong></p>
          <p class="muted">You can view your past orders on the Orders page.</p>
          <div style="margin-top:12px;">
            <a href="orders.php" class="btn-primary">View Orders</a>
            <a href="index.php" class="btn-ghost">Continue Shopping</a>
          </div>
        </div>
      </section>
    </main>
  </div>
</body>
</html>

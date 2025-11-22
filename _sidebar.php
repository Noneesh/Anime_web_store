<?php
// _sidebar.php - shared sidebar (shows store links only to signed-in users)
$user = $_SESSION['user'] ?? null;
?>
<aside class="sidebar">
  <div class="logo">
    <img src="assets/hero-placeholder.png" alt="logo">
    <div class="brand">Anistore</div>
  </div>

  <?php if($user): ?>
    <div style="color:rgba(255,255,255,0.9);font-weight:700;padding:6px 0">Hi, <?php echo htmlspecialchars($user['name']); ?></div>
    <nav class="side-nav">
      <a class="nav-item" href="index.php"><i class="fa-solid fa-house"></i> Dashboard</a>

      <div class="nav-section">Store</div>
      <a class="nav-item" href="products.php?cat=Clothes"><i class="fa-solid fa-shirt"></i> Clothes</a>
      <a class="nav-item" href="products.php?cat=Stickers"><i class="fa-solid fa-sticky-note"></i> Stickers</a>
      <a class="nav-item" href="products.php?cat=Keychains"><i class="fa-solid fa-key"></i> Keychains</a>
      <a class="nav-item" href="products.php?cat=Bags"><i class="fa-solid fa-bag-shopping"></i> Bags</a>
      <a class="nav-item" href="products.php?cat=Shoes"><i class="fa-solid fa-shoe-prints"></i> Shoes</a>

      <div class="nav-section">Manage</div>
      <a class="nav-item" href="products.php"><i class="fa-solid fa-box-open"></i> Collections</a>
      <a class="nav-item" href="orders.php"><i class="fa-solid fa-receipt"></i> Orders</a>

      <div class="nav-section">Customers</div>
      <a class="nav-item" href="contact.php"><i class="fa-solid fa-users"></i> Customers</a>

      <div class="nav-section">Support</div>
      <a class="nav-item" href="inbox.php"><i class="fa-solid fa-envelope"></i> Inbox</a>

      <a class="nav-item" href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Sign out</a>
    </nav>

  <?php else: ?>
    <nav class="side-nav">
      <div style="padding:12px 0;">
        <a class="nav-item" href="signin.php"><i class="fa-solid fa-right-to-bracket"></i> Sign in</a>
        <a class="nav-item" href="signup.php"><i class="fa-solid fa-user-plus"></i> Sign up</a>
      </div>

      <div class="nav-section">Info</div>
      <a class="nav-item" href="contact.php"><i class="fa-solid fa-envelope"></i> Contact</a>
      <a class="nav-item" href="index.php"><i class="fa-solid fa-circle-info"></i> About</a>
    </nav>
  <?php endif; ?>

  <div class="sidebar-bottom">
    <button class="btn-ghost">Design Site</button>
  </div>
</aside>

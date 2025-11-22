<?php
session_start();

if (!isset($_SESSION['user'])) {
  header('Location: signin.php');
  exit;
}

$products = include __DIR__ . '/data/products.php';
$featured = array_slice($products, 0, 6);
?>
<html lang="en">
<head>
  <title>Anistore — Home</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <div class="app-shell storefront">
    <?php include __DIR__ . '/_sidebar.php'; ?>
    <main class="main-area">
      <header class="topbar store-top">
        <div>
          <h1>Anistore — Anime Merch</h1>
          <p class="muted">Shop clothes, stickers, keychains, bags & shoes.</p>
        </div>
        <div class="top-right"><a class="icon-btn" href="cart.php" title="Cart"><i class="fa-solid fa-cart-shopping"></i></a></div>
      </header>

      <section class="content container">
        <div class="categories-grid">
          <?php $cats=['Clothes','Keychains','Stickers','Bags','Shoes']; foreach($cats as $cat): ?>
            <a class="category-card" href="products.php?cat=<?php echo urlencode($cat); ?>">
              <img src="assets/cat-<?php echo strtolower($cat); ?>.png" alt="">
              <div class="cat-name"><?php echo htmlspecialchars($cat); ?></div>
            </a>
          <?php endforeach; ?>
        </div>

        <div class="card">
          <h2>Featured Products</h2>
          <div class="products-grid">
            <?php foreach($featured as $p): ?>
              <div class="product-card">
                <img src="<?php echo htmlspecialchars($p['image']); ?>" alt="">
                <div class="product-info">
                  <div class="p-name"><?php echo htmlspecialchars($p['name']); ?></div>
                  <div class="p-price">₹<?php echo number_format($p['price'],2); ?></div>
                </div>
                <form method="post" action="add_to_cart.php" class="cart-form">
                  <input type="hidden" name="id" value="<?php echo htmlspecialchars($p['id']); ?>">
                  <input type="hidden" name="action" value="add">
                  <input type="number" name="qty" min="1" value="1" class="qty-input">
                  <button class="btn-primary" type="submit">Add to cart</button>
                </form>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </section>

      <footer class="footer"><div class="container muted">© <?php echo date('Y'); ?> Anistore</div></footer>
    </main>
  </div>
</body>
</html>

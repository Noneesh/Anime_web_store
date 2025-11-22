<?php
session_start();

// require login
if (!isset($_SESSION['user'])) {
  header('Location: signin.php');
  exit;
}

$products = include __DIR__ . '/data/products.php';
$cat = isset($_GET['cat']) ? trim($_GET['cat']) : '';
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$filtered = array_filter($products, function($p) use ($cat, $q){
  if($cat !== '' && strcasecmp($p['category'],$cat) !== 0) return false;
  if($q !== '' && stripos($p['name'].' '.$p['description'],$q) === false) return false;
  return true;
});
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Catalog - Anistore</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <div class="app-shell storefront">
    <?php include __DIR__ . '/_sidebar.php'; ?>
    <main class="main-area">
      <header class="topbar"><div><h1>Catalog<?php echo $cat ? ' — '.htmlspecialchars($cat):''; ?></h1></div><div class="top-right"><a class="icon-btn" href="cart.php"><i class="fa-solid fa-cart-shopping"></i></a></div></header>

      <section class="content container">
        <div class="card">
          <form method="get" action="products.php" class="filters">
            <input name="q" placeholder="Search products..." value="<?php echo htmlspecialchars($q); ?>">
            <select name="cat">
              <option value="">All categories</option>
              <?php foreach(['Clothes','Keychains','Stickers','Bags','Shoes'] as $c): ?>
                <option <?php if($cat===$c) echo 'selected'; ?>><?php echo $c; ?></option>
              <?php endforeach; ?>
            </select>
            <button class="btn-primary small" type="submit">Filter</button>
          </form>

          <div class="products-grid" style="margin-top:14px;">
            <?php if(!$filtered): ?><p>No products found.</p>
            <?php else: foreach($filtered as $p): ?>
              <article class="product-card">
                <img src="<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
                <h4><?php echo htmlspecialchars($p['name']); ?></h4>
                <p class="price">₹<?php echo number_format($p['price'],2); ?></p>
                <p class="cat"><?php echo htmlspecialchars($p['category']); ?></p>
                <div class="product-actions">
                  <a class="btn-ghost small" href="product.php?id=<?php echo urlencode($p['id']); ?>">View</a>
                  <form method="post" action="add_to_cart.php" style="display:inline-block">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($p['id']); ?>">
                    <input type="hidden" name="action" value="add">
                    <input type="number" name="qty" min="1" value="1" class="qty-input small">
                    <button class="btn-primary small" type="submit">Add</button>
                  </form>
                </div>
              </article>
            <?php endforeach; endif; ?>
          </div>
        </div>
      </section>

      <footer class="footer"><div class="container muted">© <?php echo date('Y'); ?> Anistore</div></footer>
    </main>
  </div>
</body>
</html>

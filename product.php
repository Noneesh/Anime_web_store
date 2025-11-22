<?php
session_start();

// require login
if (!isset($_SESSION['user'])) {
  header('Location: signin.php');
  exit;
}

$products = include __DIR__ . '/data/products.php';
$id = $_GET['id'] ?? null; $product=null;
foreach($products as $p) if($p['id']==$id){ $product=$p; break; }
if(!$product){ header("Location: products.php"); exit; }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title><?php echo htmlspecialchars($product['name']); ?> — Anistore</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <div class="app-shell storefront">
    <?php include __DIR__ . '/_sidebar.php'; ?>
    <main class="main-area">
      <header class="topbar"><div><h1><?php echo htmlspecialchars($product['name']); ?></h1></div><div class="top-right"><a class="icon-btn" href="cart.php"><i class="fa-solid fa-cart-shopping"></i></a></div></header>

      <section class="content container">
        <div class="card single-product">
          <div class="product-media"><img src="<?php echo htmlspecialchars($product['image']); ?>" alt=""></div>
          <div class="product-info">
            <h2><?php echo htmlspecialchars($product['name']); ?></h2>
            <p class="price">₹<?php echo number_format($product['price'],2); ?></p>
            <p class="cat"><?php echo htmlspecialchars($product['category']); ?></p>
            <p><?php echo htmlspecialchars($product['description']); ?></p>
            <p><strong>Stock:</strong> <?php echo intval($product['stock']); ?></p>

            <form method="post" action="add_to_cart.php">
              <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']); ?>">
              <input type="hidden" name="action" value="add">
              <label>Quantity <input type="number" name="qty" value="1" min="1" class="qty-input"></label>
              <button class="btn-primary" type="submit">Add to cart</button>
            </form>
          </div>
        </div>
      </section>

      <footer class="footer"><div class="container muted">© <?php echo date('Y'); ?> Anistore</div></footer>
    </main>
  </div>
</body>
</html>

<?php
session_start();
if (!isset($_SESSION['user'])) {
  header('Location: signin.php');
  exit;
}

$dataDir = __DIR__ . '/data';
$csvFile = $dataDir . '/orders.csv';
$orders = [];

if (file_exists($csvFile)) {
  if (($h = fopen($csvFile, 'r')) !== false) {
    $headers = fgetcsv($h);
    while (($row = fgetcsv($h)) !== false) {
      $r = array_combine($headers, $row);
      if ($r['user_email'] === ($_SESSION['user']['email'] ?? '')) {
        $r['items'] = json_decode($r['items'], true);
        $orders[] = $r;
      }
    }
    fclose($h);
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Your Orders — Anistore</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <div class="app-shell storefront">
    <?php include __DIR__ . '/_sidebar.php'; ?>
    <main class="main-area">
      <header class="topbar"><div><h1>Your Orders</h1></div></header>
      <section class="content container">
        <div class="card">
          <?php if(empty($orders)): ?>
            <p>You have no orders yet. <a href="products.php">Shop now</a></p>
          <?php else: foreach($orders as $o): ?>
            <div style="padding:12px;border-bottom:1px solid #f1f5f9;margin-bottom:8px">
              <div style="display:flex;justify-content:space-between;align-items:center">
                <div>
                  <div style="font-weight:700">Order <?php echo htmlspecialchars($o['order_id']); ?></div>
                  <div class="muted">Placed: <?php echo htmlspecialchars($o['created_at']); ?></div>
                </div>
                <div style="text-align:right">
                  <div style="font-weight:700">₹<?php echo htmlspecialchars($o['total']); ?></div>
                  <div class="muted"><?php echo htmlspecialchars($o['payment_method']); ?></div>
                </div>
              </div>

              <div style="margin-top:10px;display:flex;gap:12px;flex-wrap:wrap">
                <?php foreach($o['items'] as $it): ?>
                  <div style="display:flex;gap:8px;align-items:center;border-radius:6px;padding:8px;background:#fff;border:1px solid #f1f5f9">
                    <img src="<?php echo htmlspecialchars($it['image']); ?>" style="width:64px;height:64px;object-fit:cover;border-radius:6px">
                    <div style="min-width:180px">
                      <div style="font-weight:700"><?php echo htmlspecialchars($it['name']); ?></div>
                      <div class="muted">Qty: <?php echo intval($it['qty']); ?> × ₹<?php echo number_format($it['price'],2); ?></div>
                    </div>
                    <div style="margin-left:auto;font-weight:700">₹<?php echo number_format($it['qty']*$it['price'],2); ?></div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endforeach; endif; ?>
        </div>
      </section>
    </main>
  </div>
</body>
</html>

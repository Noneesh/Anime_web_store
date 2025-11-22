<?php
session_start();

if (!isset($_SESSION['user'])) {
  header('Location: signin.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($_POST['action'] ?? '') !== 'place_order') {
  header('Location: cart.php');
  exit;
}

// gather and validate
$name = trim($_POST['name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');
$payment = trim($_POST['payment_method'] ?? 'COD');

$errors = [];
if (!$name) $errors[] = "Name required.";
if (!$phone) $errors[] = "Phone required.";
if (!$address) $errors[] = "Address required.";

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) $errors[] = "Cart is empty.";

if ($errors) {
  // store errors in session and redirect back
  $_SESSION['checkout_errors'] = $errors;
  header('Location: checkout.php');
  exit;
}

// create order id
$order_id = 'ORD' . strtoupper(uniqid());

// compute total
$total = 0; foreach($cart as $c) $total += $c['qty'] * $c['price'];

// prepare order row
$order = [
  'order_id' => $order_id,
  'user_email' => $_SESSION['user']['email'] ?? '',
  'name' => $name,
  'phone' => $phone,
  'address' => str_replace(["\r\n", "\n"], [' ', ' '], $address),
  'payment_method' => $payment,
  'items' => json_encode(array_values($cart), JSON_UNESCAPED_UNICODE),
  'total' => number_format($total,2,'.',''),
  'created_at' => date('c')
];

// ensure data folder
$dataDir = __DIR__ . '/data';
if (!is_dir($dataDir)) mkdir($dataDir, 0755, true);

// write to CSV (create header if not exists)
$csvFile = $dataDir . '/orders.csv';
$isNew = !file_exists($csvFile);
$f = fopen($csvFile, 'a');
if ($f) {
  if ($isNew) {
    fputcsv($f, array_keys($order));
  }
  fputcsv($f, $order);
  fclose($f);

  // clear cart and redirect to success
  unset($_SESSION['cart']);
  header('Location: order_success.php?order_id=' . urlencode($order_id));
  exit;
} else {
  $_SESSION['checkout_errors'] = ['Failed to save order.'];
  header('Location: checkout.php');
  exit;
}

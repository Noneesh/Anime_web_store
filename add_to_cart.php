<?php
session_start();
function redirect_back($default='products.php'){ $back = $_SERVER['HTTP_REFERER'] ?? $default; header("Location: $back"); exit; }
$action = $_POST['action'] ?? $_GET['action'] ?? 'add';
$id = $_POST['id'] ?? $_GET['id'] ?? null;
$qty = isset($_POST['qty']) ? intval($_POST['qty']) : (isset($_GET['qty']) ? intval($_GET['qty']) : 1);
$products = include __DIR__ . '/data/products.php';
$productMap = []; foreach($products as $p) $productMap[$p['id']] = $p;
if(!$id || !isset($productMap[$id])) redirect_back();
if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
switch($action){
  case 'add':
    if(isset($_SESSION['cart'][$id])) $_SESSION['cart'][$id]['qty'] += max(1,$qty);
    else $_SESSION['cart'][$id] = ['id'=>$id,'qty'=>max(1,$qty),'price'=>$productMap[$id]['price'],'name'=>$productMap[$id]['name'],'image'=>$productMap[$id]['image']];
    break;
  case 'update':
    if(isset($_SESSION['cart'][$id])){
      if($qty <= 0) unset($_SESSION['cart'][$id]); else $_SESSION['cart'][$id]['qty'] = $qty;
    }
    break;
  case 'remove':
    if(isset($_SESSION['cart'][$id])) unset($_SESSION['cart'][$id]);
    break;
}
redirect_back();

<?php
session_start();

// require login
if (!isset($_SESSION['user'])) {
  header('Location: signin.php');
  exit;
}

$errors = [];
$success = false;
if($_SERVER['REQUEST_METHOD'] == 'POST'){
  $name = trim($_POST['name'] ?? ($_POST['from_name'] ?? 'Guest'));
  $email = trim($_POST['email'] ?? '');
  $message = trim($_POST['message'] ?? '');
  if(!$email) $errors[] = "Please enter an email.";
  if(!$message) $errors[] = "Please enter a message.";
  if(empty($errors)){
    $row = [date('c'), $name, $email, str_replace(["\r","\n"], [' ',' '], $message)];
    $dataDir = __DIR__ . '/data';
    if(!is_dir($dataDir)) mkdir($dataDir, 0755, true);
    $f = fopen($dataDir . '/contacts.csv', 'a');
    if($f){
      fputcsv($f, $row);
      fclose($f);
      $success = true;
    } else {
      $errors[] = "Failed to save message (file write error).";
    }
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Contact - Anistore</title>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="assets/style.css">
  <style>
    .contact-card { max-width: 1100px; margin: 6px auto; padding: 18px; }
    .contact-form-row { display:flex; gap:12px; align-items:center; flex-wrap:wrap; }
    .contact-form-row label{ display:flex; align-items:center; gap:8px; font-weight:600;}
    .contact-form-row input[type="text"],
    .contact-form-row input[type="email"],
    .contact-form-row textarea{ padding:8px 10px; border-radius:6px; border:1px solid #e6eef5; background:#fff; color:#111;}
    .contact-form-row textarea{ min-height:60px; width:360px;}
    .contact-actions{ display:flex; align-items:center; gap:12px; margin-left:auto;}
    @media (max-width:900px){
      .contact-form-row{flex-direction:column; align-items:stretch;}
      .contact-form-row textarea{ width:100%;}
      .contact-actions{ margin-left:0; }
    }
  </style>
</head>
<body>
  <div class="app-shell storefront">
    <?php include __DIR__ . '/_sidebar.php'; ?>

    <main class="main-area">
      <header class="topbar">
        <div><h1>Contact / Customers</h1></div>
        <div class="top-right">
          <a class="icon-btn" href="cart.php"><i class="fa-solid fa-cart-shopping"></i></a>
          <?php if($_SESSION['user'] ?? false): ?><button class="profile"><?php echo htmlspecialchars(($_SESSION['user']['name'][0] ?? 'A')); ?></button><?php endif; ?>
        </div>
      </header>

      <section class="content container">
        <div class="card contact-card">
          <?php if($success): ?><div class="notice success">Thanks — your message was received.</div><?php endif; ?>
          <?php if($errors): ?><div class="notice error"><ul><?php foreach($errors as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?></ul></div><?php endif; ?>

          <form class="contact-form" method="post" action="contact.php">
            <div class="contact-form-row">
              <label>Name <input type="text" name="name" value="<?php echo htmlspecialchars($_POST['name']??''); ?>"></label>
              <label>Email <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email']??''); ?>"></label>
              <label style="flex:1">Message <textarea name="message"><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea></label>
              <div class="contact-actions">
                <button class="btn-primary" type="submit">Send Message</button>
              </div>
            </div>
          </form>

          <section style="margin-top:18px">
            <h3>Business</h3>
            <p>Email: contact@anistore.example</p>
          </section>
        </div>
      </section>

      <footer class="footer">
        <div class="container muted">© <?php echo date('Y'); ?> Anistore</div>
      </footer>
    </main>
  </div>
</body>
</html>

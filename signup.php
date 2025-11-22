<?php
// signup.php - centered, clean signup form (replacement)
session_start();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name  = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $pass  = $_POST['password'] ?? '';
  $pass2 = $_POST['password2'] ?? '';

  if (!$name) $errors[] = "Enter your name.";
  if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Enter a valid email.";
  if (!$pass) $errors[] = "Enter a password.";
  if ($pass !== $pass2) $errors[] = "Passwords do not match.";

  if (empty($errors)) {
    $usersFile = __DIR__ . '/data/users.php';
    $users = file_exists($usersFile) ? include $usersFile : [];

    if (isset($users[$email])) {
      $errors[] = "Email already registered.";
    } else {
      $hash = password_hash($pass, PASSWORD_DEFAULT);
      $users[$email] = ['email'=>$email,'name'=>$name,'password'=>$hash];
      $export = "<?php\nreturn " . var_export($users, true) . ";\n";
      if (file_put_contents($usersFile, $export) === false) {
        $errors[] = "Failed to save user. Check file permissions.";
      } else {
        // auto-login and redirect to home
        $_SESSION['user'] = ['email'=>$email, 'name'=>$name];
        header("Location: index.php");
        exit;
      }
    }
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Sign up — Anistore</title>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="assets/style.css">

  <style>
    /* Centered minimal sign-up card (overrides/supplements your main css) */
    .signup-wrapper {
      min-height: calc(100vh - 80px);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 20px;
    }

    .signup-card {
      width: 420px;
      max-width: 94%;
      background: #fff;
      border-radius: 8px;
      padding: 34px 36px;
      box-shadow: 0 10px 30px rgba(13, 30, 60, 0.08);
      border: 1px solid rgba(6, 22, 40, 0.04);
    }

    .signup-card h2 {
      margin: 0 0 6px;
      font-size: 28px;
      font-weight: 700;
      color: #0b1726;
      text-align: center;
    }
    .signup-card p.lead {
      margin: 6px 0 18px;
      text-align: center;
      color: var(--muted);
      font-size: 14px;
    }

    .form-field { margin-bottom: 14px; }
    .form-field label { display:block; font-size:13px; color:#55616a; margin-bottom:8px; }
    .form-field input[type="text"],
    .form-field input[type="email"],
    .form-field input[type="password"] {
      width:100%; padding:12px 12px; border-radius:8px; border:1px solid #e6eef5; font-size:14px; box-sizing:border-box;
      background:#fff; color:#0b1220;
    }

    .btn-primary.full {
      width:100%; padding:12px 14px; border-radius:8px; border:0; font-weight:700; cursor:pointer;
      background: linear-gradient(90deg,var(--accent),var(--accent-2)); color:#fff; font-size:15px;
      box-shadow: 0 6px 14px rgba(31, 143, 255, 0.14);
    }

    .row {
      display:flex; gap:12px;
    }
    .row .half { flex:1; }

    .muted-center { color:var(--muted); text-align:center; margin-top:14px; font-size:13px; }

    .hr {
      height:1px; background:#f1f5f9; margin:18px 0; border-radius:2px;
    }

    .social-row { display:flex; gap:8px; }
    .social-btn {
      flex:1; padding:8px 10px; text-align:center; border-radius:8px; border:1px solid #e6eef5; cursor:pointer; font-weight:700;
      background:#fff;
    }

    .remember {
      display:flex; align-items:center; gap:8px; color:#536471; font-size:14px; margin-top:6px;
    }

    .notice.error { margin-bottom:12px; }
    .notice.success { margin-bottom:12px; }

    /* hide sidebar on small widths or when you want to mimic minimal page */
    @media (max-width:1000px){
      .sidebar { display:none !important; }
    }
  </style>
</head>
<body>
  <div class="app-shell storefront">
    <!-- keep shared sidebar (it will be hidden on small screens by CSS above) -->
    <?php include __DIR__ . '/_sidebar.php'; ?>

    <main class="main-area">
      <header class="topbar"><div style="padding:20px 28px 0"><h1 style="display:none">Sign up</h1></div></header>

      <section class="content container">
        <div class="signup-wrapper">
          <div class="signup-card">
            <h2>Sign up</h2>
            <p class="lead">Create an account to continue</p>

            <?php if($errors): ?>
              <div class="notice error">
                <ul style="margin:0;padding-left:18px;">
                  <?php foreach($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?>
                </ul>
              </div>
            <?php endif; ?>

            <form method="post" novalidate>
              <div class="form-field">
                <label for="name">Name</label>
                <input id="name" name="name" type="text" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required>
              </div>

              <div class="form-field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
              </div>

              <div class="row">
                <div class="half form-field">
                  <label for="password">Password</label>
                  <input id="password" name="password" type="password" required>
                </div>
                <div class="half form-field">
                  <label for="password2">Confirm password</label>
                  <input id="password2" name="password2" type="password" required>
                </div>
              </div>

              <button type="submit" class="btn-primary full">Sign up</button>

              <div class="muted-center">Already have an account? <a href="signin.php">Sign in</a></div>

              <div class="hr" aria-hidden="true"></div>

              <div class="remember">
                <label style="display:flex;align-items:center;gap:8px">
                  <input type="checkbox" name="remember" /> Remember me
                </label>
              </div>

              <div style="margin-top:12px;" class="social-row">
                <button type="button" class="social-btn"><i class="fa-brands fa-google"></i> Google</button>
            
              </div>
            </form>
          </div>
        </div>
      </section>

      <footer class="footer"><div class="container muted">© <?php echo date('Y'); ?> Anistore</div></footer>
    </main>
  </div>
</body>
</html>

<?php
// signin.php - centered sign-in form matching mock
session_start();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $pass  = $_POST['password'] ?? '';

  if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Enter a valid email.";
  if (!$pass) $errors[] = "Enter a password.";

  if (empty($errors)) {
    $usersFile = __DIR__ . '/data/users.php';
    $users = file_exists($usersFile) ? include $usersFile : [];
    if (!isset($users[$email])) {
      $errors[] = "No user found with that email.";
    } else {
      if (password_verify($pass, $users[$email]['password'])) {
        $_SESSION['user'] = ['email' => $email, 'name' => $users[$email]['name']];
        header("Location: index.php");
        exit;
      } else {
        $errors[] = "Invalid credentials.";
      }
    }
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Sign in — Anistore</title>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="assets/style.css">

  <style>
    /* Centered sign-in card */
    .signin-wrapper {
      min-height: calc(100vh - 80px);
      display:flex; align-items:center; justify-content:center; padding:40px 20px;
    }
    .signin-card {
      width:420px; max-width:94%; background:#fff; border-radius:8px; padding:28px 30px;
      box-shadow: 0 10px 30px rgba(13,30,60,0.08); border:1px solid rgba(6,22,40,0.04);
    }
    .signin-card h2 { margin:0 0 6px; font-size:26px; font-weight:700; color:#0b1726; text-align:left; }
    .signin-card .sub { color:var(--muted); font-size:14px; margin-bottom:18px; }
    .form-field { margin-bottom:12px; }
    .form-field label { display:block; font-size:13px; color:#55616a; margin-bottom:8px; }
    .form-field input[type="email"],
    .form-field input[type="password"] {
      width:100%; padding:12px 12px; border-radius:6px; border:1px solid #e6eef5; font-size:14px;
      background:#fff; color:#0b1220; box-sizing:border-box;
    }
    .remember { display:flex; align-items:center; gap:8px; color:#536471; font-size:14px; margin:8px 0 14px; }
    .btn-primary.full { width:100%; padding:12px 14px; border-radius:6px; border:0; font-weight:700;
       cursor:pointer; background: linear-gradient(90deg,var(--accent),var(--accent-2)); color:#fff; font-size:15px; }
    .google-btn {
      width:100%; display:flex; align-items:center; gap:10px; justify-content:center;
      padding:10px 12px; border-radius:6px; border:1px solid #e6eef5; background:#fff; cursor:pointer; margin-top:12px;
      font-weight:700;
    }
    .link-row { display:flex; justify-content:space-between; align-items:center; margin-top:12px; }
    .muted-left { color:var(--muted); font-size:13px; }
    .forgot-link { color:var(--accent); font-weight:600; text-decoration:none }
    @media (max-width:1000px){ .sidebar{ display:none !important; } }
  </style>
</head>
<body>
  <div class="app-shell storefront">
    <?php include __DIR__ . '/_sidebar.php'; ?>

    <main class="main-area">
      <header class="topbar"><div style="padding:18px 24px 0"><h1 style="display:none">Sign in</h1></div></header>

      <section class="content container">
        <div class="signin-wrapper">
          <div class="signin-card">
            <h2>Sign in</h2>
            <p class="sub">or <a href="signup.php">create an account</a></p>

            <?php if($errors): ?>
              <div class="notice error"><ul style="margin:0 0 8px 18px;"><?php foreach($errors as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?></ul></div>
            <?php endif; ?>

            <form method="post" novalidate>
              <div class="form-field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
              </div>

              <div class="form-field">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" required>
              </div>

              <div class="remember">
                <label style="display:flex;align-items:center;gap:8px;"><input type="checkbox" name="remember"> Remember me</label>
              </div>

              <button type="submit" class="btn-primary full">Sign in</button>

              <div class="link-row">
                <div class="muted-left"><a class="forgot-link" href="#">Forgotten your password?</a></div>
                <div style="font-size:13px;color:var(--muted)"> </div>
              </div>

              <button type="button" class="google-btn" onclick="alert('Google sign-in demo')">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg" style="width:18px;height:18px" alt=""> Sign in with Google
              </button>

            </form>
          </div>
        </div>
      </section>

      <footer class="footer"><div class="container muted">© <?php echo date('Y'); ?> Anistore</div></footer>
    </main>
  </div>
</body>
</html>

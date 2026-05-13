<?php
$bizName = $settings['business_name'] ?? 'Mi Negocio';
$logoPath = $settings['logo_path'] ?? '';
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($bizName) ?> · Iniciar sesión</title>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;300;400;500;600;700;800;900;1000&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="/assets/css/app.css" rel="stylesheet">
</head>
<body class="auth">
  <div class="auth-shell">
    <div class="auth-card">
      <div class="auth-brand">
        <?php if (!empty($logoPath)): ?>
          <img class="auth-logo" src="/<?= htmlspecialchars($logoPath) ?>" alt="">
        <?php else: ?>
          <div class="auth-logo auth-logo--placeholder"></div>
        <?php endif; ?>
        <div class="auth-title"><?= htmlspecialchars($bizName) ?></div>
        <div class="auth-subtitle">Acceso al sistema</div>
      </div>

      <?php if (!empty($error)): ?>
        <div class="auth-alert"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="post" class="auth-form">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
        <input type="hidden" name="next" value="<?= htmlspecialchars($next ?? '') ?>">

        <label class="auth-field">
          <span class="auth-label">Usuario</span>
          <span class="auth-inputwrap">
            <i class="fa-solid fa-user"></i>
            <input name="user" autocomplete="username" inputmode="text" class="auth-input" placeholder="Usuario" required>
          </span>
        </label>

        <label class="auth-field">
          <span class="auth-label">Contraseña</span>
          <span class="auth-inputwrap">
            <i class="fa-solid fa-lock"></i>
            <input name="pass" autocomplete="current-password" type="password" class="auth-input" placeholder="Contraseña" required>
          </span>
        </label>

        <button class="auth-btn" type="submit">Login</button>
      </form>
    </div>
  </div>
</body>
</html>

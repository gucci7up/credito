<?php
/** @var string $viewFile */
$bizName = $appSettings['business_name'] ?? 'Perfumes POS';
$logoPath = $appSettings['logo_path'] ?? '';

$route = (string)($_GET['r'] ?? 'dashboard/index');
$route = trim($route, '/');
$routeKey = strtolower($route);
$pageTitles = [
  'dashboard/index' => 'Dashboard',
  'clients/index' => 'Clientes',
  'clients/create' => 'Nuevo cliente',
  'clients/edit' => 'Editar cliente',
  'sales/index' => 'Ventas',
  'sales/create' => 'Nueva venta',
  'sales/view' => 'Detalle',
  'settings/index' => 'Configuración',
];
$pageTitle = $pageTitles[$routeKey] ?? 'Sistema';
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($bizName) ?> · <?= htmlspecialchars($pageTitle) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;300;400;500;600;700;800;900;1000&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="/assets/css/app.css" rel="stylesheet">
</head>
<body class="app">
  <div class="app-shell">
    <header class="app-header">
      <div class="app-header-left">
        <?php if (!empty($logoPath)): ?>
          <img class="app-avatar" src="/<?= htmlspecialchars($logoPath) ?>" alt="">
        <?php else: ?>
          <div class="app-avatar app-avatar--placeholder"></div>
        <?php endif; ?>
        <div class="app-header-text">
          <div class="app-bizname"><?= htmlspecialchars($bizName) ?></div>
          <div class="app-pagetitle"><?= htmlspecialchars($pageTitle) ?></div>
        </div>
      </div>

      <div class="app-header-right">
        <a class="app-iconbtn" href="/?r=settings/index" aria-label="Configuración">
          <i class="fa-solid fa-gear"></i>
        </a>
      </div>
    </header>

    <main class="app-main">
      <div class="app-container">
        <?php require $viewFile; ?>
      </div>
    </main>

    <nav class="app-nav" aria-label="Navegación">
      <a class="app-navitem" data-route="dashboard" href="/?r=dashboard/index">
        <i class="fa-solid fa-chart-pie"></i>
        <span>Inicio</span>
      </a>
      <a class="app-navitem" data-route="clients" href="/?r=clients/index">
        <i class="fa-solid fa-users"></i>
        <span>Clientes</span>
      </a>

      <a class="app-fab" href="/?r=sales/create" aria-label="Nueva venta">
        <i class="fa-solid fa-plus"></i>
      </a>

      <a class="app-navitem" data-route="sales" href="/?r=sales/index">
        <i class="fa-solid fa-receipt"></i>
        <span>Ventas</span>
      </a>
      <a class="app-navitem" data-route="settings" href="/?r=settings/index">
        <i class="fa-solid fa-sliders"></i>
        <span>Ajustes</span>
      </a>
    </nav>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="/assets/js/app.js"></script>
</body>
</html>

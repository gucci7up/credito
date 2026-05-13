<?php
/** @var string $viewFile */
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Perfumes POS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/assets/css/app.css" rel="stylesheet">
</head>
<body>
  <div class="d-flex">
    <aside class="sidebar border-end">
      <div class="p-3 border-bottom">
        <?php
          $bizName = $appSettings['business_name'] ?? 'Perfumes POS';
          $logoPath = $appSettings['logo_path'] ?? '';
        ?>
        <?php if (!empty($logoPath)): ?>
          <div class="text-center mb-2">
            <img src="/<?= htmlspecialchars($logoPath) ?>" alt="Logo" style="max-width: 120px; max-height: 60px;">
          </div>
        <?php endif; ?>
        <div class="fw-bold"><?= htmlspecialchars($bizName) ?></div>
        <div class="text-muted small">Gestión y Ventas</div>
      </div>
      <nav class="p-2">
        <a class="nav-link" href="/?r=dashboard/index">Dashboard</a>
        <a class="nav-link" href="/?r=clients/index">Clientes</a>
        <a class="nav-link" href="/?r=sales/create">Nueva Venta</a>
        <a class="nav-link" href="/?r=sales/index">Ventas</a>
        <a class="nav-link" href="/?r=settings/index">Configuración</a>
      </nav>
    </aside>

    <main class="flex-grow-1">
      <header class="p-3 border-bottom bg-white">
        <div class="d-flex align-items-center justify-content-between">
          <div class="fw-semibold">Sistema de ventas</div>
          <div class="text-muted small"><?= date('Y-m-d H:i') ?></div>
        </div>
      </header>

      <div class="p-4">
        <?php require $viewFile; ?>
      </div>
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="/assets/js/app.js"></script>
</body>
</html>

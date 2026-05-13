<?php
$currency = $appSettings['currency'] ?? 'RD$';
$todayTotal = (float)$stats['ventas_hoy'] + (float)$stats['creditos_hoy'];
$creditPct = $todayTotal > 0 ? (int)round(((float)$stats['creditos_hoy'] / $todayTotal) * 100) : 0;
$bizName = $bizName ?? ($appSettings['business_name'] ?? 'Perfumes POS');
$logoPath = $logoPath ?? ($appSettings['logo_path'] ?? '');

$activityBg = [
  'https://github.com/ecemgo/mini-samples-great-tricks/assets/13468728/c8e88356-8df5-4ac5-9e1f-5b9e99685021',
  'https://github.com/ecemgo/mini-samples-great-tricks/assets/13468728/69437d08-f203-4905-8cf5-05411cc28c19',
  'https://github.com/ecemgo/mini-samples-great-tricks/assets/13468728/7568e0ff-edb5-43dd-bff5-aed405fc32d9',
  'https://github.com/ecemgo/mini-samples-great-tricks/assets/13468728/467cf682-03fb-4fae-b129-5d4f5db304dd',
  'https://github.com/ecemgo/mini-samples-great-tricks/assets/13468728/3bab6a71-c842-4a50-9fed-b4ce650cb478',
  'https://github.com/ecemgo/mini-samples-great-tricks/assets/13468728/e1a66078-1927-4828-b793-15c403d06411',
];

$metricItems = [
  ['class' => 'img-one', 'label' => 'Ventas del día', 'value' => $currency . ' ' . number_format((float)$stats['ventas_hoy'], 2), 'img' => $activityBg[0]],
  ['class' => 'img-two', 'label' => 'Crédito (hoy)', 'value' => $currency . ' ' . number_format((float)$stats['creditos_hoy'], 2), 'img' => $activityBg[1]],
  ['class' => 'img-three', 'label' => 'Cobros del día', 'value' => $currency . ' ' . number_format((float)$stats['cobros_hoy'], 2), 'img' => $activityBg[2]],
  ['class' => 'img-four', 'label' => 'Clientes con deuda', 'value' => (string)(int)$stats['clientes_con_deuda'], 'img' => $activityBg[3]],
  ['class' => 'img-five', 'label' => 'Facturas vencidas', 'value' => (string)(int)$stats['vencidas'], 'img' => $activityBg[4]],
  ['class' => 'img-six', 'label' => 'Vencen hoy', 'value' => (string)(int)$stats['vencen_hoy'], 'img' => $activityBg[5]],
];

$colorClasses = ['activity-one', 'activity-two', 'activity-three', 'activity-four'];
?>

<main>
  <nav class="main-menu">
    <h1><?= htmlspecialchars($bizName) ?></h1>
    <?php if (!empty($logoPath)): ?>
      <img class="logo" src="/<?= htmlspecialchars($logoPath) ?>" alt="">
    <?php else: ?>
      <img class="logo" src="https://github.com/ecemgo/mini-samples-great-tricks/assets/13468728/4cfdcb5a-0137-4457-8be1-6e7bd1f29ebb" alt="">
    <?php endif; ?>
    <ul>
      <li class="nav-item active">
        <b></b>
        <b></b>
        <a href="/?r=dashboard/index">
          <i class="fa-solid fa-house nav-icon"></i>
          <span class="nav-text">Dashboard</span>
        </a>
      </li>
      <li class="nav-item">
        <b></b>
        <b></b>
        <a href="/?r=clients/index">
          <i class="fa-solid fa-user nav-icon"></i>
          <span class="nav-text">Clientes</span>
        </a>
      </li>
      <li class="nav-item">
        <b></b>
        <b></b>
        <a href="/?r=sales/create">
          <i class="fa-solid fa-circle-plus nav-icon"></i>
          <span class="nav-text">Nueva venta</span>
        </a>
      </li>
      <li class="nav-item">
        <b></b>
        <b></b>
        <a href="/?r=sales/index">
          <i class="fa-solid fa-receipt nav-icon"></i>
          <span class="nav-text">Ventas</span>
        </a>
      </li>
      <li class="nav-item">
        <b></b>
        <b></b>
        <a href="/?r=settings/index">
          <i class="fa-solid fa-sliders nav-icon"></i>
          <span class="nav-text">Config</span>
        </a>
      </li>
    </ul>
  </nav>

  <section class="content">
    <div class="left-content">
      <div class="activities">
        <h1>Resumen</h1>
        <div class="activity-container">
          <?php foreach ($metricItems as $m): ?>
            <div class="image-container <?= htmlspecialchars($m['class']) ?>">
              <img src="<?= htmlspecialchars($m['img']) ?>" alt="">
              <div class="overlay">
                <div class="metric">
                  <div class="metric-label"><?= htmlspecialchars($m['label']) ?></div>
                  <div class="metric-value"><?= htmlspecialchars($m['value']) ?></div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="left-bottom">
        <div class="weekly-schedule">
          <h1>Facturas vencidas</h1>
          <div class="calendar">
            <?php if (empty($alerts['vencidas'])): ?>
              <div class="dashv2-empty">No hay facturas vencidas.</div>
            <?php else: ?>
              <?php foreach (array_slice($alerts['vencidas'], 0, 4) as $i => $a): ?>
                <?php
                  $dueDate = (string)($a['due_date'] ?? '');
                  $dt = $dueDate !== '' ? DateTime::createFromFormat('Y-m-d', $dueDate) : false;
                  $dayNum = $dt ? $dt->format('d') : '--';
                  $dow = $dt ? strtoupper($dt->format('D')) : '';
                  $clientName = (string)($a['client_name'] ?? '-');
                  $balance = $currency . ' ' . number_format((float)($a['balance_due'] ?? 0), 2);
                  $cls = $colorClasses[$i % count($colorClasses)];
                ?>
                <div class="day-and-activity <?= $cls ?>">
                  <div class="day">
                    <h1><?= htmlspecialchars($dayNum) ?></h1>
                    <p><?= htmlspecialchars($dow) ?></p>
                  </div>
                  <div class="activity">
                    <h2><?= htmlspecialchars($clientName) ?></h2>
                    <div class="participants">
                      <span class="dashv2-balance"><?= htmlspecialchars($balance) ?></span>
                    </div>
                  </div>
                  <a class="dashv2-btn" href="/?r=sales/view&id=<?= (int)$a['id'] ?>">Ver</a>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>

        <div class="personal-bests">
          <h1>Alertas rápidas</h1>
          <div class="personal-bests-container">
            <div class="best-item box-one">
              <p>Clientes con deuda: <?= (int)$stats['clientes_con_deuda'] ?></p>
              <i class="fa-solid fa-user-clock dashv2-icon"></i>
            </div>
            <div class="best-item box-two">
              <p>Facturas vencidas: <?= (int)$stats['vencidas'] ?></p>
              <i class="fa-solid fa-triangle-exclamation dashv2-icon"></i>
            </div>
            <div class="best-item box-three">
              <p>Vencen hoy: <?= (int)$stats['vencen_hoy'] ?></p>
              <i class="fa-solid fa-calendar-day dashv2-icon"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="right-content">
      <div class="user-info">
        <div class="icon-container">
          <a class="dashv2-icon-link" href="/?r=sales/create" title="Nueva venta">
            <i class="fa-solid fa-circle-plus nav-icon"></i>
          </a>
          <a class="dashv2-icon-link" href="/?r=settings/index" title="Configuración">
            <i class="fa-solid fa-gear nav-icon"></i>
          </a>
        </div>
        <h4>Hoy</h4>
        <?php if (!empty($logoPath)): ?>
          <img src="/<?= htmlspecialchars($logoPath) ?>" alt="">
        <?php else: ?>
          <img src="https://github.com/ecemgo/mini-samples-great-tricks/assets/13468728/40b7cce2-c289-4954-9be0-938479832a9c" alt="">
        <?php endif; ?>
      </div>

      <div class="active-calories">
        <h1 style="align-self: flex-start">Crédito vs total (hoy)</h1>
        <div class="active-calories-container">
          <div class="box" style="--i: <?= (int)$creditPct ?>%">
            <div class="circle">
              <h2><?= (int)$creditPct ?><small>%</small></h2>
            </div>
          </div>
          <div class="calories-content">
            <p><span>Efectivo:</span> <?= htmlspecialchars($currency) ?> <?= number_format((float)$stats['ventas_hoy'], 2) ?></p>
            <p><span>Crédito:</span> <?= htmlspecialchars($currency) ?> <?= number_format((float)$stats['creditos_hoy'], 2) ?></p>
            <p><span>Cobros:</span> <?= htmlspecialchars($currency) ?> <?= number_format((float)$stats['cobros_hoy'], 2) ?></p>
          </div>
        </div>
      </div>

      <div class="mobile-personal-bests">
        <h1>Alertas rápidas</h1>
        <div class="personal-bests-container">
          <div class="best-item box-one">
            <p>Clientes con deuda: <?= (int)$stats['clientes_con_deuda'] ?></p>
            <i class="fa-solid fa-user-clock dashv2-icon"></i>
          </div>
          <div class="best-item box-two">
            <p>Facturas vencidas: <?= (int)$stats['vencidas'] ?></p>
            <i class="fa-solid fa-triangle-exclamation dashv2-icon"></i>
          </div>
          <div class="best-item box-three">
            <p>Vencen hoy: <?= (int)$stats['vencen_hoy'] ?></p>
            <i class="fa-solid fa-calendar-day dashv2-icon"></i>
          </div>
        </div>
      </div>

      <div class="friends-activity">
        <h1>Vencimientos</h1>
        <div class="dashv2-card-container">
          <div class="dashv2-card">
            <div class="dashv2-card-user-info">
              <i class="fa-solid fa-circle-xmark dashv2-card-icon"></i>
              <h2>Vencidas</h2>
            </div>
            <?php if (empty($alerts['vencidas'])): ?>
              <p>No hay facturas vencidas.</p>
            <?php else: ?>
              <?php $a = $alerts['vencidas'][0]; ?>
              <p><strong>#<?= (int)$a['id'] ?></strong> <?= htmlspecialchars((string)($a['client_name'] ?? '-')) ?></p>
              <p>Vence: <?= htmlspecialchars((string)($a['due_date'] ?? '')) ?> · Balance: <?= htmlspecialchars($currency) ?> <?= number_format((float)($a['balance_due'] ?? 0), 2) ?></p>
              <p><a class="dashv2-link" href="/?r=sales/view&id=<?= (int)$a['id'] ?>">Ver factura</a></p>
            <?php endif; ?>
          </div>

          <div class="dashv2-card dashv2-card-two">
            <div class="dashv2-card-user-info">
              <i class="fa-solid fa-circle-check dashv2-card-icon"></i>
              <h2>Vencen hoy</h2>
            </div>
            <?php if (empty($alerts['vencen_hoy'])): ?>
              <p>No hay facturas que venzan hoy.</p>
            <?php else: ?>
              <?php $a = $alerts['vencen_hoy'][0]; ?>
              <p><strong>#<?= (int)$a['id'] ?></strong> <?= htmlspecialchars((string)($a['client_name'] ?? '-')) ?></p>
              <p>Vence: <?= htmlspecialchars((string)($a['due_date'] ?? '')) ?> · Balance: <?= htmlspecialchars($currency) ?> <?= number_format((float)($a['balance_due'] ?? 0), 2) ?></p>
              <p><a class="dashv2-link" href="/?r=sales/view&id=<?= (int)$a['id'] ?>">Ver factura</a></p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

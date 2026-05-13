<?php
$currency = $appSettings['currency'] ?? 'RD$';
$ventasHoy = (float)($stats['ventas_hoy'] ?? 0);
$creditosHoy = (float)($stats['creditos_hoy'] ?? 0);
$cobrosHoy = (float)($stats['cobros_hoy'] ?? 0);
$ventasMes = (float)($stats['ventas_mes'] ?? 0);
$totalHoy = $ventasHoy + $creditosHoy;
?>

<div class="app-toolbar">
  <h4>Resumen</h4>
  <a class="app-btn app-btn--primary" href="/?r=sales/create">
    <i class="fa-solid fa-plus"></i>
    Venta
  </a>
</div>

<div class="app-card">
  <div class="app-card-header">
    <div class="app-card-title">Total de hoy</div>
    <span class="app-badge"><?= date('d M') ?></span>
  </div>
  <div class="app-card-body">
    <div class="app-money"><?= htmlspecialchars($currency) ?> <?= number_format($totalHoy, 2) ?></div>
    <div class="app-subtext">Ventas del mes: <?= htmlspecialchars($currency) ?> <?= number_format($ventasMes, 2) ?></div>
  </div>
</div>

<div class="app-card">
  <div class="app-card-header">
    <div class="app-card-title">Accesos rápidos</div>
  </div>
  <div class="app-card-body">
    <div class="app-chiprow">
      <div class="app-mini">
        <div class="app-mini-title">Efectivo</div>
        <div class="app-mini-value"><?= htmlspecialchars($currency) ?> <?= number_format($ventasHoy, 2) ?></div>
        <div class="app-mini-accent"><i class="fa-solid fa-sack-dollar"></i></div>
      </div>
      <div class="app-mini">
        <div class="app-mini-title">Crédito</div>
        <div class="app-mini-value"><?= htmlspecialchars($currency) ?> <?= number_format($creditosHoy, 2) ?></div>
        <div class="app-mini-accent"><i class="fa-solid fa-credit-card"></i></div>
      </div>
      <div class="app-mini">
        <div class="app-mini-title">Cobros</div>
        <div class="app-mini-value"><?= htmlspecialchars($currency) ?> <?= number_format($cobrosHoy, 2) ?></div>
        <div class="app-mini-accent"><i class="fa-solid fa-hand-holding-dollar"></i></div>
      </div>
      <div class="app-mini">
        <div class="app-mini-title">Vencidas</div>
        <div class="app-mini-value"><?= (int)($stats['vencidas'] ?? 0) ?></div>
        <div class="app-mini-accent"><i class="fa-solid fa-triangle-exclamation"></i></div>
      </div>
      <div class="app-mini">
        <div class="app-mini-title">Vencen hoy</div>
        <div class="app-mini-value"><?= (int)($stats['vencen_hoy'] ?? 0) ?></div>
        <div class="app-mini-accent"><i class="fa-solid fa-calendar-day"></i></div>
      </div>
    </div>
  </div>
</div>

<div class="app-card">
  <div class="app-card-header">
    <div class="app-card-title">Últimas ventas</div>
    <a class="app-btn" href="/?r=sales/index">Ver todas</a>
  </div>
  <div class="app-card-body">
    <?php if (empty($recent_sales)): ?>
      <div class="app-subtext">No hay ventas registradas.</div>
    <?php else: ?>
      <div class="app-list">
        <?php foreach ($recent_sales as $s): ?>
          <?php
            $clientName = (string)($s['client_name'] ?? '-');
            $type = ($s['sale_type'] ?? '') === 'credit' ? 'Crédito' : 'Diaria';
            $status = ($s['status'] ?? '') === 'PENDING' ? 'PENDIENTE' : 'PAGADO';
            $isPending = ($s['status'] ?? '') === 'PENDING';
          ?>
          <a class="app-listitem" href="/?r=sales/view&id=<?= (int)$s['id'] ?>">
            <div class="app-listleft">
              <div class="app-listtitle">#<?= (int)$s['id'] ?> · <?= htmlspecialchars($clientName) ?></div>
              <div class="app-listmeta"><?= htmlspecialchars((string)($s['sale_date'] ?? '')) ?> · <?= htmlspecialchars($type) ?></div>
            </div>
            <div class="app-listright">
              <div class="app-listamount"><?= htmlspecialchars($currency) ?> <?= number_format((float)($s['total'] ?? 0), 2) ?></div>
              <div class="app-listmeta">
                <span class="app-badge<?= $isPending ? ' app-badge--danger' : '' ?>"><?= htmlspecialchars($status) ?></span>
              </div>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<div class="app-card">
  <div class="app-card-header">
    <div class="app-card-title">Últimos cobros</div>
  </div>
  <div class="app-card-body">
    <?php if (empty($recent_payments)): ?>
      <div class="app-subtext">Sin cobros registrados.</div>
    <?php else: ?>
      <div class="app-list">
        <?php foreach ($recent_payments as $p): ?>
          <div class="app-listitem">
            <div class="app-listleft">
              <div class="app-listtitle">Venta #<?= (int)($p['sale_id'] ?? 0) ?> · <?= htmlspecialchars((string)($p['client_name'] ?? '-')) ?></div>
              <div class="app-listmeta"><?= htmlspecialchars((string)($p['paid_date'] ?? '')) ?></div>
            </div>
            <div class="app-listright">
              <div class="app-listamount"><?= htmlspecialchars($currency) ?> <?= number_format((float)($p['amount'] ?? 0), 2) ?></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

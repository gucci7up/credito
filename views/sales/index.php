<?php $currency = $appSettings['currency'] ?? 'RD$'; ?>

<div class="app-toolbar">
  <h4>Ventas</h4>
  <a class="app-btn app-btn--primary" href="/?r=sales/create">
    <i class="fa-solid fa-plus"></i>
    Nueva
  </a>
</div>

<div class="app-card">
  <div class="app-card-body app-form">
    <form method="get" class="d-flex gap-2">
      <input type="hidden" name="r" value="sales/index">
      <input class="form-control" name="q" placeholder="Buscar..." value="<?= htmlspecialchars($q) ?>">
      <button class="btn btn-outline-secondary" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
    </form>
  </div>
</div>

<div class="app-card">
  <div class="app-card-header">
    <div class="app-card-title">Listado</div>
    <span class="app-badge"><?= count($sales) ?></span>
  </div>
  <div class="app-card-body">
    <?php if (empty($sales)): ?>
      <div class="app-subtext">No hay ventas.</div>
    <?php else: ?>
      <div class="app-list">
        <?php foreach ($sales as $s): ?>
          <?php
            $clientName = (string)($s['client_name'] ?? '-');
            $type = ($s['sale_type'] ?? '') === 'credit' ? 'Crédito' : 'Diaria';
            $status = ($s['status'] ?? '') === 'PENDING' ? 'PENDIENTE' : 'PAGADO';
            $isPending = ($s['status'] ?? '') === 'PENDING';
          ?>
          <div class="app-listitem">
            <a class="app-listleft" href="/?r=sales/view&id=<?= (int)$s['id'] ?>" style="text-decoration:none;color:inherit;">
              <div class="app-listtitle">#<?= (int)$s['id'] ?> · <?= htmlspecialchars($clientName) ?></div>
              <div class="app-listmeta"><?= htmlspecialchars((string)($s['sale_date'] ?? '')) ?> · <?= htmlspecialchars($type) ?></div>
            </a>
            <div class="app-listright">
              <div class="app-listamount"><?= htmlspecialchars($currency) ?> <?= number_format((float)($s['total'] ?? 0), 2) ?></div>
              <div class="app-listmeta">
                <span class="app-badge<?= $isPending ? ' app-badge--danger' : '' ?>"><?= htmlspecialchars($status) ?></span>
              </div>
              <div class="d-flex gap-2 justify-content-end mt-2">
                <a class="app-btn" href="/?r=sales/view&id=<?= (int)$s['id'] ?>"><i class="fa-solid fa-eye"></i></a>
                <a class="app-btn" href="/?r=invoices/ticket&id=<?= (int)$s['id'] ?>"><i class="fa-solid fa-print"></i></a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>


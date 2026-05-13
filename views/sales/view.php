<?php
  $isCredit = ($sale['sale_type'] ?? '') === 'credit';
  $currency = $appSettings['currency'] ?? 'RD$';
?>

<div class="app-toolbar">
  <div>
    <h4>Venta #<?= (int)$sale['id'] ?></h4>
    <div class="app-subtext"><?= htmlspecialchars($sale['sale_date']) ?> · <?= $isCredit ? 'Crédito' : 'Diaria' ?> · <?= htmlspecialchars($sale['client_name'] ?? '-') ?></div>
  </div>
  <div class="d-flex gap-2">
    <a class="app-btn" href="/?r=sales/index"><i class="fa-solid fa-arrow-left"></i></a>
    <a class="app-btn app-btn--primary" href="/?r=invoices/ticket&id=<?= (int)$sale['id'] ?>"><i class="fa-solid fa-print"></i></a>
  </div>
</div>

<div class="app-card">
  <div class="app-card-header">
    <div class="app-card-title">Productos</div>
  </div>
  <div class="app-card-body">
        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <thead>
              <tr>
                <th>Producto</th>
                <th class="text-end">Cant.</th>
                <th class="text-end">P. Unit</th>
                <th class="text-end">Total</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($items as $it): ?>
                <tr>
                  <td><?= htmlspecialchars($it['product_name']) ?></td>
                  <td class="text-end"><?= number_format((float)$it['qty'], 2) ?></td>
                  <td class="text-end"><?= number_format((float)$it['unit_price'], 2) ?></td>
                  <td class="text-end"><?= number_format((float)$it['line_total'], 2) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <div class="row mt-2">
          <div class="col-md-6 ms-auto">
            <div class="d-flex justify-content-between"><span>Subtotal</span><span><?= htmlspecialchars($currency) ?> <?= number_format((float)$sale['subtotal'], 2) ?></span></div>
            <?php if ((int)$sale['itbis_enabled'] === 1): ?>
              <div class="d-flex justify-content-between"><span>ITBIS (<?= number_format((float)$sale['itbis_percent'], 2) ?>%)</span><span><?= htmlspecialchars($currency) ?> <?= number_format((float)$sale['itbis_amount'], 2) ?></span></div>
            <?php endif; ?>
            <div class="d-flex justify-content-between"><span>Descuento</span><span><?= htmlspecialchars($currency) ?> <?= number_format((float)$sale['discount_amount'], 2) ?></span></div>
            <div class="d-flex justify-content-between"><span>Delivery</span><span><?= htmlspecialchars($currency) ?> <?= number_format((float)$sale['delivery_amount'], 2) ?></span></div>
            <hr class="my-2">
            <div class="d-flex justify-content-between fw-semibold"><span>Total</span><span><?= htmlspecialchars($currency) ?> <?= number_format((float)$sale['total'], 2) ?></span></div>
          </div>
        </div>
  </div>
</div>

<div class="app-card" style="margin-top:12px;">
  <div class="app-card-header">
    <div class="app-card-title">Estado</div>
  </div>
  <div class="app-card-body">
    <?php if ($sale['status'] === 'PENDING'): ?>
      <div class="alert alert-warning mb-2"><strong>PENDIENTE</strong> · Balance: <?= htmlspecialchars($currency) ?> <?= number_format((float)$sale['balance_due'], 2) ?></div>
    <?php else: ?>
      <div class="alert alert-success mb-2"><strong>PAGADO</strong></div>
    <?php endif; ?>

    <?php if ($isCredit): ?>
      <div class="small text-muted">
        Fecha de pago (vencimiento): <strong><?= htmlspecialchars($sale['due_date'] ?? '-') ?></strong><br>
        Inicial: <strong><?= htmlspecialchars($currency) ?> <?= number_format((float)($sale['initial_payment'] ?? 0), 2) ?></strong>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php if ($isCredit): ?>
  <div class="app-card" style="margin-top:12px;">
    <div class="app-card-header">
      <div class="app-card-title">Cobros</div>
    </div>
    <div class="app-card-body app-form">
      <?php if ($sale['status'] === 'PENDING'): ?>
        <form method="post" action="/?r=sales/addpayment" class="mb-3">
          <input type="hidden" name="sale_id" value="<?= (int)$sale['id'] ?>">
          <label class="form-label">Monto a cobrar</label>
          <input name="amount" class="form-control" inputmode="decimal" required>
          <label class="form-label mt-2">Nota (opcional)</label>
          <input name="note" class="form-control">
          <button class="app-btn app-btn--primary app-btn--block mt-3" type="submit"><i class="fa-solid fa-check"></i> Registrar cobro</button>
        </form>
      <?php endif; ?>

      <?php if (empty($payments)): ?>
        <div class="text-muted small">Sin cobros registrados.</div>
      <?php else: ?>
        <ul class="list-group list-group-flush">
          <?php foreach ($payments as $p): ?>
            <li class="list-group-item px-0">
              <div class="d-flex justify-content-between">
                <span><?= htmlspecialchars($p['paid_date']) ?></span>
                <strong><?= htmlspecialchars($currency) ?> <?= number_format((float)$p['amount'], 2) ?></strong>
              </div>
              <?php if (!empty($p['note'])): ?>
                <div class="text-muted small"><?= htmlspecialchars($p['note']) ?></div>
              <?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  </div>
<?php endif; ?>


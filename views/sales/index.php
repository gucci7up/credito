<div class="d-flex align-items-center justify-content-between mb-3">
  <h4 class="mb-0">Ventas</h4>
  <a class="btn btn-primary" href="/?r=sales/create">Nueva venta</a>
</div>

<div class="card">
  <div class="card-body">
    <form class="row g-2 mb-3" method="get">
      <input type="hidden" name="r" value="sales/index">
      <div class="col-md-10">
        <input class="form-control" name="q" placeholder="Buscar por cliente o # factura..." value="<?= htmlspecialchars($q) ?>">
      </div>
      <div class="col-md-2 d-grid">
        <button class="btn btn-outline-secondary">Buscar</button>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-sm align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Fecha</th>
            <th>Tipo</th>
            <th>Cliente</th>
            <th>Total</th>
            <th>Estado</th>
            <th class="text-end">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($sales)): ?>
            <tr><td colspan="7" class="text-center text-muted py-4">No hay ventas.</td></tr>
          <?php endif; ?>
          <?php foreach ($sales as $s): ?>
            <tr>
              <td class="fw-semibold"><?= (int)$s['id'] ?></td>
              <td><?= htmlspecialchars($s['sale_date']) ?></td>
              <td>
                <?php if ($s['sale_type'] === 'credit'): ?>
                  <span class="badge text-bg-warning">Crédito</span>
                <?php else: ?>
                  <span class="badge text-bg-success">Diaria</span>
                <?php endif; ?>
              </td>
              <td><?= htmlspecialchars($s['client_name'] ?? '-') ?></td>
              <td><?= number_format((float)$s['total'], 2) ?></td>
              <td>
                <?php if ($s['status'] === 'PENDING'): ?>
                  <span class="badge text-bg-warning">PENDIENTE</span>
                <?php else: ?>
                  <span class="badge text-bg-success">PAGADO</span>
                <?php endif; ?>
              </td>
              <td class="text-end">
                <a class="btn btn-sm btn-outline-secondary" href="/?r=sales/view&id=<?= (int)$s['id'] ?>">Detalle</a>
                <a class="btn btn-sm btn-outline-primary" href="/?r=invoices/ticket&id=<?= (int)$s['id'] ?>">Factura</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>


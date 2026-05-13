<div class="d-flex align-items-center justify-content-between mb-3">
  <h4 class="mb-0">Dashboard</h4>
  <a class="btn btn-primary" href="/?r=sales/create">Nueva venta</a>
</div>

<div class="row g-3">
  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <div class="text-muted small">Ventas del día</div>
        <div class="fs-4 fw-semibold"><?= number_format($stats['ventas_hoy'], 2) ?></div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <div class="text-muted small">Ventas a crédito (hoy)</div>
        <div class="fs-4 fw-semibold"><?= number_format($stats['creditos_hoy'], 2) ?></div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <div class="text-muted small">Cobros del día</div>
        <div class="fs-4 fw-semibold"><?= number_format($stats['cobros_hoy'], 2) ?></div>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card">
      <div class="card-body">
        <div class="text-muted small">Clientes con deuda</div>
        <div class="fs-4 fw-semibold"><?= (int)$stats['clientes_con_deuda'] ?></div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card border-danger">
      <div class="card-body">
        <div class="text-danger small">Facturas vencidas</div>
        <div class="fs-4 fw-semibold text-danger"><?= (int)$stats['vencidas'] ?></div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card border-warning">
      <div class="card-body">
        <div class="text-warning small">Vencen hoy</div>
        <div class="fs-4 fw-semibold text-warning"><?= (int)$stats['vencen_hoy'] ?></div>
      </div>
    </div>
  </div>
</div>

<div class="row g-3 mt-1">
  <div class="col-md-6">
    <div class="card border-danger">
      <div class="card-header bg-white fw-semibold text-danger">Vencidas</div>
      <div class="card-body">
        <?php if (empty($alerts['vencidas'])): ?>
          <div class="text-muted small">No hay facturas vencidas.</div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Cliente</th>
                  <th>Vence</th>
                  <th class="text-end">Balance</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($alerts['vencidas'] as $a): ?>
                  <tr>
                    <td><?= (int)$a['id'] ?></td>
                    <td><?= htmlspecialchars($a['client_name'] ?? '-') ?></td>
                    <td><span class="text-danger fw-semibold"><?= htmlspecialchars($a['due_date']) ?></span></td>
                    <td class="text-end text-danger fw-semibold"><?= number_format((float)$a['balance_due'], 2) ?></td>
                    <td class="text-end"><a class="btn btn-sm btn-outline-secondary" href="/?r=sales/view&id=<?= (int)$a['id'] ?>">Ver</a></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card border-warning">
      <div class="card-header bg-white fw-semibold text-warning">Vencen hoy</div>
      <div class="card-body">
        <?php if (empty($alerts['vencen_hoy'])): ?>
          <div class="text-muted small">No hay facturas que venzan hoy.</div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Cliente</th>
                  <th>Vence</th>
                  <th class="text-end">Balance</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($alerts['vencen_hoy'] as $a): ?>
                  <tr>
                    <td><?= (int)$a['id'] ?></td>
                    <td><?= htmlspecialchars($a['client_name'] ?? '-') ?></td>
                    <td><span class="text-warning fw-semibold"><?= htmlspecialchars($a['due_date']) ?></span></td>
                    <td class="text-end text-warning fw-semibold"><?= number_format((float)$a['balance_due'], 2) ?></td>
                    <td class="text-end"><a class="btn btn-sm btn-outline-secondary" href="/?r=sales/view&id=<?= (int)$a['id'] ?>">Ver</a></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

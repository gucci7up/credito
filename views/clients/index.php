<div class="d-flex align-items-center justify-content-between mb-3">
  <h4 class="mb-0">Clientes</h4>
  <a class="btn btn-primary" href="/?r=clients/create">Nuevo cliente</a>
</div>

<div class="card">
  <div class="card-body">
    <form class="row g-2 mb-3" method="get">
      <input type="hidden" name="r" value="clients/index">
      <div class="col-md-10">
        <input class="form-control" name="q" placeholder="Buscar por nombre, teléfono, dirección o email..." value="<?= htmlspecialchars($q) ?>">
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
            <th>Nombre</th>
            <th>Teléfono</th>
            <th>Dirección</th>
            <th>Email</th>
            <th class="text-end">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($clients)): ?>
            <tr><td colspan="6" class="text-center text-muted py-4">No hay clientes.</td></tr>
          <?php endif; ?>

          <?php foreach ($clients as $c): ?>
            <tr>
              <td><?= (int)$c['id'] ?></td>
              <td class="fw-semibold"><?= htmlspecialchars($c['name']) ?></td>
              <td><?= htmlspecialchars($c['phone']) ?></td>
              <td><?= htmlspecialchars($c['address']) ?></td>
              <td><?= htmlspecialchars($c['email'] ?? '') ?></td>
              <td class="text-end">
                <a class="btn btn-sm btn-outline-primary" href="/?r=clients/edit&id=<?= (int)$c['id'] ?>">Editar</a>
                <form method="post" action="/?r=clients/delete" class="d-inline" onsubmit="return confirm('¿Eliminar este cliente?');">
                  <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
                  <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>


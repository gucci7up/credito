<div class="app-toolbar">
  <h4>Clientes</h4>
  <a class="app-btn app-btn--primary" href="/?r=clients/create">
    <i class="fa-solid fa-user-plus"></i>
    Nuevo
  </a>
</div>

<div class="app-card">
  <div class="app-card-body app-form">
    <form method="get" class="d-flex gap-2">
      <input type="hidden" name="r" value="clients/index">
      <input class="form-control" name="q" placeholder="Buscar..." value="<?= htmlspecialchars($q) ?>">
      <button class="btn btn-outline-secondary" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
    </form>
  </div>
</div>

<div class="app-card">
  <div class="app-card-header">
    <div class="app-card-title">Listado</div>
    <span class="app-badge"><?= count($clients) ?></span>
  </div>
  <div class="app-card-body">
    <?php if (empty($clients)): ?>
      <div class="app-subtext">No hay clientes.</div>
    <?php else: ?>
      <div class="app-list">
        <?php foreach ($clients as $c): ?>
          <div class="app-listitem">
            <div class="app-listleft">
              <div class="app-listtitle"><?= htmlspecialchars($c['name']) ?></div>
              <div class="app-listmeta"><?= htmlspecialchars($c['phone']) ?> · <?= htmlspecialchars($c['address']) ?></div>
            </div>
            <div class="app-listright d-flex gap-2">
              <a class="app-btn" href="/?r=clients/edit&id=<?= (int)$c['id'] ?>"><i class="fa-solid fa-pen"></i></a>
              <form method="post" action="/?r=clients/delete" class="m-0" onsubmit="return confirm('¿Eliminar este cliente?');">
                <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
                <button class="app-btn app-btn--danger" type="submit"><i class="fa-solid fa-trash"></i></button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>


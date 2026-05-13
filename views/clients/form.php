<?php
  $isEdit = ($mode ?? 'create') === 'edit';
  $title = $isEdit ? 'Editar cliente' : 'Nuevo cliente';
  $action = $isEdit ? '/?r=clients/edit&id=' . (int)$id : '/?r=clients/create';
?>

<div class="app-toolbar">
  <h4><?= htmlspecialchars($title) ?></h4>
  <a class="app-btn" href="/?r=clients/index"><i class="fa-solid fa-arrow-left"></i> Volver</a>
</div>

<?php if (!empty($errors)): ?>
  <div class="alert alert-danger">
    <ul class="mb-0">
      <?php foreach ($errors as $e): ?>
        <li><?= htmlspecialchars($e) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<div class="app-card">
  <div class="app-card-body app-form">
    <form method="post" action="<?= htmlspecialchars($action) ?>">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Nombre</label>
          <input name="name" class="form-control" required value="<?= htmlspecialchars($data['name'] ?? '') ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label">Teléfono</label>
          <input name="phone" class="form-control" required value="<?= htmlspecialchars($data['phone'] ?? '') ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label">Email (opcional)</label>
          <input name="email" class="form-control" value="<?= htmlspecialchars($data['email'] ?? '') ?>">
        </div>
        <div class="col-12">
          <label class="form-label">Dirección</label>
          <input name="address" class="form-control" required value="<?= htmlspecialchars($data['address'] ?? '') ?>">
        </div>
      </div>

      <div class="mt-3 d-grid gap-2">
        <button class="app-btn app-btn--primary app-btn--block" type="submit"><i class="fa-solid fa-check"></i> Guardar</button>
        <a class="app-btn app-btn--block" href="/?r=clients/index">Cancelar</a>
      </div>
    </form>
  </div>
</div>


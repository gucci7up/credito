<?php
  $isEdit = ($mode ?? 'create') === 'edit';
  $title = $isEdit ? 'Editar cliente' : 'Nuevo cliente';
  $action = $isEdit ? '/?r=clients/edit&id=' . (int)$id : '/?r=clients/create';
?>

<div class="d-flex align-items-center justify-content-between mb-3">
  <h4 class="mb-0"><?= htmlspecialchars($title) ?></h4>
  <a class="btn btn-outline-secondary" href="/?r=clients/index">Volver</a>
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

<div class="card">
  <div class="card-body">
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

      <div class="mt-3 d-flex justify-content-end gap-2">
        <a class="btn btn-outline-secondary" href="/?r=clients/index">Cancelar</a>
        <button class="btn btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>


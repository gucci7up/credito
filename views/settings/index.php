<div class="d-flex align-items-center justify-content-between mb-3">
  <h4 class="mb-0">Configuración</h4>
</div>

<?php if (!empty($success)): ?>
  <div class="alert alert-success">Configuración guardada correctamente.</div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
  <div class="alert alert-danger">
    <div class="fw-semibold mb-1">Revisa lo siguiente:</div>
    <ul class="mb-0">
      <?php foreach ($errors as $e): ?>
        <li><?= htmlspecialchars($e) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<?php
  $businessName = $settings['business_name'] ?? '';
  $currency = $settings['currency'] ?? 'RD$';
  $itbisEnabled = ($settings['itbis_enabled'] ?? '0') === '1';
  $itbisPercent = $settings['itbis_percent'] ?? '18';
  $defaultDiscount = $settings['default_discount'] ?? '0';
  $footerText = $settings['footer_text'] ?? '';
  $logoPath = $settings['logo_path'] ?? '';
?>

<div class="card">
  <div class="card-body">
    <form method="post" enctype="multipart/form-data">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Nombre del negocio</label>
          <input name="business_name" class="form-control" required value="<?= htmlspecialchars($businessName) ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label">Moneda</label>
          <input name="currency" class="form-control" required value="<?= htmlspecialchars($currency) ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label">Logo (opcional)</label>
          <input type="file" name="logo" class="form-control" accept="image/*">
          <?php if (!empty($logoPath)): ?>
            <div class="mt-2">
              <img src="/<?= htmlspecialchars($logoPath) ?>" alt="Logo" style="max-width: 160px; max-height: 80px;">
            </div>
          <?php endif; ?>
        </div>

        <div class="col-12">
          <hr>
          <div class="fw-semibold mb-2">Configuración financiera</div>
        </div>

        <div class="col-md-3">
          <label class="form-label">Activar ITBIS</label>
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" name="itbis_enabled" id="itbis_enabled" <?= $itbisEnabled ? 'checked' : '' ?>>
            <label class="form-check-label" for="itbis_enabled"><?= $itbisEnabled ? 'ON' : 'OFF' ?></label>
          </div>
        </div>
        <div class="col-md-3">
          <label class="form-label">Porcentaje ITBIS</label>
          <input name="itbis_percent" class="form-control" inputmode="decimal" value="<?= htmlspecialchars($itbisPercent) ?>">
          <div class="form-text">Ejemplo: 18</div>
        </div>
        <div class="col-md-3">
          <label class="form-label">Descuento por defecto</label>
          <input name="default_discount" class="form-control" inputmode="decimal" value="<?= htmlspecialchars($defaultDiscount) ?>">
        </div>

        <div class="col-12">
          <label class="form-label">Texto pie de factura</label>
          <textarea name="footer_text" class="form-control" rows="2"><?= htmlspecialchars($footerText) ?></textarea>
        </div>
      </div>

      <div class="mt-3 d-flex justify-content-end">
        <button class="btn btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>


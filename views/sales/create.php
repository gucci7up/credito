<div class="app-toolbar">
  <h4>Nueva venta</h4>
  <a class="app-btn" href="/?r=sales/index"><i class="fa-solid fa-list"></i> Ver</a>
</div>

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

<form method="post" id="saleForm" class="app-form">
  <div class="row g-3">
    <div class="col-md-3">
      <label class="form-label">Tipo de venta</label>
      <select class="form-select" name="sale_type" id="sale_type">
        <option value="cash" <?= ($form['sale_type'] ?? 'cash') === 'cash' ? 'selected' : '' ?>>Venta diaria (pago inmediato)</option>
        <option value="credit" <?= ($form['sale_type'] ?? 'cash') === 'credit' ? 'selected' : '' ?>>Venta a crédito</option>
      </select>
    </div>

    <div class="col-md-5">
      <label class="form-label">Cliente (obligatorio para crédito)</label>
      <select class="form-select" name="client_id" id="client_id">
        <option value="">-- Seleccionar --</option>
        <?php foreach ($clients as $c): ?>
          <option value="<?= (int)$c['id'] ?>" <?= ((string)($form['client_id'] ?? '') === (string)$c['id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($c['name']) ?> (<?= htmlspecialchars($c['phone']) ?>)
          </option>
        <?php endforeach; ?>
      </select>
      <div class="form-text">
        ¿No está? <a href="/?r=clients/create">Crear cliente</a>
      </div>
    </div>

    <div class="col-md-2 credit-only">
      <label class="form-label">Fecha de pago</label>
      <input type="date" class="form-control" name="due_date" id="due_date" value="<?= htmlspecialchars($form['due_date'] ?? '') ?>">
    </div>
    <div class="col-md-2 credit-only">
      <label class="form-label">Inicial (opcional)</label>
      <input class="form-control" name="initial_payment" inputmode="decimal" value="<?= htmlspecialchars($form['initial_payment'] ?? '') ?>">
    </div>
  </div>

  <div class="app-card" style="margin-top:12px;">
    <div class="app-card-header">
      <div>Productos (se ingresan manualmente)</div>
      <button type="button" class="app-btn" id="addRow"><i class="fa-solid fa-plus"></i> Agregar</button>
    </div>
    <div class="app-card-body">
      <div class="table-responsive">
        <table class="table table-sm align-middle" id="itemsTable">
          <thead>
            <tr>
              <th style="min-width:260px">Producto</th>
              <th style="width:120px">Cantidad</th>
              <th style="width:160px">Precio unitario</th>
              <th style="width:160px">Total</th>
              <th style="width:60px"></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach (($form['items'] ?? []) as $it): ?>
              <tr>
                <td><input name="product_name[]" class="form-control form-control-sm" required value="<?= htmlspecialchars($it['product_name'] ?? '') ?>"></td>
                <td><input name="qty[]" class="form-control form-control-sm qty" inputmode="decimal" value="<?= htmlspecialchars($it['qty'] ?? '1') ?>"></td>
                <td><input name="unit_price[]" class="form-control form-control-sm price" inputmode="decimal" value="<?= htmlspecialchars($it['unit_price'] ?? '0') ?>"></td>
                <td class="text-end"><span class="line-total">0.00</span></td>
                <td class="text-end">
                  <button type="button" class="btn btn-sm btn-outline-danger removeRow">X</button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <div class="row g-3 mt-2">
        <div class="col-md-3">
          <label class="form-label">Descuento</label>
          <input class="form-control" name="discount_amount" id="discount_amount" inputmode="decimal" value="<?= htmlspecialchars($form['discount_amount'] ?? '0') ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label">Delivery (opcional)</label>
          <input class="form-control" name="delivery_amount" id="delivery_amount" inputmode="decimal" value="<?= htmlspecialchars($form['delivery_amount'] ?? '0') ?>">
        </div>

        <div class="col-md-6">
          <div class="app-card">
            <div class="app-card-body" style="padding:12px;">
              <div class="d-flex justify-content-between"><span>Subtotal</span><span id="subtotal">0.00</span></div>
              <div class="d-flex justify-content-between <?= $itbisEnabledGlobal ? '' : 'd-none' ?>" id="itbisRow">
                <span>ITBIS (<?= number_format((float)$itbisPercentGlobal, 2) ?>%)</span>
                <span id="itbis">0.00</span>
              </div>
              <div class="d-flex justify-content-between"><span>Descuento</span><span id="discount">0.00</span></div>
              <div class="d-flex justify-content-between"><span>Delivery</span><span id="delivery">0.00</span></div>
              <hr class="my-2">
              <div class="d-flex justify-content-between fw-semibold"><span>Total final</span><span id="total">0.00</span></div>
            </div>
          </div>
          <div class="form-text">
            ITBIS global: <strong><?= $itbisEnabledGlobal ? 'ACTIVADO' : 'DESACTIVADO' ?></strong>
          </div>
        </div>
      </div>

      <div class="mt-3 d-grid gap-2">
        <button class="app-btn app-btn--primary app-btn--block" type="submit"><i class="fa-solid fa-check"></i> Guardar venta</button>
        <a class="app-btn app-btn--block" href="/?r=sales/index">Cancelar</a>
      </div>
    </div>
  </div>
</form>

<script>
  (function(){
    const itbisEnabled = <?= $itbisEnabledGlobal ? 'true' : 'false' ?>;
    const itbisPercent = <?= json_encode((float)$itbisPercentGlobal) ?>;

    const saleType = document.getElementById('sale_type');
    const creditOnly = () => document.querySelectorAll('.credit-only');

    function toggleCreditFields(){
      const isCredit = saleType.value === 'credit';
      creditOnly().forEach(el => el.style.display = isCredit ? '' : 'none');
      const client = document.getElementById('client_id');
      client.required = isCredit;
      const due = document.getElementById('due_date');
      due.required = isCredit;
    }

    function num(v){
      const n = parseFloat(String(v).replace(',', '.'));
      return isNaN(n) ? 0 : n;
    }

    function recalc(){
      let subtotal = 0;
      document.querySelectorAll('#itemsTable tbody tr').forEach(tr => {
        const qty = num(tr.querySelector('.qty')?.value);
        const price = num(tr.querySelector('.price')?.value);
        const line = Math.round((qty * price) * 100) / 100;
        subtotal += line;
        tr.querySelector('.line-total').textContent = line.toFixed(2);
      });
      subtotal = Math.round(subtotal * 100) / 100;
      const discount = Math.round(num(document.getElementById('discount_amount').value) * 100) / 100;
      const delivery = Math.round(num(document.getElementById('delivery_amount').value) * 100) / 100;

      const itbis = itbisEnabled ? Math.round((subtotal * (itbisPercent/100)) * 100) / 100 : 0;
      const total = itbisEnabled
        ? Math.round((subtotal + itbis - discount + delivery) * 100) / 100
        : Math.round((subtotal - discount + delivery) * 100) / 100;

      document.getElementById('subtotal').textContent = subtotal.toFixed(2);
      document.getElementById('itbis').textContent = itbis.toFixed(2);
      document.getElementById('discount').textContent = discount.toFixed(2);
      document.getElementById('delivery').textContent = delivery.toFixed(2);
      document.getElementById('total').textContent = total.toFixed(2);
    }

    function addRow(pn='', qty='1', price='0'){
      const tbody = document.querySelector('#itemsTable tbody');
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td><input name="product_name[]" class="form-control form-control-sm" required></td>
        <td><input name="qty[]" class="form-control form-control-sm qty" inputmode="decimal"></td>
        <td><input name="unit_price[]" class="form-control form-control-sm price" inputmode="decimal"></td>
        <td class="text-end"><span class="line-total">0.00</span></td>
        <td class="text-end"><button type="button" class="btn btn-sm btn-outline-danger removeRow">X</button></td>
      `;
      tr.querySelector('input[name="product_name[]"]').value = pn;
      tr.querySelector('input[name="qty[]"]').value = qty;
      tr.querySelector('input[name="unit_price[]"]').value = price;
      tbody.appendChild(tr);
      bindRow(tr);
      recalc();
    }

    function bindRow(tr){
      tr.querySelectorAll('input').forEach(i => i.addEventListener('input', recalc));
      tr.querySelector('.removeRow').addEventListener('click', () => {
        const rows = document.querySelectorAll('#itemsTable tbody tr');
        if (rows.length <= 1) return;
        tr.remove();
        recalc();
      });
    }

    document.querySelectorAll('#itemsTable tbody tr').forEach(bindRow);
    document.getElementById('discount_amount').addEventListener('input', recalc);
    document.getElementById('delivery_amount').addEventListener('input', recalc);
    document.getElementById('addRow').addEventListener('click', () => addRow());
    saleType.addEventListener('change', toggleCreditFields);

    toggleCreditFields();
    recalc();
  })();
</script>


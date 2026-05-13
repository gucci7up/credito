<?php
  $bizName = $settings['business_name'] ?? 'Mi Negocio';
  $currency = $settings['currency'] ?? 'RD$';
  $footerText = $settings['footer_text'] ?? '';
  $logoPath = $settings['logo_path'] ?? '';

  $itbisEnabled = ((int)($sale['itbis_enabled'] ?? 0) === 1);
  $status = $sale['status'] ?? 'PAID';
  $isCredit = ($sale['sale_type'] ?? '') === 'credit';

  $fmt = function($n) {
    return number_format((float)$n, 2, '.', '');
  };
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=58mm, initial-scale=1">
  <title>Factura #<?= (int)$sale['id'] ?></title>
  <link href="/assets/css/print-ticket.css" rel="stylesheet">
</head>
<body>
  <div class="no-print" style="padding:10px; width:58mm;">
    <button class="btn-print" onclick="window.print()">Imprimir factura</button>
    <a class="btn-print" style="text-decoration:none; margin-left:6px;" href="/?r=sales/view&id=<?= (int)$sale['id'] ?>">Volver</a>
  </div>

  <div class="ticket">
    <div class="center">
      <?php if (!empty($logoPath)): ?>
        <div style="margin-bottom:6px;">
          <img class="logo" src="/<?= htmlspecialchars($logoPath) ?>" alt="Logo">
        </div>
      <?php endif; ?>
      <div style="font-weight:bold;"><?= htmlspecialchars($bizName) ?></div>
      <div class="muted">FACTURA</div>
    </div>

    <div class="hr"></div>

    <div>
      <div>Fecha: <?= htmlspecialchars($sale['sale_date']) ?></div>
      <div>Factura: #<?= (int)$sale['id'] ?></div>
      <div>Cliente: <?= htmlspecialchars($sale['client_name'] ?? '-') ?></div>
      <?php if ($isCredit): ?>
        <div>Vence: <?= htmlspecialchars($sale['due_date'] ?? '-') ?></div>
      <?php endif; ?>
    </div>

    <div class="hr"></div>

    <table class="items">
      <?php foreach ($items as $it): ?>
        <tr>
          <td class="item-name">
            <?= htmlspecialchars($it['product_name']) ?><br>
            <span class="muted"><?= $fmt($it['qty']) ?> x <?= $fmt($it['unit_price']) ?></span>
          </td>
          <td class="right" style="width:18mm;"><?= $fmt($it['line_total']) ?></td>
        </tr>
      <?php endforeach; ?>
    </table>

    <div class="hr"></div>

    <table class="items">
      <tr>
        <td>Subtotal</td>
        <td class="right"><?= $fmt($sale['subtotal']) ?></td>
      </tr>
      <?php if ($itbisEnabled): ?>
        <tr>
          <td>ITBIS (<?= $fmt($sale['itbis_percent']) ?>%)</td>
          <td class="right"><?= $fmt($sale['itbis_amount']) ?></td>
        </tr>
      <?php endif; ?>
      <tr>
        <td>Descuento</td>
        <td class="right">-<?= $fmt($sale['discount_amount']) ?></td>
      </tr>
      <?php if ((float)$sale['delivery_amount'] > 0): ?>
        <tr>
          <td>Delivery</td>
          <td class="right"><?= $fmt($sale['delivery_amount']) ?></td>
        </tr>
      <?php endif; ?>
      <tr>
        <td style="font-weight:bold;">TOTAL</td>
        <td class="right" style="font-weight:bold;"><?= $currency ?> <?= $fmt($sale['total']) ?></td>
      </tr>
    </table>

    <div class="hr"></div>

    <div class="center" style="font-weight:bold;">
      <?= $status === 'PENDING' ? 'PENDIENTE' : 'PAGADO' ?>
    </div>

    <?php if (!empty($footerText)): ?>
      <div class="hr"></div>
      <div class="center"><?= nl2br(htmlspecialchars($footerText)) ?></div>
    <?php endif; ?>

    <div style="height:10mm;"></div>
  </div>
</body>
</html>


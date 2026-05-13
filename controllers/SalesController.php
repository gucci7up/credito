<?php

class SalesController extends BaseController
{
    public function indexAction(): void
    {
        $q = (string)$this->get('q', '');
        $sales = (new Sale())->list($q);
        $this->view('sales/index', [
            'sales' => $sales,
            'q' => $q,
        ]);
    }

    public function createAction(): void
    {
        $settings = new Setting();
        $itbisEnabledGlobal = ($settings->get('itbis_enabled', '0') === '1');
        $itbisPercentGlobal = (float)$settings->get('itbis_percent', '18');
        $defaultDiscount = (float)$settings->get('default_discount', '0');

        $clients = (new Client())->search('');

        $errors = [];
        $form = [
            'sale_type' => 'cash',
            'client_id' => '',
            'due_date' => '',
            'initial_payment' => '',
            'discount_amount' => (string)$defaultDiscount,
            'delivery_amount' => '0',
            'items' => [
                ['product_name' => '', 'qty' => '1', 'unit_price' => '0'],
            ],
        ];

        if ($this->requestMethod() === 'POST') {
            $form['sale_type'] = $this->post('sale_type', 'cash') === 'credit' ? 'credit' : 'cash';
            $form['client_id'] = trim((string)$this->post('client_id', ''));
            $form['due_date'] = trim((string)$this->post('due_date', ''));
            $form['initial_payment'] = trim((string)$this->post('initial_payment', ''));
            $form['discount_amount'] = trim((string)$this->post('discount_amount', '0'));
            $form['delivery_amount'] = trim((string)$this->post('delivery_amount', '0'));

            $names = $_POST['product_name'] ?? [];
            $qtys = $_POST['qty'] ?? [];
            $prices = $_POST['unit_price'] ?? [];

            $items = [];
            $count = max(count($names), count($qtys), count($prices));
            for ($i = 0; $i < $count; $i++) {
                $pn = trim((string)($names[$i] ?? ''));
                $qv = trim((string)($qtys[$i] ?? ''));
                $pv = trim((string)($prices[$i] ?? ''));
                if ($pn === '') {
                    continue;
                }
                $items[] = ['product_name' => $pn, 'qty' => $qv, 'unit_price' => $pv];
            }
            $form['items'] = $items ?: $form['items'];

            if (!is_numeric($form['discount_amount']) || (float)$form['discount_amount'] < 0) {
                $errors[] = 'Descuento inválido.';
            }
            if (!is_numeric($form['delivery_amount']) || (float)$form['delivery_amount'] < 0) {
                $errors[] = 'Delivery inválido.';
            }

            if ($form['sale_type'] === 'credit') {
                if ($form['client_id'] === '' || (int)$form['client_id'] <= 0) {
                    $errors[] = 'Cliente es obligatorio para venta a crédito.';
                }
                if ($form['due_date'] === '') {
                    $errors[] = 'Fecha de pago (vencimiento) es obligatoria para crédito.';
                }
                if ($form['initial_payment'] !== '' && (!is_numeric($form['initial_payment']) || (float)$form['initial_payment'] < 0)) {
                    $errors[] = 'Inicial inválida.';
                }
            }

            if (empty($items)) {
                $errors[] = 'Debes agregar al menos 1 producto (nombre obligatorio).';
            }

            // Validar ítems y calcular
            $calcItems = [];
            $subtotal = 0.0;
            foreach ($items as $it) {
                if (!is_numeric($it['qty']) || (float)$it['qty'] <= 0) {
                    $errors[] = 'Cantidad inválida en un producto.';
                    break;
                }
                if (!is_numeric($it['unit_price']) || (float)$it['unit_price'] < 0) {
                    $errors[] = 'Precio inválido en un producto.';
                    break;
                }
                $qty = (float)$it['qty'];
                $unit = (float)$it['unit_price'];
                $line = round($qty * $unit, 2);
                $subtotal += $line;
                $calcItems[] = [
                    'product_name' => $it['product_name'],
                    'qty' => $qty,
                    'unit_price' => $unit,
                    'line_total' => $line,
                ];
            }
            $subtotal = round($subtotal, 2);

            $discount = round((float)$form['discount_amount'], 2);
            $delivery = round((float)$form['delivery_amount'], 2);

            $itbisEnabled = $itbisEnabledGlobal ? 1 : 0;
            $itbisPercent = $itbisEnabledGlobal ? round($itbisPercentGlobal, 2) : 0.0;
            $itbisAmount = $itbisEnabledGlobal ? round($subtotal * ($itbisPercent / 100), 2) : 0.0;

            $total = $itbisEnabledGlobal
                ? round($subtotal + $itbisAmount - $discount + $delivery, 2)
                : round($subtotal - $discount + $delivery, 2);

            if ($total < 0) {
                $errors[] = 'El total no puede ser negativo (revisa descuento/delivery).';
            }

            if (!$errors) {
                $saleDate = date('Y-m-d H:i:s');
                $sale = [
                    'sale_type' => $form['sale_type'],
                    'client_id' => ($form['client_id'] !== '' ? (int)$form['client_id'] : null),
                    'sale_date' => $saleDate,
                    'subtotal' => $subtotal,
                    'itbis_enabled' => $itbisEnabled,
                    'itbis_percent' => $itbisPercent,
                    'itbis_amount' => $itbisAmount,
                    'discount_amount' => $discount,
                    'delivery_amount' => $delivery,
                    'total' => $total,
                    'status' => 'PAID',
                ];

                if ($form['sale_type'] === 'credit') {
                    $initial = $form['initial_payment'] === '' ? 0.0 : round((float)$form['initial_payment'], 2);
                    $balance = round(max(0, $total - $initial), 2);
                    $status = $balance <= 0 ? 'PAID' : 'PENDING';

                    $sale['status'] = $status;
                    $sale['initial_payment'] = $initial > 0 ? $initial : null;
                    $sale['balance_due'] = $balance;
                    $sale['due_date'] = $form['due_date'];
                    $sale['paid_at'] = $status === 'PAID' ? $saleDate : null;
                } else {
                    $sale['paid_at'] = $saleDate;
                }

                $saleId = (new Sale())->createSale($sale, $calcItems);
                $this->redirect('invoices/ticket', ['id' => $saleId]);
            }
        }

        $this->view('sales/create', [
            'clients' => $clients,
            'errors' => $errors,
            'form' => $form,
            'itbisEnabledGlobal' => $itbisEnabledGlobal,
            'itbisPercentGlobal' => $itbisPercentGlobal,
            'defaultDiscount' => $defaultDiscount,
        ]);
    }

    public function viewAction(): void
    {
        $id = (int)$this->get('id', 0);
        $saleModel = new Sale();
        $sale = $saleModel->find($id);
        if (!$sale) {
            http_response_code(404);
            echo "Venta no encontrada.";
            return;
        }

        $items = $saleModel->items($id);
        $payments = $saleModel->payments($id);

        $this->view('sales/view', [
            'sale' => $sale,
            'items' => $items,
            'payments' => $payments,
        ]);
    }

    public function addpaymentAction(): void
    {
        if ($this->requestMethod() !== 'POST') {
            http_response_code(405);
            echo "Método no permitido.";
            return;
        }

        $saleId = (int)$this->post('sale_id', 0);
        $amount = (float)$this->post('amount', 0);
        $note = trim((string)$this->post('note', ''));

        if ($saleId <= 0 || $amount <= 0) {
            $this->redirect('sales/view', ['id' => $saleId]);
        }

        (new Sale())->addPayment($saleId, $amount, date('Y-m-d H:i:s'), $note ?: null);
        $this->redirect('sales/view', ['id' => $saleId]);
    }
}

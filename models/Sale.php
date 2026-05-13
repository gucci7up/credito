<?php

class Sale extends BaseModel
{
    public function createSale(array $sale, array $items): int
    {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO sales(
                    sale_type, client_id, sale_date,
                    subtotal, itbis_enabled, itbis_percent, itbis_amount,
                    discount_amount, delivery_amount, total,
                    status, initial_payment, balance_due, due_date, paid_at
                ) VALUES(
                    :sale_type, :client_id, :sale_date,
                    :subtotal, :itbis_enabled, :itbis_percent, :itbis_amount,
                    :discount_amount, :delivery_amount, :total,
                    :status, :initial_payment, :balance_due, :due_date, :paid_at
                )"
            );

            $stmt->execute([
                'sale_type' => $sale['sale_type'],
                'client_id' => $sale['client_id'] ?? null,
                'sale_date' => $sale['sale_date'],
                'subtotal' => $sale['subtotal'],
                'itbis_enabled' => $sale['itbis_enabled'],
                'itbis_percent' => $sale['itbis_percent'],
                'itbis_amount' => $sale['itbis_amount'],
                'discount_amount' => $sale['discount_amount'],
                'delivery_amount' => $sale['delivery_amount'],
                'total' => $sale['total'],
                'status' => $sale['status'],
                'initial_payment' => $sale['initial_payment'] ?? null,
                'balance_due' => $sale['balance_due'] ?? null,
                'due_date' => $sale['due_date'] ?? null,
                'paid_at' => $sale['paid_at'] ?? null,
            ]);

            $saleId = (int)$this->db->lastInsertId();

            $stmtItem = $this->db->prepare(
                "INSERT INTO sale_items(sale_id, product_name, qty, unit_price, line_total)
                 VALUES(:sale_id, :product_name, :qty, :unit_price, :line_total)"
            );
            foreach ($items as $it) {
                $stmtItem->execute([
                    'sale_id' => $saleId,
                    'product_name' => $it['product_name'],
                    'qty' => $it['qty'],
                    'unit_price' => $it['unit_price'],
                    'line_total' => $it['line_total'],
                ]);
            }

            // Si hay pago inicial, registrarlo como "cobro"
            if (!empty($sale['initial_payment']) && (float)$sale['initial_payment'] > 0) {
                $stmtPay = $this->db->prepare("INSERT INTO payments(sale_id, amount, paid_date, note) VALUES(:sale_id,:amount,:paid_date,:note)");
                $stmtPay->execute([
                    'sale_id' => $saleId,
                    'amount' => $sale['initial_payment'],
                    'paid_date' => $sale['sale_date'],
                    'note' => 'Pago inicial',
                ]);
            }

            $this->db->commit();
            return $saleId;
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT s.*, c.name AS client_name
          FROM sales s
          LEFT JOIN clients c ON c.id = s.client_id
          WHERE s.id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function items(int $saleId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM sale_items WHERE sale_id = :id ORDER BY id ASC");
        $stmt->execute(['id' => $saleId]);
        return $stmt->fetchAll();
    }

    public function list(string $q = ''): array
    {
        $q = trim($q);
        if ($q === '') {
            $stmt = $this->db->query("SELECT s.*, c.name AS client_name
              FROM sales s
              LEFT JOIN clients c ON c.id = s.client_id
              ORDER BY s.id DESC");
            return $stmt->fetchAll();
        }

        $stmt = $this->db->prepare("SELECT s.*, c.name AS client_name
          FROM sales s
          LEFT JOIN clients c ON c.id = s.client_id
          WHERE c.name LIKE :q OR s.id LIKE :q
          ORDER BY s.id DESC");
        $stmt->execute(['q' => '%' . $q . '%']);
        return $stmt->fetchAll();
    }

    public function addPayment(int $saleId, float $amount, string $paidDate, ?string $note = null): void
    {
        $this->db->beginTransaction();
        try {
            $sale = $this->find($saleId);
            if (!$sale) {
                throw new RuntimeException('Venta no encontrada.');
            }
            if ($sale['sale_type'] !== 'credit') {
                throw new RuntimeException('Solo aplica a ventas a crédito.');
            }
            if ($sale['status'] === 'PAID') {
                throw new RuntimeException('La venta ya está pagada.');
            }

            $stmt = $this->db->prepare("INSERT INTO payments(sale_id, amount, paid_date, note) VALUES(:sale_id,:amount,:paid_date,:note)");
            $stmt->execute([
                'sale_id' => $saleId,
                'amount' => $amount,
                'paid_date' => $paidDate,
                'note' => $note,
            ]);

            $newBalance = max(0, (float)$sale['balance_due'] - $amount);
            $status = $newBalance <= 0 ? 'PAID' : 'PENDING';
            $paidAt = $status === 'PAID' ? $paidDate : null;

            $stmt2 = $this->db->prepare("UPDATE sales SET balance_due=:b, status=:s, paid_at=:p WHERE id=:id");
            $stmt2->execute([
                'id' => $saleId,
                'b' => $newBalance,
                's' => $status,
                'p' => $paidAt,
            ]);

            $this->db->commit();
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function payments(int $saleId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM payments WHERE sale_id = :id ORDER BY id DESC");
        $stmt->execute(['id' => $saleId]);
        return $stmt->fetchAll();
    }
}


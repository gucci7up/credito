<?php

class DashboardController extends BaseController
{
    public function indexAction(): void
    {
        $db = Database::pdo();
        $today = date('Y-m-d');
        $monthStart = date('Y-m-01');

        $ventasHoy = (float)$db->query("SELECT COALESCE(SUM(total),0) AS v FROM sales WHERE sale_type='cash' AND DATE(sale_date)=CURDATE()")->fetch()['v'];
        $creditosHoy = (float)$db->query("SELECT COALESCE(SUM(total),0) AS v FROM sales WHERE sale_type='credit' AND DATE(sale_date)=CURDATE()")->fetch()['v'];
        $cobrosHoy = (float)$db->query("SELECT COALESCE(SUM(amount),0) AS v FROM payments WHERE DATE(paid_date)=CURDATE()")->fetch()['v'];
        $stmtMonth = $db->prepare("SELECT COALESCE(SUM(total),0) AS v FROM sales WHERE sale_type='cash' AND DATE(sale_date) >= :m");
        $stmtMonth->execute(['m' => $monthStart]);
        $ventasMes = (float)$stmtMonth->fetch()['v'];

        $clientesDeuda = (int)$db->query("SELECT COUNT(DISTINCT client_id) AS c
          FROM sales
          WHERE sale_type='credit' AND status='PENDING' AND balance_due > 0 AND client_id IS NOT NULL")->fetch()['c'];

        $stmtOver = $db->prepare("SELECT s.id, s.due_date, s.balance_due, c.name AS client_name
          FROM sales s
          LEFT JOIN clients c ON c.id = s.client_id
          WHERE s.sale_type='credit' AND s.status='PENDING' AND s.due_date IS NOT NULL AND s.due_date < :t
          ORDER BY s.due_date ASC, s.id DESC
          LIMIT 10");
        $stmtOver->execute(['t' => $today]);
        $vencidas = $stmtOver->fetchAll();

        $stmtToday = $db->prepare("SELECT s.id, s.due_date, s.balance_due, c.name AS client_name
          FROM sales s
          LEFT JOIN clients c ON c.id = s.client_id
          WHERE s.sale_type='credit' AND s.status='PENDING' AND s.due_date = :t
          ORDER BY s.id DESC
          LIMIT 10");
        $stmtToday->execute(['t' => $today]);
        $vencenHoy = $stmtToday->fetchAll();

        $stmtRecentSales = $db->query("SELECT s.id, s.sale_type, s.status, s.total, s.sale_date, c.name AS client_name
          FROM sales s
          LEFT JOIN clients c ON c.id = s.client_id
          ORDER BY s.id DESC
          LIMIT 8");
        $recentSales = $stmtRecentSales ? $stmtRecentSales->fetchAll() : [];

        $stmtRecentPayments = $db->query("SELECT p.id, p.amount, p.paid_date, s.id AS sale_id, c.name AS client_name
          FROM payments p
          JOIN sales s ON s.id = p.sale_id
          LEFT JOIN clients c ON c.id = s.client_id
          ORDER BY p.id DESC
          LIMIT 6");
        $recentPayments = $stmtRecentPayments ? $stmtRecentPayments->fetchAll() : [];

        $this->view('dashboard/index', [
            'stats' => [
                'ventas_hoy' => $ventasHoy,
                'creditos_hoy' => $creditosHoy,
                'cobros_hoy' => $cobrosHoy,
                'ventas_mes' => $ventasMes,
                'clientes_con_deuda' => $clientesDeuda,
                'vencidas' => count($vencidas),
                'vencen_hoy' => count($vencenHoy),
            ],
            'alerts' => [
                'vencidas' => $vencidas,
                'vencen_hoy' => $vencenHoy,
            ],
            'recent_sales' => $recentSales,
            'recent_payments' => $recentPayments,
        ]);
    }
}

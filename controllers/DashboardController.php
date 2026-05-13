<?php

class DashboardController extends BaseController
{
    public function indexAction(): void
    {
        $db = Database::pdo();
        $today = date('Y-m-d');

        $ventasHoy = (float)$db->query("SELECT COALESCE(SUM(total),0) AS v FROM sales WHERE sale_type='cash' AND DATE(sale_date)=CURDATE()")->fetch()['v'];
        $creditosHoy = (float)$db->query("SELECT COALESCE(SUM(total),0) AS v FROM sales WHERE sale_type='credit' AND DATE(sale_date)=CURDATE()")->fetch()['v'];
        $cobrosHoy = (float)$db->query("SELECT COALESCE(SUM(amount),0) AS v FROM payments WHERE DATE(paid_date)=CURDATE()")->fetch()['v'];

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

        $this->view('dashboard/index', [
            'stats' => [
                'ventas_hoy' => $ventasHoy,
                'creditos_hoy' => $creditosHoy,
                'cobros_hoy' => $cobrosHoy,
                'clientes_con_deuda' => $clientesDeuda,
                'vencidas' => count($vencidas),
                'vencen_hoy' => count($vencenHoy),
            ],
            'alerts' => [
                'vencidas' => $vencidas,
                'vencen_hoy' => $vencenHoy,
            ]
        ]);
    }
}

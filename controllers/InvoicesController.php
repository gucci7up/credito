<?php

class InvoicesController extends BaseController
{
    public function ticketAction(): void
    {
        $id = (int)$this->get('id', 0);
        $saleModel = new Sale();
        $sale = $saleModel->find($id);
        if (!$sale) {
            http_response_code(404);
            echo "Factura no encontrada.";
            return;
        }

        $items = $saleModel->items($id);
        $settings = (new Setting())->getAll();

        // Render sin layout (optimizado para impresión)
        $viewFile = __DIR__ . '/../views/invoices/ticket.php';
        require $viewFile;
    }
}


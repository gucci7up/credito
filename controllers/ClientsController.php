<?php

class ClientsController extends BaseController
{
    public function indexAction(): void
    {
        $q = (string)$this->get('q', '');
        $clients = (new Client())->search($q);
        $this->view('clients/index', [
            'clients' => $clients,
            'q' => $q,
        ]);
    }

    public function createAction(): void
    {
        $errors = [];
        $data = [
            'name' => '',
            'phone' => '',
            'address' => '',
            'email' => '',
        ];

        if ($this->requestMethod() === 'POST') {
            $data = [
                'name' => trim((string)$this->post('name', '')),
                'phone' => trim((string)$this->post('phone', '')),
                'address' => trim((string)$this->post('address', '')),
                'email' => trim((string)$this->post('email', '')),
            ];

            if ($data['name'] === '') $errors[] = 'Nombre es obligatorio.';
            if ($data['phone'] === '') $errors[] = 'Teléfono es obligatorio.';
            if ($data['address'] === '') $errors[] = 'Dirección es obligatoria.';

            if ($data['email'] !== '' && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email inválido.';
            }

            if (!$errors) {
                (new Client())->create($data);
                $this->redirect('clients/index');
            }
        }

        $this->view('clients/form', [
            'mode' => 'create',
            'errors' => $errors,
            'data' => $data,
        ]);
    }

    public function editAction(): void
    {
        $id = (int)$this->get('id', 0);
        $model = new Client();
        $client = $model->find($id);
        if (!$client) {
            http_response_code(404);
            echo "Cliente no encontrado.";
            return;
        }

        $errors = [];
        $data = [
            'name' => $client['name'],
            'phone' => $client['phone'],
            'address' => $client['address'],
            'email' => $client['email'] ?? '',
        ];

        if ($this->requestMethod() === 'POST') {
            $data = [
                'name' => trim((string)$this->post('name', '')),
                'phone' => trim((string)$this->post('phone', '')),
                'address' => trim((string)$this->post('address', '')),
                'email' => trim((string)$this->post('email', '')),
            ];

            if ($data['name'] === '') $errors[] = 'Nombre es obligatorio.';
            if ($data['phone'] === '') $errors[] = 'Teléfono es obligatorio.';
            if ($data['address'] === '') $errors[] = 'Dirección es obligatoria.';
            if ($data['email'] !== '' && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email inválido.';
            }

            if (!$errors) {
                $model->update($id, $data);
                $this->redirect('clients/index');
            }
        }

        $this->view('clients/form', [
            'mode' => 'edit',
            'id' => $id,
            'errors' => $errors,
            'data' => $data,
        ]);
    }

    public function deleteAction(): void
    {
        if ($this->requestMethod() !== 'POST') {
            http_response_code(405);
            echo "Método no permitido.";
            return;
        }

        $id = (int)$this->post('id', 0);
        if ($id > 0) {
            (new Client())->delete($id);
        }
        $this->redirect('clients/index');
    }
}


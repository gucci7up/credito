<?php

class Client extends BaseModel
{
    public function search(string $q = ''): array
    {
        $q = trim($q);
        if ($q === '') {
            $stmt = $this->db->query("SELECT * FROM clients ORDER BY id DESC");
            return $stmt->fetchAll();
        }

        $stmt = $this->db->prepare("SELECT * FROM clients
          WHERE name LIKE :q OR phone LIKE :q OR address LIKE :q OR email LIKE :q
          ORDER BY id DESC");
        $stmt->execute(['q' => '%' . $q . '%']);
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM clients WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO clients(name, phone, address, email) VALUES(:name,:phone,:address,:email)");
        $stmt->execute([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'email' => $data['email'] ?? null,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare("UPDATE clients SET name=:name, phone=:phone, address=:address, email=:email WHERE id=:id");
        $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'email' => $data['email'] ?? null,
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM clients WHERE id=:id");
        $stmt->execute(['id' => $id]);
    }
}


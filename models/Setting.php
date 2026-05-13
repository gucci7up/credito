<?php

class Setting extends BaseModel
{
    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT `key`, `value` FROM settings");
        $rows = $stmt->fetchAll();
        $out = [];
        foreach ($rows as $r) {
            $out[$r['key']] = $r['value'];
        }
        return $out;
    }

    public function get(string $key, $default = null)
    {
        $stmt = $this->db->prepare("SELECT `value` FROM settings WHERE `key` = :k LIMIT 1");
        $stmt->execute(['k' => $key]);
        $row = $stmt->fetch();
        return $row ? $row['value'] : $default;
    }

    public function set(string $key, ?string $value): void
    {
        $stmt = $this->db->prepare("INSERT INTO settings(`key`,`value`) VALUES(:k,:v)
          ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)");
        $stmt->execute(['k' => $key, 'v' => $value]);
    }

    public function setMany(array $kv): void
    {
        $this->db->beginTransaction();
        try {
            foreach ($kv as $k => $v) {
                $this->set((string)$k, $v === null ? null : (string)$v);
            }
            $this->db->commit();
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}


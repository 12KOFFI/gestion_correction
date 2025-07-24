<?php
namespace Config;

use Config\Database;
use PDO;

abstract class BaseModel {
    protected PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    public function toArray(): array {
        return get_object_vars($this);
    }

    public function formArray(array $data): void {
        foreach ($data as $key => $value) {
            $setter = 'set' . ucfirst($key);
            if (method_exists($this, $setter)) {
                $this->$setter($value);
            }
        }
    }
}

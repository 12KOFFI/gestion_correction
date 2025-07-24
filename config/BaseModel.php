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

    public function formArray(array $data): self {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
        return $this;
    }
}

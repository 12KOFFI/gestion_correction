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
        return get_object_vars($this); // Retourne un tableau associatif des propriétés de l'objet
    }

    public function formArray(array $data): void {
        foreach ($data as $key => $value) {
            $setter = 'set' . ucfirst($key); // Convertit le nom de la clé en setter
            if (method_exists($this, $setter)) {
                // Appel du setter s'il existe
                $this->$setter($value);
            } elseif (property_exists($this, $key)) {
                // Définition directe de la propriété si elle existe
                $this->$key = $value;
            } else {
                // Pour le débogage : afficher les propriétés non reconnues
                error_log("Propriété non reconnue: " . get_class($this) . "->$key");
            }
        }
        
    }
}

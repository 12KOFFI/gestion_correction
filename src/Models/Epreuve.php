<?php
namespace App\Models;

use Config\BaseModel;
use PDO;

class Epreuve extends BaseModel {
    protected string $table = 'epreuve';

    public int $id;
    public string $nom;
    public string $type;
    public ?int $id_examen = null;

    // Récupérer toutes les épreuves
    public function getAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll();
    }

    // Récupérer une épreuve par ID
    public function getById(int $id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Enregistrer une nouvelle épreuve
    public function save(): bool {
        $sql = "INSERT INTO {$this->table} (nom, type, id_examen)
                VALUES (:nom, :type, :id_examen)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'nom'       => $this->nom,
            'type'      => $this->type,
            'id_examen' => $this->id_examen
        ]);
    }

    // Mettre à jour une épreuve
    public function update(): bool {
        $sql = "UPDATE {$this->table}
                SET nom = :nom, type = :type, id_examen = :id_examen
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'nom'       => $this->nom,
            'type'      => $this->type,
            'id_examen' => $this->id_examen,
            'id'        => $this->id
        ]);
    }

    // Supprimer une épreuve
    public function delete(): bool {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$this->id]);
    }
}

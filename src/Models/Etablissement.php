<?php
namespace App\Models;

use Config\BaseModel;
use PDO;

class Etablissement extends BaseModel {
    protected string $table = 'etablissement';

    public int $id;
    public string $nom;
    public ?string $ville = null;

    // Récupérer tous les établissements
    public function getAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll();
    }

    // Récupérer un établissement par ID
    public function getById(int $id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Enregistrer un nouvel établissement
    public function save(): bool {
        $sql = "INSERT INTO {$this->table} (nom, ville)
                VALUES (:nom, :ville)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'nom'   => $this->nom,
            'ville' => $this->ville
        ]);
    }

    // Mettre à jour un établissement
    public function update(): bool {
        $sql = "UPDATE {$this->table}
                SET nom = :nom, ville = :ville
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'nom'   => $this->nom,
            'ville' => $this->ville,
            'id'    => $this->id
        ]);
    }

    // Supprimer un établissement
    public function delete(): bool {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$this->id]);
    }
}

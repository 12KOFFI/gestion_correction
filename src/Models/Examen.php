<?php
namespace App\Models;

use Config\BaseModel;
use PDO;

class Examen extends BaseModel {
    protected string $table = 'examen';

    public int $id;
    public string $nom;

    // Récupérer tous les examens
    public function getAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll();
    }

    // Récupérer un examen par ID
    public function getById(int $id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Enregistrer un nouvel examen
    public function save(): bool {
        $sql = "INSERT INTO {$this->table} (nom) VALUES (:nom)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['nom' => $this->nom]);
    }

    // Mettre à jour un examen
    public function update(): bool {
        $sql = "UPDATE {$this->table} SET nom = :nom WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['nom' => $this->nom, 'id' => $this->id]);
    }

    // Supprimer un examen
    public function delete(): bool {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$this->id]);
    }
}

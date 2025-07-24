<?php
namespace App\Models;

use Config\BaseModel;
use PDO;

class Examen extends BaseModel 
{
    protected string $table = 'examen';
    protected ?int $id = null;
    protected string $nom = '';

    // Getters et Setters
    public function getId(): ?int {
        return $this->id;
    }

    public function getNom(): string {
        return $this->nom;
    }

    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function setNom(string $nom): void {
        $this->nom = $nom;
    }

    // Méthodes CRUD
    public function getAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function save(): bool {
        $sql = "INSERT INTO {$this->table} (nom) VALUES (:nom)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['nom' => $this->nom]);
    }

    public function update(): bool {
        $sql = "UPDATE {$this->table} SET nom = :nom WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id' => $this->id,
            'nom' => $this->nom
        ]);
    }

    public function delete(): bool {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$this->id]);
    }
}
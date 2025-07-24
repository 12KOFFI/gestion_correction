<?php
namespace App\Models;

use Config\BaseModel;
use PDO;

class Etablissement extends BaseModel {
    protected string $table = 'etablissement';
    protected ?int $id = null;
    protected string $nom = '';
    protected string $ville = '';

    // Récupérer tous les établissements
    public function getAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll();
    }

    // Récupérer un établissement par ID
    public function getById(int $id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
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

    // Getters
    public function getId(): ?int {
        return $this->id;
    }

    public function getNom(): string {
        return $this->nom;
    }

    public function getVille(): string {
        return $this->ville;
    }

    // Setters
    public function setNom(string $nom): void {
        $this->nom = $nom;
    }

    public function setVille(string $ville): void {
        $this->ville = $ville;
    }

    public function setId(?int $id): void {
        $this->id = $id;
    }

    // Mettre à jour un établissement
    public function update(): bool {
        if (!$this->id) {
            throw new \RuntimeException("Cannot update etablissement without id");
        }
        
        $query = "UPDATE {$this->table} SET nom = :nom, ville = :ville WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([
            'id' => $this->id,
            'nom' => $this->nom,
            'ville' => $this->ville
        ]);
    }

    // Supprimer un établissement
    public function delete(): bool {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$this->id]);
    }
}

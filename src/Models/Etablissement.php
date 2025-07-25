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

    // Supprimer un établissement et ses professeurs associés
    public function delete(): bool {
        try {
            // Démarrer une transaction
            $this->pdo->beginTransaction();
            
            // 1. D'abord, supprimer tous les professeurs liés à cet établissement
            $stmt = $this->pdo->prepare("DELETE FROM professeur WHERE id_etab = ?");
            $stmt->execute([$this->id]);
            
            // 2. Ensuite, supprimer l'établissement
            $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
            $result = $stmt->execute([$this->id]);
            
            // Valider la transaction si tout s'est bien passé
            $this->pdo->commit();
            return $result;
            
        } catch (\PDOException $e) {
            // En cas d'erreur, annuler la transaction
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            // Relancer l'exception pour qu'elle soit gérée par le contrôleur
            throw $e;
        }
    }
}

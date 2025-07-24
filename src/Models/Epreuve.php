<?php
namespace App\Models;

use Config\BaseModel;
use PDO;
use RuntimeException;

class Epreuve extends BaseModel {
    protected string $table = 'epreuve';

    public ?int $id = null;
    public string $nom = '';
    public string $type = '';
    public ?int $id_examen = null;

    // Récupérer toutes les épreuves avec jointure optionnelle
    public function getAll(bool $withExamen = false): array {
        $sql = "SELECT e.*";
        if ($withExamen) {
            $sql .= ", ex.nom as examen_nom";
        }
        $sql .= " FROM {$this->table} e";
        
        if ($withExamen) {
            $sql .= " LEFT JOIN examen ex ON e.id_examen = ex.id";
        }

        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer une épreuve par ID avec contrôle strict
    public function getById(int $id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Enregistrer une nouvelle épreuve avec validation
    public function save(): bool {
        $this->validate();
        
        $sql = "INSERT INTO {$this->table} (nom, type, id_examen)
                VALUES (:nom, :type, :id_examen)";
        
        $stmt = $this->pdo->prepare($sql);
        $success = $stmt->execute([
            'nom'       => $this->nom,
            'type'      => $this->type,
            'id_examen' => $this->id_examen
        ]);

        if ($success) {
            $this->id = $this->pdo->lastInsertId();
        }

        return $success;
    }

    // Mettre à jour une épreuve avec validation
    public function update(): bool {
        $this->validate();
        
        if ($this->id === null) {
            throw new RuntimeException("Impossible de mettre à jour: ID non défini");
        }

        $sql = "UPDATE {$this->table}
                SET nom = :nom, type = :type, id_examen = :id_examen
                WHERE id = :id";
                
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id'        => $this->id,
            'nom'       => $this->nom,
            'type'      => $this->type,
            'id_examen' => $this->id_examen
        ]);
    }

    // Supprimer une épreuve
    public function delete(): bool {
        if ($this->id === null) {
            throw new RuntimeException("Impossible de supprimer: ID non défini");
        }

        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$this->id]);
    }

    // Validation des données
    private function validate(): void {
        if (empty($this->nom)) {
            throw new RuntimeException("Le nom de l'épreuve est obligatoire");
        }

        if (empty($this->type)) {
            throw new RuntimeException("Le type d'épreuve est obligatoire");
        }
    }
}
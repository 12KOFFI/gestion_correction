<?php
namespace App\Models;

use Config\BaseModel;
use PDO;

class Professeur extends BaseModel {
    protected string $table = 'professeur';

    public int $id;
    public string $nom;
    public string $prenom;
    public string $grade;
    public ?int $id_etab = null;

    // Récupérer tous les professeurs
    public function getAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll();
    }

    // Récupérer un professeur par ID
    public function getById(int $id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Enregistrer un nouveau professeur
    public function save(): bool {
        $sql = "INSERT INTO {$this->table} (nom, prenom, grade, id_etab)
                VALUES (:nom, :prenom, :grade, :id_etab)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'nom'     => $this->nom,
            'prenom'  => $this->prenom,
            'grade'   => $this->grade,
            'id_etab' => $this->id_etab
        ]);
    }

    // Mettre à jour un professeur
    public function update(): bool {
        $sql = "UPDATE {$this->table}
                SET nom = :nom, prenom = :prenom, grade = :grade, id_etab = :id_etab
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'nom'     => $this->nom,
            'prenom'  => $this->prenom,
            'grade'   => $this->grade,
            'id_etab' => $this->id_etab,
            'id'      => $this->id
        ]);
    }

    // Supprimer un professeur
    public function delete(): bool {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$this->id]);
    }
}

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
    protected ?int $id_etab = null;  // Changé de etablissement_id à id_etab

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

    // Récupérer tous les établissements
    public function getAllEtablissements(): array {
        $sql = "SELECT * FROM etablissement ORDER BY nom";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Enregistrer un nouveau professeur
    public function save(): bool {
        $fields = ['nom', 'prenom', 'grade'];
        $values = [
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'grade' => $this->grade
        ];
        
        if ($this->id_etab !== null) {
            $fields[] = 'id_etab';
            $values['id_etab'] = $this->id_etab;
        }
        
        $sql = "INSERT INTO {$this->table} (" . implode(', ', $fields) . ") 
                VALUES (:" . implode(', :', $fields) . ")";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($values);
    }

    // Mettre à jour un professeur
    public function update(): bool {
        $fields = ['nom = :nom', 'prenom = :prenom', 'grade = :grade'];
        $values = [
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'grade' => $this->grade
        ];
        
        if ($this->id_etab !== null) {
            $fields[] = 'id_etab = :id_etab';
            $values['id_etab'] = $this->id_etab;
        }
        
        $sql = "UPDATE {$this->table} 
                SET " . implode(', ', $fields) . " 
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($values);
    }

    // Supprimer un professeur
    public function delete(): bool {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$this->id]);
    }

    // Getters
    public function getId(): ?int {
        return $this->id;
    }

    public function getNom(): string {
        return $this->nom;
    }

    public function getPrenom(): string {
        return $this->prenom;
    }

    public function getGrade(): string {
        return $this->grade;
    }

    public function getEtablissementId(): ?int {
        return $this->id_etab;
    }

    // Setters
    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function setNom(string $nom): void {
        $this->nom = $nom;
    }

    public function setPrenom(string $prenom): void {
        $this->prenom = $prenom;
    }

    public function setGrade(string $grade): void {
        $this->grade = $grade;
    }

    public function setEtablissementId(?int $id_etab): void {
        $this->id_etab = $id_etab;
    }
}


<?php
namespace App\Models;
use Config\BaseModel;

use PDO;

class Correction extends BaseModel {
    public int $id;
    public ?int $id_professeur = null;
    public ?int $id_epreuve = null;
    public ?string $date = null;
    public ?int $nbr_copie = null;

    // Récupérer toutes les corrections avec les noms de professeurs et épreuves (jointures)
    public function getAllWithDetails(): array {
        $sql = "SELECT c.*, p.nom AS prof_nom, p.prenom AS prof_prenom, e.nom AS epreuve_nom
                FROM correction c
                LEFT JOIN professeur p ON c.id_professeur = p.id
                LEFT JOIN epreuve e ON c.id_epreuve = e.id
                ORDER BY c.id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Les autres méthodes CRUD héritées ou à implémenter si besoin
    public function save(): bool {
        $sql = "INSERT INTO correction (id_professeur, id_epreuve, date, nbr_copie) 
                VALUES (:id_professeur, :id_epreuve, :date, :nbr_copie)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id_professeur' => $this->id_professeur,
            ':id_epreuve' => $this->id_epreuve,
            ':date' => $this->date,
            ':nbr_copie' => $this->nbr_copie,
        ]);
    }

    public function update(): bool {
        $sql = "UPDATE correction SET id_professeur = :id_professeur, id_epreuve = :id_epreuve, date = :date, nbr_copie = :nbr_copie WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id_professeur' => $this->id_professeur,
            ':id_epreuve' => $this->id_epreuve,
            ':date' => $this->date,
            ':nbr_copie' => $this->nbr_copie,
            ':id' => $this->id,
        ]);
    }

    public function delete(): bool {
        $sql = "DELETE FROM correction WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $this->id]);
    }

    public function getById(int $id): ?self {
        $sql = "SELECT * FROM correction WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        if ($data) {
            $this->formArray((array)$data);
            return $this;
        }
        return null;
    }

    public function getAll(): array {
        $sql = "SELECT * FROM correction ORDER BY id DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
}

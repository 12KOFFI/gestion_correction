<?php

namespace App\Repository;

use PDO;
use App\Models\Epreuve;
use Config\Database;

class EpreuveRepository {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    /**
     * Récupère une épreuve par son ID
     * 
     * @param int $id L'ID de l'épreuve à récupérer
     * @return Epreuve|null Retourne l'objet Epreuve ou null si non trouvé
     */
    public function getById(int $id): ?Epreuve {
        $stmt = $this->pdo->prepare("SELECT * FROM epreuve WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            return null;
        }
        
        return new Epreuve(
            (int)$row['id'],
            $row['nom'],
            $row['type'],
            $row['id_examen'] ? (int)$row['id_examen'] : null
        );
    }

    /**
     * Récupère toutes les épreuves avec les détails des examens associés
     * 
     * @return array Tableau d'objets Epreuve avec les détails
     */
    public function getAll(): array {
        $query = "
            SELECT e.*, ex.nom as examen_nom
            FROM epreuve e
            LEFT JOIN examen ex ON e.id_examen = ex.id
            ORDER BY e.nom
        ";
        
        $stmt = $this->pdo->query($query);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $epreuves = [];
        foreach ($rows as $row) {
            $epreuve = new Epreuve(
                (int)$row['id'],
                $row['nom'],
                $row['type'],
                $row['id_examen'] ? (int)$row['id_examen'] : null
            );
            
            // Ajout des informations supplémentaires
            $epreuve->setExamenNom($row['examen_nom'] ?? null);
            
            $epreuves[] = $epreuve;
        }
        
        return $epreuves;
    }

    /**
     * Enregistre une nouvelle épreuve
     * 
     * @param Epreuve $epreuve L'objet Epreuve à enregistrer
     * @return Epreuve|null L'objet Epreuve avec l'ID généré ou null en cas d'erreur
     */
    public function save(Epreuve $epreuve): ?Epreuve {
        $stmt = $this->pdo->prepare("
            INSERT INTO epreuve (nom, type, id_examen) 
            VALUES (:nom, :type, :id_examen)
        ");

        $success = $stmt->execute([
            ':nom' => $epreuve->getNom(),
            ':type' => $epreuve->getType(),
            ':id_examen' => $epreuve->getIdExamen()
        ]);

        if ($success) {
            $epreuve->setId((int)$this->pdo->lastInsertId());
            return $epreuve;
        }

        return null;
    }

    /**
     * Met à jour une épreuve existante
     * 
     * @param Epreuve $epreuve L'objet Epreuve à mettre à jour
     * @return Epreuve|null L'objet Epreuve mis à jour ou null en cas d'erreur
     */
    public function update(Epreuve $epreuve): ?Epreuve {
        $stmt = $this->pdo->prepare("
            UPDATE epreuve
            SET nom = :nom, 
                type = :type, 
                id_examen = :id_examen
            WHERE id = :id
        ");

        $success = $stmt->execute([
            ':id' => $epreuve->getId(),
            ':nom' => $epreuve->getNom(),
            ':type' => $epreuve->getType(),
            ':id_examen' => $epreuve->getIdExamen()
        ]);

        return $success ? $epreuve : null;
    }

    /**
     * Supprime une épreuve par son ID
     * 
     * @param int $id L'ID de l'épreuve à supprimer
     * @return bool True si la suppression a réussi, false sinon
     */
    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM epreuve WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

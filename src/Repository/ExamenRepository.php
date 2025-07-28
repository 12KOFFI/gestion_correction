<?php

namespace App\Repository;

use PDO;
use App\Models\Examen;
use Config\Database;

class ExamenRepository {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    /**
     * Récupère un examen par son ID
     * 
     * @param int $id L'ID de l'examen à récupérer
     * @return Examen|null Retourne l'objet Examen ou null si non trouvé
     */
    public function getById(int $id): ?Examen {
        $query = "
            SELECT e.*, COUNT(ep.id) as nb_epreuves
            FROM examen e
            LEFT JOIN epreuve ep ON e.id = ep.id_examen
            WHERE e.id = ?
            GROUP BY e.id
        ";
        
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            return null;
        }
        
        $examen = new Examen(
            (int)$row['id'],
            $row['nom']
        );
        
        // Ajout d'informations supplémentaires
        $examen->setNbEpreuves((int)$row['nb_epreuves']);
        
        return $examen;
    }
    
    /**
     * Récupère tous les examens avec le nombre d'épreuves associées
     * 
     * @return array Tableau d'objets Examen avec les détails
     */
    public function getAll(): array {
        $query = "
            SELECT e.*, COUNT(ep.id) as nb_epreuves
            FROM examen e
            LEFT JOIN epreuve ep ON e.id = ep.id_examen
            GROUP BY e.id
            ORDER BY e.nom
        ";
        
        $stmt = $this->pdo->query($query);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $examens = [];
        foreach ($rows as $row) {
            $examen = new Examen(
                (int)$row['id'],
                $row['nom']
            );
            
            // Ajout d'informations supplémentaires
            $examen->setNbEpreuves((int)$row['nb_epreuves']);
            
            $examens[] = $examen;
        }
        
        return $examens;
    }
    
    /**
     * Enregistre un nouvel examen
     * 
     * @param Examen $examen L'objet Examen à enregistrer
     * @return Examen|null L'objet Examen avec l'ID généré ou null en cas d'erreur
     */
    public function save(Examen $examen): ?Examen {
        $stmt = $this->pdo->prepare("
            INSERT INTO examen (nom) 
            VALUES (:nom)
        ");

        $success = $stmt->execute([
            ':nom' => $examen->getNom()
        ]);

        if ($success) {
            $examen->setId((int)$this->pdo->lastInsertId());
            return $examen;
        }

        return null;
    }
    
    /**
     * Met à jour un examen existant
     * 
     * @param Examen $examen L'objet Examen à mettre à jour
     * @return Examen|null L'objet Examen mis à jour ou null en cas d'erreur
     */
    public function update(Examen $examen): ?Examen {
        $stmt = $this->pdo->prepare("
            UPDATE examen
            SET nom = :nom
            WHERE id = :id
        ");

        $success = $stmt->execute([
            ':id' => $examen->getId(),
            ':nom' => $examen->getNom()
        ]);

        return $success ? $examen : null;
    }
    
    /**
     * Supprime un examen par son ID
     * 
     * @param int $id L'ID de l'examen à supprimer
     * @return bool True si la suppression a réussi, false sinon
     */
    public function delete(int $id): bool {
        try {
            $this->pdo->beginTransaction();
            
            // Vérifier d'abord si l'examen existe
            $examen = $this->getById($id);
            if (!$examen) {
                $this->pdo->rollBack();
                return false;
            }
            
            // Supprimer d'abord les épreuves liées
            $stmt = $this->pdo->prepare("DELETE FROM epreuve WHERE id_examen = ?");
            $stmt->execute([$id]);
            
            // Puis supprimer l'examen
            $stmt = $this->pdo->prepare("DELETE FROM examen WHERE id = ?");
            $result = $stmt->execute([$id]);
            
            $this->pdo->commit();
            return $result;
            
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            error_log("Erreur lors de la suppression de l'examen (ID: $id): " . $e->getMessage());
            return false;
        }
    }
}

<?php

namespace App\Repository;

use PDO;
use App\Models\Correction;
use Config\Database;

class CorrectionRepository {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    /**
     * Récupère une correction par son ID
     * 
     * @param int $id L'ID de la correction à récupérer
     * @return Correction|null Retourne l'objet Correction ou null si non trouvé
     */
    public function getById(int $id): ?Correction {
        $stmt = $this->pdo->prepare("SELECT * FROM correction WHERE id = ?");
        $stmt->execute([$id]); 
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            return null;
        }
        
        return new Correction(
            (int)$row['id'],
            $row['id_professeur'] ? (int)$row['id_professeur'] : null,
            $row['id_epreuve'] ? (int)$row['id_epreuve'] : null,
            $row['date'],
            $row['nbr_copie'] ? (int)$row['nbr_copie'] : null
        );
    }

    /**
     * Récupère toutes les corrections avec les détails des professeurs et des épreuves
     * 
     * @return array Tableau d'objets Correction avec les détails
     */
    public function getAll(): array {
        $corrections = [];
        
        $query = "
            SELECT c.*, 
                   p.nom as professeur_nom, p.prenom as professeur_prenom,
                   e.nom as epreuve_nom
            FROM correction c
            LEFT JOIN professeur p ON c.id_professeur = p.id
            LEFT JOIN epreuve e ON c.id_epreuve = e.id
            ORDER BY c.date DESC
        ";
        
        $stmt = $this->pdo->query($query);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            $correction = new Correction(
                (int)$row['id'],
                $row['id_professeur'] ? (int)$row['id_professeur'] : null,
                $row['id_epreuve'] ? (int)$row['id_epreuve'] : null,
                $row['date'],
                $row['nbr_copie'] ? (int)$row['nbr_copie'] : null
            );
            
            // Ajout des informations supplémentaires
            $correction->professeur_nom = $row['professeur_prenom'] . ' ' . $row['professeur_nom'];
            $correction->epreuve_nom = $row['epreuve_nom'];
            
            $corrections[] = $correction;
        }

        return $corrections;
    }

    /**
     * Enregistre une nouvelle correction
     * 
     * @param Correction $correction L'objet Correction à enregistrer
     * @return Correction|null L'objet Correction avec l'ID généré ou null en cas d'erreur
     */
    public function save(Correction $correction): ?Correction {
        $stmt = $this->pdo->prepare("
            INSERT INTO correction (id_professeur, id_epreuve, date, nbr_copie) 
            VALUES (:id_professeur, :id_epreuve, :date, :nbr_copie)
        ");

        $success = $stmt->execute([
            ':id_professeur' => $correction->getIdProfesseur(),
            ':id_epreuve' => $correction->getIdEpreuve(),
            ':date' => $correction->getDate(),
            ':nbr_copie' => $correction->getNbrCopie()
        ]);

        if ($success) {
            $correction->setId((int)$this->pdo->lastInsertId());
            return $correction;
        }

        return null;
    }

    /**
     * Met à jour une correction existante
     * 
     * @param Correction $correction L'objet Correction à mettre à jour
     * @return Correction|null L'objet Correction mis à jour ou null en cas d'erreur
     */
    public function update(Correction $correction): ?Correction {
        $stmt = $this->pdo->prepare("
            UPDATE correction
            SET id_professeur = :id_professeur, 
                id_epreuve = :id_epreuve, 
                date = :date, 
                nbr_copie = :nbr_copie
            WHERE id = :id
        ");

        $success = $stmt->execute([
            ':id' => $correction->getId(),
            ':id_professeur' => $correction->getIdProfesseur(),
            ':id_epreuve' => $correction->getIdEpreuve(),
            ':date' => $correction->getDate(),
            ':nbr_copie' => $correction->getNbrCopie()
        ]);

        return $success ? $correction : null;
    }

    /**
     * Supprime une correction par son ID
     * 
     * @param int $id L'ID de la correction à supprimer
     * @return bool True si la suppression a réussi, false sinon
     */
    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM correction WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

<?php

namespace App\Repository;

use PDO;
use App\Models\Etablissement;
use Config\Database;

class EtablissementRepository {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    /**
     * Récupère un établissement par son ID
     * 
     * @param int $id L'ID de l'établissement à récupérer
     * @return Etablissement|null Retourne l'objet Etablissement ou null si non trouvé
     */
    public function getById(int $id): ?Etablissement {
        $stmt = $this->pdo->prepare("SELECT * FROM etablissement WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            return null;
        }
        
        return new Etablissement(
            (int)$row['id'],
            $row['nom'],
            $row['ville']
        );
    }

    /**
     * Récupère tous les établissements
     * 
     * @return array Tableau d'objets Etablissement
     */
    public function getAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM etablissement ORDER BY nom");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $etablissements = [];
        foreach ($rows as $row) {
            $etablissement = new Etablissement(
                (int)$row['id'],
                $row['nom'],
                $row['ville']
            );
            
            $etablissements[] = $etablissement;
        }

        return $etablissements;
    }

    /**
     * Enregistre un nouvel établissement
     * 
     * @param Etablissement $etablissement L'objet Etablissement à enregistrer
     * @return Etablissement|null L'objet Etablissement avec l'ID généré ou null en cas d'erreur
     */
    public function save(Etablissement $etablissement): ?Etablissement {
        $stmt = $this->pdo->prepare("
            INSERT INTO etablissement (nom, ville) 
            VALUES (:nom, :ville)
        ");

        $success = $stmt->execute([
            ':nom' => $etablissement->getNom(),
            ':ville' => $etablissement->getVille()
        ]);

        if ($success) {
            $etablissement->setId((int)$this->pdo->lastInsertId());
            return $etablissement;
        }

        return null;
    }

    /**
     * Met à jour un établissement existant
     * 
     * @param Etablissement $etablissement L'objet Etablissement à mettre à jour
     * @return Etablissement|null L'objet Etablissement mis à jour ou null en cas d'erreur
     */
    public function update(Etablissement $etablissement): ?Etablissement {
        $stmt = $this->pdo->prepare("
            UPDATE etablissement
            SET nom = :nom, 
                ville = :ville
            WHERE id = :id
        ");

        $success = $stmt->execute([
            ':id' => $etablissement->getId(),
            ':nom' => $etablissement->getNom(),
            ':ville' => $etablissement->getVille()
        ]);

        return $success ? $etablissement : null;
    }

    /**
     * Supprime un établissement par son ID
     * 
     * @param int $id L'ID de l'établissement à supprimer
     * @return bool True si la suppression a réussi, false si l'établissement a des professeurs ou en cas d'erreur
     */
    public function delete(int $id): bool {
        // Vérifier qu'aucun professeur n'est lié
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM professeur WHERE id_etab = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && $result['total'] > 0) {
            return false;
        }

        $stmt = $this->pdo->prepare("DELETE FROM etablissement WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

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
}

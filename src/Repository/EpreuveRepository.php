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


}

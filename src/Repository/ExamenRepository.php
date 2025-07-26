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
        $stmt = $this->pdo->prepare("SELECT * FROM examen WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            return null;
        }
        
        return new Examen(
            (int)$row['id'],
            $row['nom']
        );
    }
}

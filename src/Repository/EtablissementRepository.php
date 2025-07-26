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
            $row['id'],
            $row['nom'],
            $row['ville']
        );
    }
}

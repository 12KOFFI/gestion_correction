<?php

namespace App\Repository;

use PDO;
use App\Models\Professeur;
use Config\Database;

class ProfesseurRepository {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    /**
     * Récupère un professeur par son ID
     * 
     * @param int $id L'ID du professeur à récupérer
     * @return Professeur|null Retourne l'objet Professeur ou null si non trouvé
     */
    public function getById(int $id): ?Professeur {
        $stmt = $this->pdo->prepare("SELECT * FROM professeur WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            return null;
        }
        
        return new Professeur(
            $row['id'],
            $row['nom'],
            $row['prenom'],
            $row['grade'],
            $row['id_etab']
        );
    }
}
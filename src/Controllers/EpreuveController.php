<?php
namespace App\Controllers;

use App\Models\Epreuve;
use App\Repository\EpreuveRepository;
use Config\Database;
use PDO;

class EpreuveController {
    private $pdo;
    private $epreuveRepository;

    public function __construct() {
        $this->pdo = Database::getConnection();
        $this->epreuveRepository = new EpreuveRepository();
    }
    
    /**
     * Récupère une épreuve par son ID
     * 
     * @param int $id L'ID de l'épreuve à récupérer
     * @return Epreuve|null Retourne l'objet Epreuve ou null si non trouvé
     */
    public function getById(int $id): ?Epreuve {
        return $this->epreuveRepository->getById($id);
    }

    // 1. Lister toutes les épreuves
    public function index(): array {
        // Debug: Vérifier la connexion à la base de données
        if (!$this->pdo) {
            throw new \Exception("Erreur de connexion à la base de données");
        }

        // Debug: Vérifier si la table existe
        $tableExists = $this->pdo->query("SHOW TABLES LIKE 'epreuve'")->rowCount() > 0;
        if (!$tableExists) {
            throw new \Exception("La table 'epreuve' n'existe pas dans la base de données");
        }

        // Récupérer les épreuves
        $stmt = $this->pdo->query("SELECT * FROM epreuve");
        if ($stmt === false) {
            $error = $this->pdo->errorInfo();
            throw new \Exception("Erreur lors de la récupération des épreuves: " . ($error[2] ?? 'Erreur inconnue'));
        }
        
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Debug: Afficher le nombre d'épreuves trouvées
        error_log("Nombre d'épreuves trouvées: " . count($rows));

        $epreuves = [];
        foreach ($rows as $row) {
            $epreuves[] = new Epreuve(
                (int)$row['id'],
                $row['nom'],
                $row['type'],
                isset($row['id_examen']) ? (int)$row['id_examen'] : null
            );
        }

        return $epreuves;
    }

    // 2. Enregistrer une nouvelle épreuve
    public function new(Epreuve $epreuve): Epreuve {
        $stmt = $this->pdo->prepare("
            INSERT INTO epreuve (nom, type, id_examen) 
            VALUES (?, ?, ?)
        ");
        $stmt->execute([
            $epreuve->getNom(),
            $epreuve->getType(),
            $epreuve->getIdExamen()
        ]);

        $epreuve->setId((int)$this->pdo->lastInsertId());
        return $epreuve;
    }

    /**
     * Modifie une épreuve existants
     */
    public function edit(int $id, Epreuve $epreuve): bool {
        try {
            // Vérifier d'abord si l'épreuve existe
            $existingEpreuve = $this->getById($id);
            if (!$existingEpreuve) {
                error_log("Tentative de modification d'une épreuve inexistante (ID: $id)");
                return false;
            }
            
            // Préparer et exécuter la requête de mise à jour
            $stmt = $this->pdo->prepare("
                UPDATE epreuve 
                SET nom = ?, type = ?, id_examen = ? 
                WHERE id = ?
            ");
            
            $result = $stmt->execute([
                $epreuve->getNom(),
                $epreuve->getType(),
                $epreuve->getIdExamen(),
                $id
            ]);
            
            // Vérifier si la mise à jour a affecté des lignes
            return $result && $stmt->rowCount() > 0;
            
        } catch (\PDOException $e) {
            error_log("Erreur lors de la mise à jour de l'épreuve (ID: $id): " . $e->getMessage());
            return false;
        }
    }

    // 4. Supprimer une épreuve
    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM epreuve WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

<?php
namespace App\Controllers;

use Config\Database;
use App\Models\Correction;
use App\Repository\CorrectionRepository;
use PDO;

class CorrectionController {
    private $pdo;
    private $correctionRepository;

    public function __construct() {
        $this->pdo = Database::getConnection();
        $this->correctionRepository = new CorrectionRepository();
    }
    
    /**
     * Récupère une correction par son ID
     * 
     * @param int $id L'ID de la correction à récupérer
     * @return Correction|null Retourne l'objet Correction ou null si non trouvé
     */
    public function getById(int $id): ?Correction {
        return $this->correctionRepository->getById($id);
    }

    // 1. Lister toutes les corrections avec les informations des professeurs et des épreuves
    public function index(): array {
        $corrections = [];
        
        // Requête pour récupérer les corrections avec les noms des professeurs et des épreuves
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
                $row['id'],
                $row['id_professeur'],
                $row['id_epreuve'],
                $row['date'],
                $row['nbr_copie']
            );
            
            // Ajout des informations supplémentaires
            $correction->professeur_nom = $row['professeur_prenom'] . ' ' . $row['professeur_nom'];
            $correction->epreuve_nom = $row['epreuve_nom'];
            
            $corrections[] = $correction;
        }

        return $corrections;
    }

    // 2. Créer une nouvelle correction (retourne l'objet Correction créé)
    public function new(array $data): ?Correction {
        $stmt = $this->pdo->prepare("
            INSERT INTO correction (id_professeur, id_epreuve, date, nbr_copie) 
            VALUES (?, ?, ?, ?)
        ");

        $success = $stmt->execute([
            $data['id_professeur'],
            $data['id_epreuve'],
            $data['date'],
            $data['nbr_copie']
        ]);

        if ($success) {
            $id = (int)$this->pdo->lastInsertId();
            return new Correction(
                $id,
                $data['id_professeur'],
                $data['id_epreuve'],
                $data['date'],
                $data['nbr_copie']
            );
        }

        return null;
    }

    // 3. Modifier une correction existante (retourne l'objet Correction modifié)
    public function edit(int $id, array $data): ?Correction {
        $stmt = $this->pdo->prepare("
            UPDATE correction
            SET id_professeur = ?, id_epreuve = ?, date = ?, nbr_copie = ?
            WHERE id = ?
        ");

        $success = $stmt->execute([
            $data['id_professeur'],
            $data['id_epreuve'],
            $data['date'],
            $data['nbr_copie'],
            $id
        ]);

        if ($success) {
            return new Correction(
                $id,
                $data['id_professeur'],
                $data['id_epreuve'],
                $data['date'],
                $data['nbr_copie']
            );
        }

        return null;
    }

    // 4. Supprimer une correction (retourne true si succès, false sinon)
    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM correction WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

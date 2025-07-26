<?php
declare(strict_types=1);
namespace App\Controllers;

use Config\Database;
use App\Models\Etablissement;
use App\Repository\EtablissementRepository;
use PDO;

class EtablissementController {
    private $pdo;
    private $etablissementRepository;

    public function __construct() {
        $this->pdo = Database::getConnection();
        $this->etablissementRepository = new EtablissementRepository();
    }

    // 1. Lister tous les établissements
    public function index(): array {
        $stmt = $this->pdo->query("SELECT * FROM etablissement ORDER BY nom");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $etablissements = [];
        foreach ($rows as $row) {
            $etablissements[] = new Etablissement(
                $row['id'],
                $row['nom'],
                $row['ville']
            );
        }

        return $etablissements;
    }

    // 2. Enregistrer un établissement
    public function new(Etablissement $etablissement): Etablissement {
        $stmt = $this->pdo->prepare("INSERT INTO etablissement (nom, ville) VALUES (?, ?)");
        $stmt->execute([
            $etablissement->getNom(),
            $etablissement->getVille()
        ]);

        $etablissement->setId((int)$this->pdo->lastInsertId());
        return $etablissement;
    }

    // 3.1 Récupérer un établissement par son ID
    public function getById(int $id): ?Etablissement {
        return $this->etablissementRepository->getById($id);
    }

    // 3.2 Modifier un établissement
    public function edit(int $id, Etablissement $etablissement): ?Etablissement {
        $stmt = $this->pdo->prepare("UPDATE etablissement SET nom = ?, ville = ? WHERE id = ?");
        $stmt->execute([
            $etablissement->getNom(),
            $etablissement->getVille(),
            $id
        ]);
        return $etablissement;
    }

    // 4. Supprimer un établissement
    public function delete(int $id): bool {
        // Vérifier qu’aucun professeur n’est lié
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

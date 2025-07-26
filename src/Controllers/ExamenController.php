<?php

declare(strict_types=1);
namespace App\Controllers;

use Config\Database;
use App\Models\Examen;
use App\Repository\ExamenRepository;
use PDO;

class ExamenController {
    private $pdo;
    private $examenRepository;

    public function __construct() {
        $this->pdo = Database::getConnection();
        $this->examenRepository = new ExamenRepository();
    }

    // 1. Lister tous les examens
    public function index(): array {
        $examens = [];
        $stmt = $this->pdo->query("SELECT * FROM examen ORDER BY id DESC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            $examens[] = new Examen(
                (int)$row['id'],
                $row['nom']
            );
        }

        return $examens;
    }

    // 2. Créer un nouvel examen
    public function new(): ?Examen {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $examen = new Examen(
                null,
                $_POST['nom']
            );

            $stmt = $this->pdo->prepare("INSERT INTO examen (nom) VALUES (?)");
            $stmt->execute([$examen->getNom()]);

            $examen->setId((int)$this->pdo->lastInsertId());
            return $examen;
        }

        return null;
    }

    // 3.1 Récupérer un examen par son ID
    public function getById(int $id): ?Examen {
        return $this->examenRepository->getById($id);
    }

    // 3.2 Modifier un examen existant
    public function edit(): ?Examen {
        if (!isset($_GET['id'])) return null;

        $id = (int)$_GET['id'];

        // Si c'est une requête GET, on retourne l'examen existant
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->getById($id);
        }

        $examen = new Examen(
            $id,
            $_POST['nom']
        );

        $stmt = $this->pdo->prepare("UPDATE examen SET nom = ? WHERE id = ?");
        $stmt->execute([
            $examen->getNom(),
            $examen->getId()
        ]);

        return $examen;
    }

    // 4. Supprimer un examen
    public function delete(int $id): bool {
        error_log('Méthode delete appelée avec l\'ID: ' . $id);
        try {
            // Vérifier d'abord si l'examen existe
            $examen = $this->getById($id);
            if (!$examen) {
                error_log("Tentative de suppression d'un examen inexistant (ID: $id)");
                return false;
            }

            $stmt = $this->pdo->prepare("DELETE FROM examen WHERE id = ?");
            $result = $stmt->execute([$id]);
            
            if ($result && $stmt->rowCount() > 0) {
                error_log("Examen supprimé avec succès (ID: $id)");
                return true;
            }
            
            error_log("Échec de la suppression de l'examen (ID: $id)");
            return false;
            
        } catch (\PDOException $e) {
            error_log("Erreur lors de la suppression de l'examen (ID: $id): " . $e->getMessage());
            return false;
        }
    }
}

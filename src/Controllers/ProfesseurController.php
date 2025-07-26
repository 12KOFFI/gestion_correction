<?php
declare(strict_types=1);
namespace App\Controllers;

use Config\Database;
use App\Models\Professeur;
use App\Repository\ProfesseurRepository;
use PDO;

class ProfesseurController {
    private $pdo;
    private $professeurRepository;

    public function __construct() {
        $this->pdo = Database::getConnection();
        $this->professeurRepository = new ProfesseurRepository();
    }

    // 1. Lister tous les professeurs (retourne tableau d’objets)
    public function index(): array {
        $professeurs = [];
        $stmt = $this->pdo->query("SELECT * FROM professeur ORDER BY nom, prenom");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            $professeurs[] = new Professeur(
                $row['id'],
                $row['nom'],
                $row['prenom'],
                $row['grade'],
                $row['id_etab']
            );
        }

        return $professeurs;
    }

    // 2. Enregistrer un nouveau professeur (retourne l’objet créé)
    public function new(): ?Professeur {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $professeur = new Professeur(
                null,
                $_POST['nom'] ?? '',
                $_POST['prenom'] ?? '',
                $_POST['grade'] ?? '',
                $_POST['etablissement_id'] ?? null
            );

            $stmt = $this->pdo->prepare("
                INSERT INTO professeur (nom, prenom, grade, id_etab) 
                VALUES (?, ?, ?, ?)
            ");

            $success = $stmt->execute([
                $professeur->getNom(),
                $professeur->getPrenom(),
                $professeur->getGrade(),
                $professeur->getEtablissementId()
            ]);

            if ($success) {
                $professeur->setId((int)$this->pdo->lastInsertId());
                return $professeur;
            }
        }

        return null;
    }

    // 3.1 Récupérer un professeur par son ID
    public function getById(int $id): ?Professeur {
        return $this->professeurRepository->getById($id);
    }

    // 3.2 Modifier un professeur
    public function edit(int $id, Professeur $professeur): ?Professeur {
        $stmt = $this->pdo->prepare("
            UPDATE professeur 
            SET nom = ?, prenom = ?, grade = ?, id_etab = ?
            WHERE id = ?
        ");
        
        $stmt->execute([
            $professeur->getNom(),
            $professeur->getPrenom(),
            $professeur->getGrade(),
            $professeur->getEtablissementId(),
            $id
        ]);

        return $professeur;
    }

    // 4. Supprimer un professeur (retourne true ou false)
    public function delete(): bool {
        if (!isset($_POST['id'])) return false;
        
        $id = (int)$_POST['id'];
        
        try {
            // Désactiver temporairement la vérification des clés étrangères
            $this->pdo->exec('SET FOREIGN_KEY_CHECKS=0');
            
            // Supprimer d'abord les corrections associées à ce professeur
            $stmt = $this->pdo->prepare("DELETE FROM correction WHERE id_professeur = ?");
            $stmt->execute([$id]);
            
            // Ensuite supprimer le professeur
            $stmt = $this->pdo->prepare("DELETE FROM professeur WHERE id = ?");
            $success = $stmt->execute([$id]);
            
            // Réactiver la vérification des clés étrangères
            $this->pdo->exec('SET FOREIGN_KEY_CHECKS=1');
            
            return $success;
            
        } catch (\PDOException $e) {
            // En cas d'erreur, s'assurer que la vérification des clés étrangères est réactivée
            $this->pdo->exec('SET FOREIGN_KEY_CHECKS=1');
            error_log('Erreur lors de la suppression du professeur: ' . $e->getMessage());
            return false;
        }
    }
}

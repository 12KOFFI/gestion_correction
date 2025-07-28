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
     * Récupère un professeur par son ID avec les détails de l'établissement
     * 
     * @param int $id L'ID du professeur à récupérer
     * @return Professeur|null Retourne l'objet Professeur ou null si non trouvé
     */
    public function getById(int $id): ?Professeur {
        $query = "
            SELECT p.*, e.nom as etablissement_nom, e.ville as etablissement_ville,
                   COUNT(c.id) as nb_corrections
            FROM professeur p
            LEFT JOIN etablissement e ON p.id_etab = e.id
            LEFT JOIN correction c ON p.id = c.id_professeur
            WHERE p.id = ?
            GROUP BY p.id
        ";
        
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            return null;
        }
        
        $professeur = new Professeur(
            (int)$row['id'],
            $row['nom'],
            $row['prenom'],
            $row['grade'],
            $row['id_etab'] ? (int)$row['id_etab'] : null
        );
        
        // Ajout des informations supplémentaires
        $professeur->setEtablissementNom($row['etablissement_nom'] ?? null);
        $professeur->setEtablissementVille($row['etablissement_ville'] ?? null);
        $professeur->setNbCorrections((int)$row['nb_corrections']);
        
        return $professeur;
    }
    
    /**
     * Récupère tous les professeurs avec les détails des établissements
     * 
     * @return array Tableau d'objets Professeur avec les détails
     */
    public function getAll(): array {
        $query = "
            SELECT p.*, e.nom as etablissement_nom, e.ville as etablissement_ville,
                   COUNT(c.id) as nb_corrections
            FROM professeur p
            LEFT JOIN etablissement e ON p.id_etab = e.id
            LEFT JOIN correction c ON p.id = c.id_professeur
            GROUP BY p.id
            ORDER BY p.nom, p.prenom
        ";
        
        $stmt = $this->pdo->query($query);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $professeurs = [];
        foreach ($rows as $row) {
            $professeur = new Professeur(
                (int)$row['id'],
                $row['nom'],
                $row['prenom'],
                $row['grade'],
                $row['id_etab'] ? (int)$row['id_etab'] : null
            );
            
            // Ajout des informations supplémentaires
            $professeur->setEtablissementNom($row['etablissement_nom'] ?? null);
            $professeur->setEtablissementVille($row['etablissement_ville'] ?? null);
            $professeur->setNbCorrections((int)$row['nb_corrections']);
            
            $professeurs[] = $professeur;
        }
        
        return $professeurs;
    }
    
    /**
     * Enregistre un nouveau professeur
     * 
     * @param Professeur $professeur L'objet Professeur à enregistrer
     * @return Professeur|null L'objet Professeur avec l'ID généré ou null en cas d'erreur
     */
    public function save(Professeur $professeur): ?Professeur {
        $stmt = $this->pdo->prepare("
            INSERT INTO professeur (nom, prenom, grade, id_etab) 
            VALUES (:nom, :prenom, :grade, :id_etab)
        ");

        $success = $stmt->execute([
            ':nom' => $professeur->getNom(),
            ':prenom' => $professeur->getPrenom(),
            ':grade' => $professeur->getGrade(),
            ':id_etab' => $professeur->getEtablissementId()
        ]);

        if ($success) {
            $professeur->setId((int)$this->pdo->lastInsertId());
            return $professeur;
        }

        return null;
    }
    
    /**
     * Met à jour un professeur existant
     * 
     * @param Professeur $professeur L'objet Professeur à mettre à jour
     * @return Professeur|null L'objet Professeur mis à jour ou null en cas d'erreur
     */
    public function update(Professeur $professeur): ?Professeur {
        $stmt = $this->pdo->prepare("
            UPDATE professeur
            SET nom = :nom, 
                prenom = :prenom,
                grade = :grade,
                id_etab = :id_etab
            WHERE id = :id
        ");

        $success = $stmt->execute([
            ':id' => $professeur->getId(),
            ':nom' => $professeur->getNom(),
            ':prenom' => $professeur->getPrenom(),
            ':grade' => $professeur->getGrade(),
            ':id_etab' => $professeur->getEtablissementId()
        ]);

        return $success ? $professeur : null;
    }
    
    /**
     * Supprime un professeur par son ID
     * 
     * @param int $id L'ID du professeur à supprimer
     * @return bool True si la suppression a réussi, false sinon
     */
    public function delete(int $id): bool {
        try {
            $this->pdo->beginTransaction();
            
            // Désactiver temporairement la vérification des clés étrangères
            $this->pdo->exec('SET FOREIGN_KEY_CHECKS=0');
            
            // Supprimer d'abord les corrections associées à ce professeur
            $stmt = $this->pdo->prepare("DELETE FROM correction WHERE id_professeur = ?");
            $stmt->execute([$id]);
            
            // Puis supprimer le professeur
            $stmt = $this->pdo->prepare("DELETE FROM professeur WHERE id = ?");
            $result = $stmt->execute([$id]);
            
            // Réactiver la vérification des clés étrangères
            $this->pdo->exec('SET FOREIGN_KEY_CHECKS=1');
            
            $this->pdo->commit();
            return $result;
            
        } catch (\PDOException $e) {
            // En cas d'erreur, s'assurer que la vérification des clés étrangères est réactivée
            $this->pdo->rollBack();
            $this->pdo->exec('SET FOREIGN_KEY_CHECKS=1');
            error_log("Erreur lors de la suppression du professeur (ID: $id): " . $e->getMessage());
            return false;
        }
    }
}
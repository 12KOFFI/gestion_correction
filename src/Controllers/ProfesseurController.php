<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Models\Professeur;
use App\Repository\ProfesseurRepository;

class ProfesseurController {
    private $professeurRepository;

    public function __construct() {
        $this->professeurRepository = new ProfesseurRepository();
    }

    /**
     * Liste tous les professeurs avec leurs informations d'établissement
     * 
     * @return array Tableau d'objets Professeur
     */
    public function index(): array {
        return $this->professeurRepository->getAll();
    }

    /**
     * Crée un nouveau professeur
     * 
     * @param array $data Les données du professeur à créer
     * @return Professeur|null Le professeur créé ou null en cas d'échec
     * @throws \Exception Si les données sont invalides
     */
    public function new(array $data): ?Professeur {
        // Validation des champs obligatoires
        if (empty($data['nom']) || empty($data['prenom'])) {
            throw new \Exception('Le nom et le prénom sont obligatoires');
        }

        $professeur = new Professeur(
            null,
            $data['nom'],
            $data['prenom'],
            $data['grade'] ?? '',
            !empty($data['etablissement_id']) ? (int)$data['etablissement_id'] : null
        );

        return $this->professeurRepository->save($professeur);
    }

    // 3.1 Récupérer un professeur par son ID
    public function getById(int $id): ?Professeur {
        return $this->professeurRepository->getById($id);
    }

    /**
     * Modifie un professeur existant
     * 
     * @param int $id ID du professeur à modifier
     * @param array $data Les nouvelles données du professeur
     * @return Professeur|null Le professeur modifié ou null en cas d'échec
     * @throws \Exception Si les données sont invalides
     */
    public function edit(int $id, array $data): ?Professeur {
        // Vérifier d'abord si le professeur existe
        $existingProfesseur = $this->professeurRepository->getById($id);
        
        if (!$existingProfesseur) {
            error_log("Tentative de mise à jour d'un professeur inexistant (ID: $id)");
            return null;
        }
        
        // Validation des champs obligatoires
        if (empty($data['nom']) || empty($data['prenom'])) {
            throw new \Exception('Le nom et le prénom sont obligatoires');
        }
        
        // Mettre à jour les propriétés du professeur existant
        $existingProfesseur->setNom($data['nom']);
        $existingProfesseur->setPrenom($data['prenom']);
        $existingProfesseur->setGrade($data['grade'] ?? '');
        $existingProfesseur->setEtablissementId(!empty($data['etablissement_id']) ? (int)$data['etablissement_id'] : null);
        
        // Utiliser le repository pour la mise à jour
        return $this->professeurRepository->update($existingProfesseur);
    }

    /**
     * Supprime un professeur et ses corrections associées
     * 
     * @param int $id ID du professeur à supprimer
     * @return bool True si la suppression a réussi, false sinon
     */
    public function delete(int $id): bool {
        error_log('Tentative de suppression du professeur avec l\'ID: ' . $id);
        
        try {
            $result = $this->professeurRepository->delete($id);
            
            if ($result) {
                error_log("Professeur supprimé avec succès (ID: $id)");
            } else {
                error_log("Échec de la suppression du professeur (ID: $id)");
            }
            
            return $result;
        } catch (\PDOException $e) {
            error_log("Erreur lors de la suppression du professeur (ID: $id): " . $e->getMessage());
            return false;
        }
    }
}

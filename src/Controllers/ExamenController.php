<?php

declare(strict_types=1);
namespace App\Controllers;

use App\Models\Examen;
use App\Repository\ExamenRepository;

class ExamenController {
    private $examenRepository;

    public function __construct() {
        $this->examenRepository = new ExamenRepository();
    }

    /**
     * Récupère tous les examens
     * 
     * @return array Tableau d'objets Examen
     */
    public function index(): array {
        return $this->examenRepository->getAll();
    }

    /**
     * Crée un nouvel examen
     * 
     * @param array $data Les données de l'examen à créer
     * @return Examen|null L'examen créé ou null en cas d'échec
     * @throws \Exception Si les données sont invalides
     */
    public function new(array $data): ?Examen {
        if (empty($data['nom'])) {
            throw new \Exception('Le nom de l\'examen est obligatoire');
        }

        $examen = new Examen(
            null,
            $data['nom']
        );

        return $this->examenRepository->save($examen);
    }

    // 3.1 Récupérer un examen par son ID
    public function getById(int $id): ?Examen {
        return $this->examenRepository->getById($id);
    }

    /**
     * Affiche le formulaire de modification d'un examen (GET)
     * 
     * @param int $id ID de l'examen à modifier
     * @return Examen|null L'examen à modifier ou null si non trouvé
     */
    public function editForm(int $id): ?Examen {
        return $this->getById($id);
    }
    
    /**
     * Traite la soumission du formulaire de modification d'un examen (POST)
     * 
     * @param int $id ID de l'examen à modifier
     * @param array $data Les données de l'examen à mettre à jour
     * @return Examen|null L'examen modifié ou null en cas d'échec
     */
    public function edit(int $id, array $data): ?Examen {
        // Vérifier d'abord si l'examen existe
        $existingExamen = $this->examenRepository->getById($id);
        
        if (!$existingExamen) {
            return null;
        }
        
        // Mettre à jour les propriétés de l'examen existant
        if (isset($data['nom'])) {
            $existingExamen->setNom($data['nom']);
        }
        
        // Sauvegarder les modifications
        return $this->examenRepository->save($existingExamen);
    }

    /**
     * Supprime un examen
     * 
     * @param int $id ID de l'examen à supprimer
     * @return bool True si la suppression a réussi, false sinon
     */
    public function delete(int $id): bool {
        error_log('Tentative de suppression de l\'examen avec l\'ID: ' . $id);
        
        try {
            $result = $this->examenRepository->delete($id);
            
            if ($result) {
                error_log("Examen supprimé avec succès (ID: $id)");
            } else {
                error_log("Échec de la suppression de l'examen (ID: $id)");
            }
            
            return $result;
        } catch (\PDOException $e) {
            error_log("Erreur lors de la suppression de l'examen (ID: $id): " . $e->getMessage());
            return false;
        }
    }
}

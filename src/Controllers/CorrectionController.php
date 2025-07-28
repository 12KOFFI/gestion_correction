<?php
declare(strict_types=1); // Indique que le code doit être typé strictement
namespace App\Controllers;
use App\Models\Correction;
use App\Repository\CorrectionRepository;


class CorrectionController {
    private $correctionRepository;
    
    public function __construct() {
        $this->correctionRepository = new CorrectionRepository();
    }
    
    /**
     * Récupère une correction par son ID
     */
    public function getById(int $id): ?Correction {
        return $this->correctionRepository->getById($id);
    }

    // 1. Lister toutes les corrections avec les informations des professeurs et des épreuves
    public function index(): array {
        return $this->correctionRepository->getAll();
    }

    // 2. Créer une nouvelle correction (retourne l'objet Correction créé)
    public function new(array $data): ?Correction {
        $correction = new Correction(
            null,
            $data['id_professeur'],
            $data['id_epreuve'],
            $data['date'],
            $data['nbr_copie']
        );
        
        return $this->correctionRepository->save($correction);
    }

    /**
     * Modifie une correction existante
     * 
     * @param int $id ID de la correction à modifier
     * @param array $data Données de la correction à mettre à jour
     * @return Correction|null L'objet Correction modifié ou null si non trouvé
     */
    public function edit(int $id, array $data): ?Correction {
        // Vérifier d'abord si la correction existe
        $existingCorrection = $this->correctionRepository->getById($id);
        
        if (!$existingCorrection) {
            error_log("Tentative de mise à jour d'une correction inexistante (ID: $id)");
            return null;
        }
        
        // Mettre à jour les propriétés de la correction existante
        if (isset($data['id_professeur'])) {
            $existingCorrection->setIdProfesseur((int)$data['id_professeur']);
        }
        if (isset($data['id_epreuve'])) {
            $existingCorrection->setIdEpreuve((int)$data['id_epreuve']);
        }
        if (isset($data['date'])) {
            $existingCorrection->setDate($data['date']);
        }
        if (isset($data['nbr_copie'])) {
            $existingCorrection->setNbrCopie((int)$data['nbr_copie']);
        }
        
        // Mettre à jour la correction dans le repository
        return $this->correctionRepository->update($existingCorrection);
    }

    // 4. Supprimer une correction (retourne true si succès, false sinon)
    public function delete(int $id): bool {
        return $this->correctionRepository->delete($id);
    }
}

<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Models\Epreuve;
use App\Repository\EpreuveRepository;

class EpreuveController {
    private $epreuveRepository;

    public function __construct() {
        $this->epreuveRepository = new EpreuveRepository();
    }
    
    /**
     * Récupère une épreuve par son ID
     */
    public function getById(int $id): ?Epreuve {
        return $this->epreuveRepository->getById($id);
    }

    /**
     * Récupère toutes les épreuves
     * 
     * @return array Tableau d'objets Epreuve
     */
    public function index(): array {
        return $this->epreuveRepository->getAll();
    }

    /**
     * Enregistre une nouvelle épreuve
     * 
     * @param array $data Les données de l'épreuve à enregistrer
     * @return Epreuve L'épreuve enregistrée avec son ID
     * @throws \Exception Si l'enregistrement échoue
     */
    public function new(array $data): Epreuve {
        // Créer une nouvelle instance d'Epreuve avec les données fournies
        $epreuve = new Epreuve(
            null,
            $data['nom'] ?? '',
            $data['type'] ?? '',
            $data['id_examen'] ?? null
        );
        
        $savedEpreuve = $this->epreuveRepository->save($epreuve);
        if ($savedEpreuve === null) {
            throw new \Exception("Échec de l'enregistrement de l'épreuve");
        }
        return $savedEpreuve;
    }

    /**
     * Modifie une épreuve existante
     * 
     * @param int $id ID de l'épreuve à modifier
     * @param array $data Les nouvelles données de l'épreuve
     * @return Epreuve|null L'épreuve modifiée ou null en cas d'échec
     */
    public function edit(int $id, array $data): ?Epreuve {
        try {
            // Vérifier d'abord si l'épreuve existe
            $existingEpreuve = $this->epreuveRepository->getById($id);
            
            if (!$existingEpreuve) {
                error_log("Tentative de mise à jour d'une épreuve inexistante (ID: $id)");
                return null;
            }
            
            // Mettre à jour les propriétés de l'épreuve existante
            if (isset($data['nom'])) {
                $existingEpreuve->setNom($data['nom']);
            }
            if (isset($data['type'])) {
                $existingEpreuve->setType($data['type']);
            }
            if (isset($data['id_examen'])) {
                $existingEpreuve->setIdExamen($data['id_examen']);
            }
            
            // Utiliser le repository pour la mise à jour
            return $this->epreuveRepository->update($existingEpreuve);
            
        } catch (\Exception $e) {
            error_log("Erreur lors de la mise à jour de l'épreuve (ID: $id): " . $e->getMessage());
            return null;
        }
    }

    /**
     * Supprime une épreuve
     * 
     * @param int $id ID de l'épreuve à supprimer
     * @return bool True si la suppression a réussi, false sinon
     */
    public function delete(int $id): bool {
        return $this->epreuveRepository->delete($id);
    }
}

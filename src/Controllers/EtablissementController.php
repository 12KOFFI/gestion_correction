<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Models\Etablissement;
use App\Repository\EtablissementRepository;

class EtablissementController {
    private $etablissementRepository;

    public function __construct() {
        $this->etablissementRepository = new EtablissementRepository();
    }

    // 1. Lister tous les établissements
    public function index(): array {
        return $this->etablissementRepository->getAll();
    }

    /**
     * Enregistre un nouvel établissement
     * 
     * @param array $data Les données de l'établissement à enregistrer
     * @return Etablissement L'établissement enregistré avec son ID
     * @throws \Exception Si l'enregistrement échoue
     */
    public function new(array $data): Etablissement {
        // Créer une nouvelle instance d'Etablissement avec les données fournies
        $etablissement = new Etablissement(
            null,
            $data['nom'] ?? '',
            $data['ville'] ?? ''
        );
        
        $savedEtablissement = $this->etablissementRepository->save($etablissement);
        if ($savedEtablissement === null) {
            throw new \Exception("Échec de l'enregistrement de l'établissement");
        }
        return $savedEtablissement;
    }

    // 3.1 Récupérer un établissement par son ID
    public function getById(int $id): ?Etablissement {
        return $this->etablissementRepository->getById($id);
    }

    /**
     * Modifie un établissement existant
     * 
     * @param int $id ID de l'établissement à modifier
     * @param array $data Les nouvelles données de l'établissement
     * @return Etablissement|null L'établissement mis à jour ou null en cas d'échec
     */
    public function edit(int $id, array $data): ?Etablissement {
        // Vérifier d'abord si l'établissement existe
        $existingEtablissement = $this->etablissementRepository->getById($id);
        
        if (!$existingEtablissement) {
            error_log("Tentative de mise à jour d'un établissement inexistant (ID: $id)");
            return null;
        }
        
        // Mettre à jour les propriétés de l'établissement existant
        if (isset($data['nom'])) {
            $existingEtablissement->setNom($data['nom']);
        }
        if (isset($data['ville'])) {
            $existingEtablissement->setVille($data['ville']);
        }
        
        // Utiliser le repository pour la mise à jour
        return $this->etablissementRepository->update($existingEtablissement);
    }

    /**
     * Supprime un établissement
     * 
     * @param int $id ID de l'établissement à supprimer
     * @return bool True si la suppression a réussi, false sinon
     */
    public function delete(int $id): bool {
        return $this->etablissementRepository->delete($id);
    }
}

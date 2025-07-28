<?php
namespace App\Models;

use Config\BaseModel;

class Etablissement extends BaseModel {
    private ?int $id;
    private string $nom;
    private string $ville;
    private int $nb_professeurs = 0;

    public function __construct(
        ?int $id = null,
        string $nom = '',
        string $ville = ''
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->ville = $ville;
    }

    // Getters
    public function getId(): ?int {
        return $this->id;
    }

    public function getNom(): string {
        return $this->nom;
    }

    public function getVille(): string {
        return $this->ville;
    }

    // Setters
    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function setNom(string $nom): void {
        $this->nom = $nom;
    }

    public function setVille(string $ville): void {
        $this->ville = $ville;
    }
    
    // Getter et setter pour nb_professeurs
    public function getNbProfesseurs(): int {
        return $this->nb_professeurs;
    }
    
    public function setNbProfesseurs(int $nb_professeurs): void {
        $this->nb_professeurs = $nb_professeurs;
    }
}

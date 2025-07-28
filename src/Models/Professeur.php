<?php

namespace App\Models;

use Config\BaseModel;

class Professeur extends BaseModel {
    private ?int $id;
    private string $nom;
    private string $prenom;
    private string $grade;
    private ?int $id_etab;
    
    // Propriétés pour les jointures
    private ?string $etablissement_nom = null;
    private ?string $etablissement_ville = null;
    private int $nb_corrections = 0;

    public function __construct(
        ?int $id = null,
        string $nom = '',
        string $prenom = '',
        string $grade = '',
        ?int $id_etab = null
) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->grade = $grade;
        $this->id_etab = $id_etab;
    }

    // Getters
    public function getId(): ?int {
        return $this->id;
    }

    public function getNom(): string {
        return $this->nom;
    }

    public function getPrenom(): string {
        return $this->prenom;
    }

    public function getGrade(): string {
        return $this->grade;
    }
    
    public function getEtablissementId(): ?int {
        return $this->id_etab;
    }

    // Setters
    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function setNom(string $nom): void {
        $this->nom = $nom;
    }

    public function setPrenom(string $prenom): void {
        $this->prenom = $prenom;
    }

    public function setGrade(string $grade): void {
        $this->grade = $grade;
    }
    
    public function setEtablissementId(?int $id_etab): void {
        $this->id_etab = $id_etab;
    }
    
    // Getters pour les propriétés de jointure
    public function getEtablissementNom(): ?string {
        return $this->etablissement_nom;
    }
    
    public function setEtablissementNom(?string $etablissement_nom): void {
        $this->etablissement_nom = $etablissement_nom;
    }
    
    public function getEtablissementVille(): ?string {
        return $this->etablissement_ville;
    }
    
    public function setEtablissementVille(?string $etablissement_ville): void {
        $this->etablissement_ville = $etablissement_ville;
    }
    
    public function getNbCorrections(): int {
        return $this->nb_corrections;
    }
    
    public function setNbCorrections(int $nb_corrections): void {
        $this->nb_corrections = $nb_corrections;
    }
}


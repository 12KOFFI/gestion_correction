<?php

namespace App\Models;

use Config\BaseModel;

class Professeur extends BaseModel {
    private ?int $id;
    private string $nom;
    private string $prenom;
    private string $grade;
    private ?int $id_etab;

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
}


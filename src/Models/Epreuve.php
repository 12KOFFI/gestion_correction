<?php

namespace App\Models;

use Config\BaseModel;

class Epreuve extends BaseModel {
    private ?int $id;
    private string $nom;
    private string $type;
    private ?int $id_examen;
    
    // Propriété pour la jointure avec Examen
    private ?string $examen_nom = null;

    public function __construct(
        ?int $id = null,
        string $nom = '',
        string $type = '',
        ?int $id_examen = null
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->type = $type;
        $this->id_examen = $id_examen;
    }

    // Getters
    public function getId(): ?int { 
        return $this->id; 
    }
    
    public function getNom(): string { 
        return $this->nom; 
    }
    
    public function getType(): string { 
        return $this->type; 
    }
    
    public function getIdExamen(): ?int { 
        return $this->id_examen; 
    }

    // Setters
    public function setId(?int $id): void { 
        $this->id = $id; 
    }
    
    public function setNom(string $nom): void { 
        $this->nom = $nom; 
    }
    
    public function setType(string $type): void { 
        $this->type = $type; 
    }
    
    public function setIdExamen(?int $id_examen): void { 
        $this->id_examen = $id_examen; 
    }
    
    // Getter et setter pour examen_nom
    public function getExamenNom(): ?string {
        return $this->examen_nom;
    }
    
    public function setExamenNom(?string $examen_nom): void {
        $this->examen_nom = $examen_nom;
    }


}
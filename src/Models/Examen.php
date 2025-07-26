<?php
namespace App\Models;

use Config\BaseModel;

class Examen extends BaseModel 
{
    private ?int $id;
    private string $nom;

    public function __construct(
        ?int $id = null,
        string $nom = ''
    ) {
        $this->id = $id;
        $this->nom = $nom;
    }

    // Getters
    public function getId(): ?int {
        return $this->id;
    }

    public function getNom(): string {
        return $this->nom;
    }

    // Setters
    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function setNom(string $nom): void {
        $this->nom = $nom;
    }
}

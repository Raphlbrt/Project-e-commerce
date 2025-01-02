<?php
namespace App\MDS\Modele\DataObject;

class Ville extends AbstractDataObject{
    private ?int $id;
    private string $nom;
    private string $departement;
    private int $codePostal;

    public function __construct(
        ?int $id,
        string $nom,
        string $departement,
        int $codePostal
    )
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->departement = $departement;
        $this->codePostal = $codePostal;
    }

    // Getters et setters
    public function getId(): ?int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getNom(): string {
        return $this->nom;
    }

    public function setNom(string $nom): void {
        $this->nom = $nom;
    }

    public function getDepartement(): string {
        return $this->departement;
    }

    public function setDepartement(string $departement): void {
        $this->departement = $departement;
    }

    public function getCodePostal(): int {
        return $this->codePostal;
    }

    public function setCodePostal(int $codePostal): void {
        $this->codePostal = $codePostal;
    }
}

<?php
namespace App\MDS\Modele\DataObject;

class Produit extends AbstractDataObject {
    private ?int $idProduit;
    private string $nomProduit;
    private string $description;
    private float $prixProduit;
    private int $quantiteStock;
    private int $niveauReapprovisionnement;
    private int $numeroFournisseur;
    private string $cheminImage;

    public function __construct(
        ?int $idProduit,
        string $nomProduit,
        string $description,
        float $prixProduit,
        int $quantiteStock,
        int $niveauReapprovisionnement,
        int $numeroFournisseur,
        string $cheminImage
    ) {
        $this->idProduit = $idProduit;
        $this->nomProduit = $nomProduit;
        $this->description = $description;
        $this->prixProduit = $prixProduit;
        $this->quantiteStock = $quantiteStock;
        $this->niveauReapprovisionnement = $niveauReapprovisionnement;
        $this->numeroFournisseur = $numeroFournisseur;
        $this->cheminImage = $cheminImage;
    }

    // Getters
    public function getIdProduit(): ?int {
        return $this->idProduit;
    }


    public function getNomProduit(): string {
        return $this->nomProduit;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getPrixProduit(): float {
        return $this->prixProduit;
    }

    public function getQuantiteStock(): int {
        return $this->quantiteStock;
    }

    public function getNiveauReapprovisionnement(): int {
        return $this->niveauReapprovisionnement;
    }

    public function getNumeroFournisseur(): int {
        return $this->numeroFournisseur;
    }

    public function getCheminImage(): string {
        return $this->cheminImage;
    }

    // Setters
    public function setIdProduit(?int $idProduit): void {
        $this->idProduit = $idProduit;
    }

    public function setNomProduit(string $nomProduit): void {
        $this->nomProduit = $nomProduit;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function setPrixProduit(float $prixProduit): void {
        $this->prixProduit = $prixProduit;
    }

    public function setQuantiteStock(int $quantiteStock): void {
        $this->quantiteStock = $quantiteStock;
    }

    public function setNiveauReapprovisionnement(int $niveauReapprovisionnement): void {
        $this->niveauReapprovisionnement = $niveauReapprovisionnement;
    }

    public function setNumeroFournisseur(int $numeroFournisseur): void {
        $this->numeroFournisseur = $numeroFournisseur;
    }

    public function setCheminImage(string $cheminImage): void {
        $this->cheminImage = $cheminImage;
    }
}

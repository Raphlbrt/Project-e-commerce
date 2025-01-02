<?php
namespace App\MDS\Modele\DataObject;

class Fournisseur extends AbstractDataObject {
    private ?int $numFournisseur;
    private string $societe;
    private string $adresse;
    private int $tel;
    private string $mail;
    private int $idVille;

    public function __construct(
        ?int $numFournisseur, string $societe, string $adresse,
        int $tel, string $mail, int $idVille
    ) {
        $this->numFournisseur = $numFournisseur;
        $this->societe = $societe;
        $this->adresse = $adresse;
        $this->tel = $tel;
        $this->mail = $mail;
        $this->idVille = $idVille;
    }

    // Getters et setters
    public function getNumFournisseur(): ?int {
        return $this->numFournisseur;
    }
    public function setNumFournisseur(int $numFournisseur): void { $this->numFournisseur = $numFournisseur; }

    public function getSociete(): string { return $this->societe; }
    public function setSociete(string $societe): void { $this->societe = $societe; }

    public function getAdresse(): string { return $this->adresse; }
    public function setAdresse(string $adresse): void { $this->adresse = $adresse; }

    public function getTel(): int { return $this->tel; }
    public function setTel(int $tel): void { $this->tel = $tel; }

    public function getMail(): string { return $this->mail; }
    public function setMail(string $mail): void { $this->mail = $mail; }

    public function getIdVille(): int { return $this->idVille; }
    public function setIdVille(int $idVille): void { $this->idVille = $idVille; }
}

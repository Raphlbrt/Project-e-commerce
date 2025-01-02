<?php
namespace App\MDS\Modele\DataObject;

class Client extends AbstractDataObject {
    private string $login;
    private string $mdp;
    private string $nom;
    private string $prenom;
    private ?string $email;
    private ?string $emailAValider;
    private ?string $nonce;
    private ?string $tel;
    private string $adresse;
    private Ville $Ville;

    public function __construct(string $login, string $mdp, string $nom, string $prenom, ?string $email,?string $emailAValider,?string $nonce, ?int $tel, string $adresse, Ville $Ville)
    {
        $this->login = $login;
        $this->mdp = $mdp;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->emailAValider = $emailAValider;
        $this->nonce = $nonce;
        $this->tel = $tel;
        $this->adresse = $adresse;
        $this->Ville = $Ville;
    }
    public function getEmailAValider(): ?string {
        return $this->emailAValider;
    }

    public function setEmailAValider(?string $emailAValider): void {
        $this->emailAValider = $emailAValider;
    }

    public function getNonce(): ?string {
        return $this->nonce;
    }

    public function setNonce(?string $nonce): void {
        $this->nonce = $nonce;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function getMdp(): string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): void
    {
        $this->mdp = $mdp;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getTel(): int
    {
        return $this->tel;
    }

    public function setTel(int $tel): void
    {
        $this->tel = $tel;
    }

    public function getAdresse(): string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): void
    {
        $this->adresse = $adresse;
    }

    public function getVille(): Ville
    {
        return $this->Ville;
    }

    public function setVille(Ville $Ville): void
    {
        $this->Ville = $Ville;
    }
}
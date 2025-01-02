<?php
namespace App\MDS\Modele\DataObject;

class Panier extends AbstractDataObject
{
    private ?int $id;
    private string $loginClient;
    private int $idProduit;
    private int $quantite;

    public function __construct(?int $id, string $loginClient, int $idProduit, int $quantite)
    {
        $this->id = $id;
        $this->loginClient = $loginClient;
        $this->idProduit = $idProduit;
        $this->quantite = $quantite;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLoginClient(): string
    {
        return $this->loginClient;
    }

    public function getIdProduit(): int
    {
        return $this->idProduit;
    }

    public function getQuantite(): int
    {
        return $this->quantite;
    }
}

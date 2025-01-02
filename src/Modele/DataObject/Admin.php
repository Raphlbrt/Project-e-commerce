<?php
namespace App\MDS\Modele\DataObject;

class Admin extends AbstractDataObject {
    private string $loginAdmin;
    private string $mdp;

    public function __construct(string $loginAdmin, string $mdp) {
        $this->loginAdmin = $loginAdmin;
        $this->mdp = $mdp;
    }

    public function getLoginAdmin(): string {
        return $this->loginAdmin;
    }

    public function getMdp(): string {
        return $this->mdp;
    }
}

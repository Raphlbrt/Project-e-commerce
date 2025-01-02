<?php

namespace App\MDS\Controleur;

use App\MDS\Lib\MotDePasse;
use App\MDS\Modele\HTTP\Session;
use App\MDS\Modele\Repository;
use App\MDS\Modele\Repository\ProduitRepository;

class ControleurGenerique
{
    protected static function afficherVue(array $parametres = []): void {
        extract($parametres);
        require_once "../vue/vueGenerale.php"; // Charge la vue
    }
    public static function afficherErreur(string $messageErreur = "") : void {
        if ($messageErreur == ""){
            $messageErreur = "Problème controleur inconue";
        }
        self::afficherVue(["titre" => "Erreur", "cheminCorpsVue" => "erreur.php", "messageErreur" => $messageErreur]);
    }
}
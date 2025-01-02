<?php

use App\MDS\Lib\Psr4AutoloaderClass;
use App\MDS\Controleur\ControleurGenerique;
use App\MDS\Controleur\ControleurProduit;
require_once __DIR__ . '/../src/Lib/Psr4AutoloaderClass.php';

// initialisation en activant l'affichage de débogage
$chargeurDeClasse = new Psr4AutoloaderClass(false);
$chargeurDeClasse->register();
// enregistrement d'une association "espace de nom" → "dossier"
$chargeurDeClasse->addNamespace('App\MDS','../src');
if (!isset($_REQUEST['controleur'])){
    $controleur = 'produit';
} else {
    $controleur = $_REQUEST['controleur'];
}

$nomDeClasseControleur = 'App\MDS\Controleur\Controleur'.ucfirst($controleur);
if (class_exists($nomDeClasseControleur)) {
    if (isset($_REQUEST['action'])){
        $action = $_REQUEST['action'];
        $methodes = get_class_methods($nomDeClasseControleur);
        if (in_array($action, $methodes)) {
            if($action == 'validerEmail'){
                $login = $_GET['login'] ?? null;
                $nonce = $_GET['nonce'] ?? null;
                $nomDeClasseControleur::validerEmail($login, $nonce);
            }else{
                $nomDeClasseControleur::$action();
            }
        } else {
            $nomDeClasseControleur::afficherErreur("Cette page n'existe pas");
        }
    }
    else {
        ControleurProduit::afficherAccueil();
    }
}
else {
    ControleurGenerique::afficherErreur("Ce controleur n'existe pas");
}
?>

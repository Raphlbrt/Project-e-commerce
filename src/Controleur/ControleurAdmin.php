<?php

namespace App\MDS\Controleur;

use App\MDS\Lib\ConnexionUtilisateur;
use App\MDS\Lib\MotDePasse;
use App\MDS\Modele\DataObject\Admin;
use App\MDS\Modele\DataObject\Client;
use App\MDS\Modele\HTTP\Session;
use App\MDS\Modele\Repository\AdminRepository;
use App\MDS\Modele\Repository\ClientRepository;
use App\MDS\Modele\Repository\VilleRepository;

class ControleurAdmin extends ControleurGenerique
{
    static function creerUtilisateur() : void
    {
        $villes = (new VilleRepository())->recuperer();
        self::afficherVue([
            "titre" => "Création d'un nouvel utilisateur",
            "cheminCorpsVue" => "inscription.php",
            "villes" => $villes
        ]);
    }

    static function inscrireClientouAdministrateur() : void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ConnexionUtilisateur::estAdministrateur()) {
            $login = trim($_POST['login'] ?? '');
            $mdp = trim($_POST['mdp'] ?? '');
            $mdpConfirmation = trim($_POST['mdp_confirmation'] ?? '');
            $nom = trim($_POST['nom'] ?? '');
            $prenom = trim($_POST['prenom'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $tel = trim($_POST['tel'] ?? '');
            $adresse = trim($_POST['adresse'] ?? '');
            $idVille = $_POST['idVille'] ?? '';
            if (empty($login) || empty($mdp) || empty($nom) || empty($prenom) || empty($email) || empty($adresse) || empty($idVille)) {
                Session::getInstance()->ajouterMessageFlash('erreur', "Veuillez renseigner tous les champs obligatoires");
                header('Location: controleurFrontal.php?controleur=admin&action=creerUtilisateur');
                exit();
            }
            $clientRepo = new ClientRepository();
            if ($clientRepo->verifierLoginExistant($login)){
                self::afficherErreur("Ce login est déjà utilisé.");
                exit;
            }
            if ($mdp !== $mdpConfirmation) {
                Session::getInstance()->ajouterMessageFlash('erreur', "Les mots de passe ne correspondent pas");
                header('Location: controleurFrontal.php?controleur=admin&action=creerUtilisateur');
                exit();
            }
            if ($clientRepo->verifierEmailExistant($email)) {
                Session::getInstance()->ajouterMessageFlash('erreur', "Cet email est déjà utilisé.");
                header('Location: controleurFrontal.php?controleur=admin&action=creerUtilisateur');
                exit();
            }
            $mdpHash = MotDePasse::hacher($mdp);

            $villeRepo = new VilleRepository();
            $ville = $villeRepo->recupererParClePrimaire($idVille);
            if (!$ville) {
                Session::getInstance()->ajouterMessageFlash('erreur', "La ville sélectionnée est invalide");
                header('Location: controleurFrontal.php?controleur=admin&action=creerUtilisateur');
                exit();
            }
            $client = new Client(
                $login,
                $mdpHash,
                $nom,
                $prenom,
                $email,
                null,
                null,
                $tel,
                $adresse,
                $ville
            );
            if ($clientRepo->ajouter($client)){
                if (isset($_POST['admin'])){
                    $admin = new Admin($login, $mdpHash);
                    if ((new AdminRepository())->ajouter($admin)){
                        Session::getInstance()->ajouterMessageFlash('success', "Admin créé avec succès.");
                        header('Location: controleurFrontal.php?controleur=client&action=afficherListe');
                        exit();
                    } else {
                        Session::getInstance()->ajouterMessageFlash('erreur', "Problème lors de la création du compte administrateur");
                        header('Location: controleurFrontal.php?controleur=admin&action=creerUtilisateur');
                        exit();
                    }
                } else {
                    Session::getInstance()->ajouterMessageFlash('success', "Client créé avec succès.");
                    header('Location: controleurFrontal.php?controleur=client&action=afficherListe');
                    exit();
                }
            }

        } else {
            self::afficherErreur("Méthode non autorisée.");
        }
    }

    static function mettreAJour() : void
    {
        if (ConnexionUtilisateur::estAdministrateur()) {
            ControleurClient::modificationValableClient();
            $client = (new ClientRepository())->recupererParClePrimaire($_REQUEST['login']);

            if ($_REQUEST['mdp'] != "" ){
                if ($_REQUEST['mdp'] == $_REQUEST['mdp_confirmation']){
                    $admin = (new AdminRepository())->recupererParClePrimaire($_REQUEST['login']);
                    $mdp = $_REQUEST['mdp'];
                    $mdp = MotDePasse::hacher($mdp);
                } else {
                    self::afficherErreur("Les mots de passe ne correspondent pas.");
                    exit;
                }

            } else {
                $mdp = $client->getMdp();
            }

            if (isset($admin)){
                if (!isset($_POST['admin'])){
                    (new AdminRepository())->supprimer($_POST["login"]);
                } else {
                    $admin = new Admin($_REQUEST['login'], $mdp);
                    (new AdminRepository())->mettreAJour($admin);
                }
            }

            $newClient = ControleurClient::construireDepuisFormulaire($mdp, $_REQUEST['email'], null, null);
            (new ClientRepository())->mettreAJour($newClient);
            self::afficherVue([
                "titre"=>"Modification Utilisateur",
                "cheminCorpsVue"=>"admin/adminModifie.php",
                "client"=>$newClient]);
        }
    }

    static function supprimerAdmin(){
        $login = $_REQUEST['login'];
        (new AdminRepository())->supprimer($login);
        (new ClientRepository())->supprimer($login);
        $clients = (new ClientRepository())->recuperer();
        self::afficherVue([
            "Titre" => "Administrateur Supprimé",
            "cheminCorpsVue"=>"admin/adminSupprime.php",
            "clients"=>$clients,
        ]);
    }

    static function supprimerClient()
    {
        (new ClientRepository())->supprimer($_GET['login']);
        $clients = (new ClientRepository())->recuperer();
        self::afficherVue([
            "titre" => "Connexion",
            "cheminCorpsVue" => "admin/clientSupprime.php",
            "clients" => $clients
        ]);
    }

    static function estAdministrateur(String $login){
        $admin = (new AdminRepository())->recupererParClePrimaire($login);
        return isset($admin);
    }
}
<?php

namespace App\MDS\Controleur;

use App\MDS\Lib\ConnexionUtilisateur;
use App\MDS\Lib\MotDePasse;
use App\MDS\Lib\VerificationEmail;
use App\MDS\Modele\DataObject\Client;
use App\MDS\Modele\HTTP\Session;
use App\MDS\Modele\Repository\AbstractRepository;
use App\MDS\Modele\Repository\ClientRepository;
use App\MDS\Modele\Repository\VilleRepository;
use Exception;

class ControleurClient extends ControleurGenerique {

    static function afficherListe()
    {
        $clients = (new ClientRepository())->recuperer();
        //appel au modèle pour gérer la BD
        self::afficherVue(['clients' => $clients, "titre" => "Liste des clients", "cheminCorpsVue" => "client/liste.php"]);
    }
    static function afficherDetails(){
        if (ConnexionUtilisateur::estAdministrateur() && isset($_GET["login"])){
            $login = $_REQUEST["login"];
        } else {
            $login = ConnexionUtilisateur::getLoginUtilisateurConnecte();
        }

        try {
            $client = (new ClientRepository())->recupererParClePrimaire($login);
        } catch (Exception $e){
            self::afficherErreur("Le client n'existe pas");
            return;
        }
        self::afficherVue(['client' => $client, "titre" => "Détails", "cheminCorpsVue" => "client/details.php"]);
        }
        
    public static function afficherInscription() {
        $villes = (new VilleRepository())->recuperer();
        self::afficherVue([
            "titre" => "Inscription",
            "cheminCorpsVue" => "inscription.php",
            "villes" => $villes
        ]);
    }

    public static function inscrireClient()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = trim($_POST['login'] ?? '');
            $mdp = trim($_POST['mdp'] ?? '');
            $mdpConfirmation = trim($_POST['mdp_confirmation'] ?? '');
            $nom = trim($_POST['nom'] ?? '');
            $prenom = trim($_POST['prenom'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $tel = trim($_POST['tel']);
            echo $tel;
            $adresse = trim($_POST['adresse'] ?? '');
            $idVille = $_POST['idVille'] ?? '';
            if (empty($login) || empty($mdp) || empty($nom) || empty($prenom) || empty($email) || empty($adresse) || empty($idVille)) {
                Session::getInstance()->ajouterMessageFlash('erreur', "Veuillez renseigner tous les champs obligatoires");
                header('Location: controleurFrontal.php?controleur=client&action=afficherInscription');
                exit();
            }
            $clientRepo = new ClientRepository();
            if ($clientRepo->verifierLoginExistant($login)){
                self::afficherErreur("Ce login est déjà utilisé.");
            }
            if ($mdp !== $mdpConfirmation) {
                Session::getInstance()->ajouterMessageFlash('erreur', "Les mots de passe ne correspondent pas");
                header('Location: controleurFrontal.php?controleur=client&action=afficherInscription');
                exit();
            }

            if ($clientRepo->verifierEmailExistant($email)) {
                Session::getInstance()->ajouterMessageFlash('erreur', "Cet email est déjà utilisé.");
                header('Location: controleurFrontal.php?controleur=client&action=afficherInscription');
                exit();
            }
            $mdpHash = MotDePasse::hacher($mdp);
            $nonce = MotDePasse::genererChaineAleatoire();

            $villeRepo = new VilleRepository();
            $ville = $villeRepo->recupererParClePrimaire($idVille);
            if (!$ville) {
                Session::getInstance()->ajouterMessageFlash('erreur', "La ville sélectionnée est invalide");
                header('Location: controleurFrontal.php?controleur=client&action=afficherInscription');
                exit();
            }
            $client = new Client(
                $login,
                $mdpHash,
                $nom,
                $prenom,
                null,
                $email,
                $nonce,
                $tel,
                $adresse,
                $ville
            );
            if ($clientRepo->ajouter($client)) {
                self::afficherVue([
                    "titre" => "Validation de l'email",
                    "cheminCorpsVue" => "client/emailEnvoye.php",
                    "client" => $client
                ]);
            } else {
                self::afficherErreur("Erreur lors de la création du compte.");
            }
        } else {
            self::afficherErreur("Méthode non autorisée.");
        }
    }

    public static function validerEmail()
    {
        $login = $_GET['login'] ?? null;
        $nonce = $_GET['nonce'] ?? null;

        if (VerificationEmail::traiterEmailValidation($login, $nonce)) {
            Session::getInstance()->ajouterMessageFlash('success', "Email validé avec succès.");
            header('Location: controleurFrontal.php?controleur=connexion&action=afficherConnexion');
            exit();
        } else {
            Session::getInstance()->ajouterMessageFlash('erreur', "Lien de validation invalide");
            header('Location: controleurFrontal.php?controleur=connexion&action=afficherConnexion');
            exit();
        }
    }

    public static function afficherFormulaireMiseAJour(){
        if (ConnexionUtilisateur::estAdministrateur()){
            $login = $_REQUEST["login"];
        } else {
            $login = ConnexionUtilisateur::getLoginUtilisateurConnecte();
        }
        if (!ConnexionUtilisateur::estConnecte()){
            self::afficherErreur("Vous devez être connecté");
        }
        $client = (new ClientRepository())->recupererParClePrimaire($login);
        $villes = (new VilleRepository())->recuperer();
        self::afficherVue(['client' => $client, "titre" => "Détails", "cheminCorpsVue" => "client/formulaireMisAJour.php", "villes" => $villes]);
    }
    
    public static function mettreAJour(){
        if (!ConnexionUtilisateur::estConnecte()) {
            Session::getInstance()->ajouterMessageFlash('erreur', "Vous devez être connecté pour mettre a jour");
            header('Location: controleurFrontal.php?controleur=connexion&action=afficherConnexion');
            exit();
        }
        if (self::modificationValableClient()) {
            $client = (new ClientRepository())->recupererParClePrimaire($_REQUEST['login']);
            if (!MotDePasse::verifier($_REQUEST['ancienmdp'], $client->getMdp())){
                self::afficherErreur("Votre mot de passe est incorrect.");
                exit;
            }

            $boolEmail = false;
            if ($client->getEmail() != $_REQUEST['email']) {
                $nonce = MotDePasse::genererChaineAleatoire();
                ConnexionUtilisateur::deconnecter();
                self::envoiEmailValidation($nonce);
                $mail = NULL;
                $emailAValider = $_REQUEST['email'];
                $boolEmail = true;
            } else {
                $mail = $client->getEmail();
                $emailAValider = NULL;
                $nonce = NULL;
            }
            if ($_REQUEST['mdp'] != "" ){
                if ($_REQUEST['mdp'] == $_REQUEST['mdp_confirmation']){
                    $mdp = $_REQUEST['mdp'];
                    $mdp = MotDePasse::hacher($mdp);
                } else {
                    self::afficherErreur("Les mots de passe ne correspondent pas.");
                    exit;
                }

            } else {
                $mdp = $client->getMdp();
            }
            $client = self::construireDepuisFormulaire($mdp, $mail, $emailAValider, $nonce);
            $clients = (new ClientRepository())->recuperer();
            (new ClientRepository())->mettreAJour($client);
            $villes = (new VilleRepository())->recuperer();
            if ($boolEmail){
                require_once "../vue/vueGenerale.php";
            } else {
                Session::getInstance()->ajouterMessageFlash('success', "Profil mis à jour avec succès.");
                header('Location: controleurFrontal.php?controleur=client&action=afficherFormulaireMiseAJour&login='.$client->getLogin());
                exit();

            }
        }
        else {
            self::afficherErreur("Erreur lors de la completion du formulaire");
        }
    }

    public static function envoiEmailValidation(string $nonce){
        $email = trim($_POST['email'] ?? '');
        $login = trim($_POST['login'] ?? '');
        $lienValidation = "http://localhost/projetphp/web/controleurFrontal.php?controleur=client&action=validerEmail&login={$login}&nonce={$nonce}";
        mail(
            $email,
            "Validation de votre adresse email",
            "Cliquez sur ce lien pour valider votre email : $lienValidation"
        );
        echo 'Un email de validation vous a été envoyé. <a href="' . $lienValidation . '" > Confirmation</a>';
    }

    public static function modificationValableClient() : bool {
        $boolean1 = $_REQUEST['mdp'] == $_REQUEST['mdp2'];
        $utilisateur = (new ClientRepository())->recupererParClePrimaire($_REQUEST['login']);
        $boolean2 = MotDePasse::verifier($_REQUEST['ancienmdp'], $utilisateur->getMdp());
        $booleanEstUtilisateur = isset($utilisateur);
        $boolean4 = !is_null((new ClientRepository())->recupererParClePrimaire($_REQUEST['login']));
        $booleanAdmin = ConnexionUtilisateur::estAdministrateur();
        if (!$boolean4){
            self::afficherErreur("Le client n'existe pas");
            return false;
        }
        return $booleanAdmin || ($boolean1 && $boolean2 && $booleanEstUtilisateur);
    }

    public static function construireDepuisFormulaire(string $mdp, ?string $email, ?string $emailAValider, ?string $nonce) : Client {
        $login = trim($_POST['login'] ?? '');
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $tel = trim($_POST['tel'] ?? '');
        $adresse = trim($_POST['adr'] ?? '');
        $idVille = $_POST['idVille'] ?? '';
        if (!(new VilleRepository())->recupererParClePrimaire($idVille)){
            self::afficherErreur("Le ville n'existe pas");
        }
        $ville = (new VilleRepository())->recupererParClePrimaire($idVille);
        return new Client($login, $mdp, $nom,$prenom,$email,$emailAValider, $nonce, $tel,$adresse,$ville);
    }

    public static function supprimerSelfClient(){
        if (!ConnexionUtilisateur::estConnecte()) {
            Session::getInstance()->ajouterMessageFlash('erreur', "Vous devez être connecté pour effectuer cette action");
            header('Location: controleurFrontal.php?controleur=connexion&action=afficherConnexion');
            exit();
        }
        ConnexionUtilisateur::deconnecter();
        (new ClientRepository())->supprimer($_GET['login']);
        Session::getInstance()->ajouterMessageFlash('success', "Compte supprimé avec succès.");
        header('Location: controleurFrontal.php?controleur=connexion&action=afficherConnexion');
        exit();
    }

}
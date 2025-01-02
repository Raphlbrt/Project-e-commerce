<?php
namespace App\MDS\Controleur;

use App\MDS\Controleur\ControleurGenerique;
use App\MDS\Lib\ConnexionUtilisateur;
use App\MDS\Lib\MotDePasse;
use App\MDS\Modele\HTTP\Session;
use App\MDS\Modele\Repository\ClientRepository;
use App\MDS\Modele\Repository\AdminRepository;

class ControleurConnexion extends ControleurGenerique {
    private static int $MAX_TENTATIVES = 5;
    private static int $DUREE_VERROUILLAGE = 300;

    public static function afficherConnexion() {
        self::afficherVue([
            "titre" => "Connexion",
            "cheminCorpsVue" => "connexion.php"
        ]);
    }

    public static function verifierConnexion() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $session = Session::getInstance();
            $login = trim($_POST['login'] ?? '');
            $mdp = trim($_POST['mdp'] ?? '');
            if (empty($login) || empty($mdp)) {
                $session->ajouterMessageFlash('erreur', "Identifiant et mot de passe requis.");
                header('Location: controleurFrontal.php?controleur=connexion&action=afficherConnexion');
                exit();
            }
            $tentatives = $session->lire("tentatives_$login") ?? 0;
            $verrouillage = $session->lire("verrouillage_$login");
            if ($verrouillage && time() < $verrouillage) {
                $tempsRestant = $verrouillage - time();
                $session->ajouterMessageFlash('erreur', "Compte temporairement verrouillé. Réessayez dans $tempsRestant secondes.");
                header('Location: controleurFrontal.php?controleur=connexion&action=afficherConnexion');
                exit();
            }
            $client = (new ClientRepository())->recupererParClePrimaire($login);
            if ($client && MotDePasse::verifier($mdp, $client->getMdp())) {
                $session->supprimer("tentatives_$login");
                $session->supprimer("verrouillage_$login");
                ConnexionUtilisateur::connecter($login);
                header("Location: controleurFrontal.php");
                exit();
            }
            $admin = (new AdminRepository())->recupererParClePrimaire($login);
            if ($admin && MotDePasse::verifier($mdp, $admin->getMdp())) {
                $session->supprimer("tentatives_$login");
                $session->supprimer("verrouillage_$login");
                ConnexionUtilisateur::connecter($login);
                header("Location: controleurFrontal.php");
                exit();
            }
            $tentatives++;
            $session->enregistrer("tentatives_$login", $tentatives);
            if ($tentatives >= self::$MAX_TENTATIVES) {
                $verrouillage = time() + self::$DUREE_VERROUILLAGE;
                $session->enregistrer("verrouillage_$login", $verrouillage);
                $session->ajouterMessageFlash('erreur', "Trop de tentatives. Votre compte est verrouillé pour " . self::$DUREE_VERROUILLAGE / 60 . " minutes.");
            } else {
                $tentativesRestantes = self::$MAX_TENTATIVES - $tentatives;
                $session->ajouterMessageFlash('erreur', "Login ou mot de passe incorrect. Il vous reste $tentativesRestantes tentative(s).");
            }
            header('Location: controleurFrontal.php?controleur=connexion&action=afficherConnexion');
            exit();
        } else {
            self::afficherErreur("Méthode non autorisée.");
        }
    }

    public static function deconnexion() {
        if (!ConnexionUtilisateur::estConnecte()) {
            Session::getInstance()->ajouterMessageFlash('erreur', "Vous devez être connecté pour vous déconnecter.");
            header('Location: controleurFrontal.php?controleur=connexion&action=afficherConnexion');
            exit();
        }
        ConnexionUtilisateur::deconnecter();
        header("Location: controleurFrontal.php");
        exit();
    }
}

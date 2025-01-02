<?php
namespace App\MDS\Controleur;

use App\MDS\Lib\ConnexionUtilisateur;
use App\MDS\Modele\DataObject\Fournisseur;
use App\MDS\Modele\DataObject\Ville;
use App\MDS\Modele\HTTP\Session;
use App\MDS\Modele\Repository\FournisseurRepository;
use App\MDS\Modele\Repository\VilleRepository;

class ControleurFournisseur extends ControleurGenerique {
    public static function afficherAjoutFournisseur() {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            Session::getInstance()->ajouterMessageFlash('erreur', "Vous devez être administrateur.");
            header('Location: controleurFrontal.php?');
            exit();
        }
        $villes = (new VilleRepository())->recuperer();

        self::afficherVue([
            "titre" => "Ajouter un fournisseur",
            "cheminCorpsVue" => "fournisseur/ajouter.php",
            "villes" => $villes
        ]);
    }
    public static function ajouterFournisseur() {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            Session::getInstance()->ajouterMessageFlash('erreur', "Vous devez être administrateur.");
            header('Location: controleurFrontal.php?');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $societe = trim($_POST['societe'] ?? '');
            $adresse = trim($_POST['adresse'] ?? '');
            $tel = (int)trim($_POST['tel'] ?? 0);
            $mail = trim($_POST['mail'] ?? '');
            $idVille = (int)($_POST['idVille'] ?? 0);
            if (empty($societe) || empty($adresse) || empty($tel) || empty($mail)) {

                var_dump($societe);var_dump($adresse);var_dump($tel);var_dump($mail);
                self::afficherErreur("Tous les champs du fournisseur sont requis.");
                return;
            }
            if (!$idVille) {
                $nomVille = trim($_POST['nom'] ?? '');
                $departement = trim($_POST['departement'] ?? '');
                $codePostal = trim($_POST['code_postal'] ?? '');
                if (!empty($nomVille) && !empty($departement) && !empty($codePostal)) {
                    $ville = new Ville(null, $nomVille, $departement, $codePostal);
                    (new VilleRepository())->ajouter($ville);
                    $idVille = (int)ConnexionBaseDeDonnees::getPdo()->lastInsertId();
                } else {
                    self::afficherErreur("Veuillez sélectionner une ville ou en ajouter une nouvelle.");
                    return;
                }
            }
            $fournisseur = new Fournisseur(null, $societe, $adresse, $tel, $mail, $idVille);
            $fournisseurRepo = new FournisseurRepository();
            $idFournisseur = $fournisseurRepo->ajouter($fournisseur);

            if ($idFournisseur) {
                header('Location: controleurFrontal.php');
                exit;
            } else {
                self::afficherErreur("Erreur lors de l'ajout du fournisseur.");
            }
        } else {
            self::afficherErreur("Méthode non autorisée.");
        }
    }

    public static function afficherListe(): void {
        $fournisseurs = (new FournisseurRepository())->recuperer();
        self::afficherVue([
            "titre" => "Liste des fournisseurs",
            "cheminCorpsVue" => "fournisseur/liste.php",
            "fournisseurs" => $fournisseurs
        ]);
    }

    public static function afficherDetail(): void {
        $numFournisseur = $_GET['numFournisseur'] ?? null;
        if (!$numFournisseur) {
            self::afficherErreur("Numéro du fournisseur manquant.");
            return;
        }

        $fournisseur = (new FournisseurRepository())->recupererParClePrimaire($numFournisseur);

        if (!$fournisseur) {
            self::afficherErreur("Fournisseur introuvable.");
            return;
        }

        self::afficherVue([
            "titre" => "Détails du fournisseur",
            "cheminCorpsVue" => "fournisseur/detail.php",
            "fournisseur" => $fournisseur
        ]);
    }

}
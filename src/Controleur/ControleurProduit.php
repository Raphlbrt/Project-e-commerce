<?php
namespace App\MDS\Controleur;

use App\MDS\Lib\ConnexionUtilisateur;
use App\MDS\Modele\HTTP\Session;
use App\MDS\Modele\Repository\FournisseurRepository;
use App\MDS\Modele\Repository\PanierRepository;
use App\MDS\Modele\Repository\ProduitRepository;
use App\MDS\Modele\DataObject\Produit;

class ControleurProduit extends ControleurGenerique
{
    public static function afficherAccueil(array $parametres = []): void
    {
        $produits = (new ProduitRepository())->recuperer();
        self::afficherVue([
            "titre" => "Accueil",
            "cheminCorpsVue" => "produit/accueil.php",
            "produits" => $produits
        ]);
    }

    public static function afficherListe(): void
    {
        $categorie = $_GET['categorie'] ?? null;
        $produits = (new ProduitRepository())->recuperer();

        if ($categorie) {
            $produits = array_filter($produits, function (Produit $produit) use ($categorie) {
                $categoriesProduit = (new ProduitRepository())->getTableauCategories($produit);
                return in_array($categorie, $categoriesProduit);
            });
        }

        self::afficherVue([
            "titre" => "Liste des produits",
            "cheminCorpsVue" => "produit/liste.php",
            "produits" => $produits,
        ]);
    }

    public static function afficherDetail(): void
    {
        $idProduit = $_GET['id'] ?? null;
        if (!$idProduit) {
            self::afficherErreur("Identifiant du produit manquant.");
            return;
        }

        $produit = (new ProduitRepository())->recupererParClePrimaire($idProduit);

        if (!$produit) {
            self::afficherErreur("Produit introuvable.");
            return;
        }

        self::afficherVue([
            "titre" => "Détails du produit",
            "cheminCorpsVue" => "produit/detail.php",
            "produit" => $produit
        ]);
    }

    public static function afficherAjout(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            Session::getInstance()->ajouterMessageFlash('erreur', "Vous devez être administrateur.");
            header('Location: controleurFrontal.php?');
            exit();
        }

        $fournisseurs = (new FournisseurRepository())->recuperer();
        if (empty($fournisseurs)) {
            self::afficherErreur("Aucun fournisseur disponible. Veuillez en ajouter un avant de créer un produit.");
            return;
        }

        self::afficherVue([
            "titre" => "Ajout de Produit",
            "cheminCorpsVue" => "produit/ajouter.php",
            "fournisseurs" => $fournisseurs
        ]);
    }

    public static function ajouterProduit(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            Session::getInstance()->ajouterMessageFlash('erreur', "Vous devez être administrateur.");
            header('Location: controleurFrontal.php?');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            self::afficherErreur("Méthode non autorisée.");
            return;
        }

        $nomProduit = trim($_POST['nomProduit'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $prixUnite = $_POST['prixUnite'] ?? 0;
        $numeroFournisseur = $_POST['numeroFournisseur'] ?? '';
        $cheminImage = '';

        if (empty($nomProduit) || $prixUnite <= 0 || empty($numeroFournisseur)) {
            self::afficherErreur("Veuillez renseigner tous les champs obligatoires.");
            return;
        }
        if (!empty($_FILES['imageProduit']['name'])) {
            $uploadDir = __DIR__ . '/../../ressources/img/produits/';
            $uploadFile = $uploadDir . basename($_FILES['imageProduit']['name']);
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $fileExtension = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

            if (!in_array($fileExtension, $allowedExtensions)) {
                self::afficherErreur("Seuls les fichiers JPG, JPEG, PNG, et GIF sont autorisés.");
                return;
            }

            if (!move_uploaded_file($_FILES['imageProduit']['tmp_name'], $uploadFile)) {
                self::afficherErreur("L'upload de l'image a échoué.");
                return;
            }

            $cheminImage = 'ressources/img/produits/' . basename($_FILES['imageProduit']['name']);
        } else {
            self::afficherErreur("Une image du produit est requise.");
            return;
        }

        $produit = new Produit(null, $nomProduit, $description, (float)$prixUnite, 0, 0, (int)$numeroFournisseur, $cheminImage);

        if (!(new ProduitRepository())->ajouter($produit)) {
            self::afficherErreur("Erreur lors de l'ajout du produit.");
            return;
        }

        header('Location: controleurFrontal.php?controleur=produit&action=afficherListe');
        exit;
    }

    public static function ajouterAuPanier(): void
    {
        if (!ConnexionUtilisateur::estConnecte()) {
            Session::getInstance()->ajouterMessageFlash('erreur', "Vous devez être connecté pour ajouter un produit au panier.");
            header('Location: controleurFrontal.php?controleur=connexion&action=afficherConnexion');
            exit();
        }

        $idProduit = $_POST['idProduit'] ?? null;
        if (!$idProduit) {
            Session::getInstance()->ajouterMessageFlash('erreur', "Produit introuvable.");
            header('Location: controleurFrontal.php?controleur=produit&action=afficherListe');
            exit();
        }

        $produit = (new ProduitRepository())->recupererParClePrimaire($idProduit);
        if (!$produit) {
            Session::getInstance()->ajouterMessageFlash('erreur', "Produit introuvable.");
            header('Location: controleurFrontal.php?controleur=produit&action=afficherListe');
            exit();
        }

        $loginClient = ConnexionUtilisateur::getLoginUtilisateurConnecte();
        $panierRepo = new PanierRepository();
        $panierRepo->ajouterOuIncrementerProduit($loginClient, $idProduit);

        Session::getInstance()->ajouterMessageFlash('success', "Le produit '{$produit->getNomProduit()}' a été ajouté au panier !");
        header('Location: controleurFrontal.php?controleur=produit&action=afficherListe');
        exit();
    }

    public static function afficherPanier(): void
    {
        if (!ConnexionUtilisateur::estConnecte()) {
            Session::getInstance()->ajouterMessageFlash('erreur', "Vous devez être connecté pour ajouter un produit au panier.");
            header('Location: controleurFrontal.php?controleur=connexion&action=afficherConnexion');
            exit();
        }

        $loginClient = ConnexionUtilisateur::getLoginUtilisateurConnecte();
        $panier = (new PanierRepository())->recupererParColonne('loginClient', $loginClient);

        self::afficherVue([
            "titre" => "Votre panier",
            "cheminCorpsVue" => "produit/afficherPanier.php",
            "panier" => $panier
        ]);
    }

    public static function commander(): void
    {
        if (!ConnexionUtilisateur::estConnecte()) {
            Session::getInstance()->ajouterMessageFlash('erreur', "Vous devez être connecté pour pour commander");
            header('Location: controleurFrontal.php?controleur=connexion&action=afficherConnexion');
            exit();
        }

        $loginClient = ConnexionUtilisateur::getLoginUtilisateurConnecte();
        $panierRepo = new PanierRepository();
        $panier = $panierRepo->recupererParColonne('loginClient', $loginClient);

        if (empty($panier)) {
            self::afficherErreur("Votre panier est vide.");
            return;
        }

        $panierRepo->viderPanier($loginClient);

        self::afficherVue([
            "titre" => "Commande confirmée",
            "cheminCorpsVue" => "produit/confirmationCommande.php"
        ]);
    }
    public static function mettreAJourQuantite(): void
    {
        if (!ConnexionUtilisateur::estConnecte()) {
            Session::getInstance()->ajouterMessageFlash('erreur', "Vous devez être connecté pour modifier le panier");
            header('Location: controleurFrontal.php?controleur=connexion&action=afficherConnexion');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idProduit = $_POST['idProduit'] ?? null;
            $nouvelleQuantite = (int)($_POST['quantite'] ?? 0);
            if (!$idProduit || $nouvelleQuantite < 1) {
                self::afficherErreur("Données invalides.");
                return;
            }
            $loginClient = ConnexionUtilisateur::getLoginUtilisateurConnecte();
            $panierRepo = new PanierRepository();
            $panier = $panierRepo->recupererParColonne('loginClient', $loginClient);
            $produitDansPanier = false;
            foreach ($panier as $item) {
                if ($item->getIdProduit() === (int)$idProduit) {
                    $produitDansPanier = true;
                    break;
                }
            }
            if (!$produitDansPanier) {
                self::afficherErreur("Le produit n'est pas dans votre panier.");
                return;
            }
            $panierRepo->mettreAJourQuantite($loginClient, (int)$idProduit, $nouvelleQuantite);
            header("Location: controleurFrontal.php?controleur=produit&action=afficherPanier");
            exit();
        } else {
            self::afficherErreur("Méthode non autorisée.");
        }
    }
    public static function retirerProduit(): void
    {
        if (!ConnexionUtilisateur::estConnecte()) {
            Session::getInstance()->ajouterMessageFlash('erreur', "Vous devez être connecté pour modifier le panier");
            header('Location: controleurFrontal.php?controleur=connexion&action=afficherConnexion');
            exit();
        }


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idProduit = $_POST['idProduit'] ?? null;
            if (!$idProduit) {
                self::afficherErreur("Produit introuvable.");
                return;
            }
            $loginClient = ConnexionUtilisateur::getLoginUtilisateurConnecte();
            $panierRepo = new PanierRepository();
            $panier = $panierRepo->recupererParColonne('loginClient', $loginClient);
            $produitDansPanier = false;
            foreach ($panier as $item) {
                if ($item->getIdProduit() === (int)$idProduit) {
                    $produitDansPanier = true;
                    break;
                }
            }
            if (!$produitDansPanier) {
                self::afficherErreur("Le produit n'est pas dans votre panier.");
                return;
            }
            $panierRepo->supprimerProduit($loginClient, (int)$idProduit);
            header("Location: controleurFrontal.php?controleur=produit&action=afficherPanier");
            exit();
        } else {
            self::afficherErreur("Méthode non autorisée.");
        }
    }

}
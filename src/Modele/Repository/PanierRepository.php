<?php
namespace App\MDS\Modele\Repository;

use App\MDS\Modele\ConnexionBaseDeDonnees;
use App\MDS\Modele\DataObject\AbstractDataObject;
use App\MDS\Modele\DataObject\Panier;
use App\MDS\Modele\Repository\AbstractRepository;

class PanierRepository extends AbstractRepository
{
    protected function getNomTable(): string
    {
        return "MDS_Panier";
    }

    protected function construireDepuisTableauSQL(array $objetFormatTableau): AbstractDataObject
    {
        return new Panier(
            (int)$objetFormatTableau['id'],
            $objetFormatTableau['loginClient'],
            (int)$objetFormatTableau['idProduit'],
            (int)$objetFormatTableau['quantite']
        );
    }

    protected function getNomClePrimaire(): string
    {
        return "id";
    }

    protected function getNomsColonnes(): array
    {
        return ['id', 'loginClient', 'idProduit', 'quantite'];
    }

    protected function formatTableauSQL(AbstractDataObject $objet): array
    {
        /** @var Panier $panier */
        $panier = $objet;
        return [
            ':idTag' => $panier->getId(),
            ':loginClientTag' => $panier->getLoginClient(),
            ':idProduitTag' => $panier->getIdProduit(),
            ':quantiteTag' => $panier->getQuantite(),
        ];
    }

    protected function getTags(): array
    {
        return [':idTag', ':loginClientTag', ':idProduitTag', ':quantiteTag'];
    }

    public function recupererPanierParUtilisateur(string $loginClient): array
    {
        $pdo = ConnexionBaseDeDonnees::getPdo();
        $sql = "SELECT * FROM MDS_Panier WHERE loginClient = :loginClient";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['loginClient' => $loginClient]);

        $resultats = [];
        foreach ($stmt as $formatTableau) {
            $resultats[] = $this->construireDepuisTableauSQL($formatTableau);
        }
        return $resultats;
    }

    public function ajouterOuIncrementerProduit(string $loginClient, int $idProduit, int $quantite = 1): bool
    {
        $pdo = ConnexionBaseDeDonnees::getPdo();

        // Si le produit existe déjà, incrémentez la quantité
        $sql = "
        INSERT INTO MDS_Panier (loginClient, idProduit, quantite)
        VALUES (:loginClient, :idProduit, :quantite)
        ON DUPLICATE KEY UPDATE quantite = quantite + VALUES(quantite)
    ";

        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute([
                'loginClient' => $loginClient,
                'idProduit' => $idProduit,
                'quantite' => $quantite,
            ]);
            return true;
        } catch (\PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }



    public function incrementerQuantite(string $loginClient, int $idProduit, int $quantite = 1): void
    {
        $pdo = ConnexionBaseDeDonnees::getPdo();
        $sql = "
        UPDATE MDS_Panier
        SET quantite = quantite + :quantite
        WHERE loginClient = :loginClient AND idProduit = :idProduit
    ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'loginClient' => $loginClient,
            'idProduit' => $idProduit,
            'quantite' => $quantite,
        ]);
    }
    public function produitExisteDansPanier(string $loginClient, int $idProduit): bool
    {
        $pdo = ConnexionBaseDeDonnees::getPdo();
        $sql = "
        SELECT COUNT(*) FROM MDS_Panier
        WHERE loginClient = :loginClient AND idProduit = :idProduit
    ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'loginClient' => $loginClient,
            'idProduit' => $idProduit,
        ]);
        return $stmt->fetchColumn() > 0;
    }
    public function mettreAJourQuantite(string $loginClient, int $idProduit, int $quantite): void
    {
        $pdo = ConnexionBaseDeDonnees::getPdo();
        $sql = "
        UPDATE MDS_Panier
        SET quantite = :quantite
        WHERE loginClient = :loginClient AND idProduit = :idProduit
    ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'quantite' => $quantite,
            'loginClient' => $loginClient,
            'idProduit' => $idProduit,
        ]);
    }
    public function supprimerProduit(string $loginClient, int $idProduit): void
    {
        $pdo = ConnexionBaseDeDonnees::getPdo();
        $sql = "
        DELETE FROM MDS_Panier
        WHERE loginClient = :loginClient AND idProduit = :idProduit
    ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'loginClient' => $loginClient,
            'idProduit' => $idProduit,
        ]);
    }
    public function viderPanier(string $loginClient): void {
        $pdo = ConnexionBaseDeDonnees::getPdo();
        $sql = "DELETE FROM MDS_Panier WHERE loginClient = :loginClient";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':loginClient' => $loginClient]);
    }
    public function recupererParColonne(string $nomColonne, mixed $valeurColonne): array
    {
        $pdo = ConnexionBaseDeDonnees::getPdo();
        $sql = "SELECT * FROM " . $this->getNomTable() . " WHERE $nomColonne = :valeur";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':valeur' => $valeurColonne]);

        $resultats = [];
        foreach ($stmt->fetchAll() as $ligne) {
            $resultats[] = $this->construireDepuisTableauSQL($ligne);
        }
        return $resultats;
    }


}

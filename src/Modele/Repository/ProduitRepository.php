<?php
namespace App\MDS\Modele\Repository;

use App\MDS\Modele\ConnexionBaseDeDonnees;
use App\MDS\Modele\DataObject\AbstractDataObject;
use App\MDS\Modele\DataObject\Produit;


class ProduitRepository extends AbstractRepository {
    protected function getNomTable(): string {
        return "MDS_Produit";
    }
    protected function getNomsColonnes(): array {
        return ['id', 'nom', 'description', 'prix', 'quantiteStock', 'niveauReapprovisionnement', 'numFournisseur', 'image'];
    }
    protected function getTags(): array {
        return [
            ':idProduitTag',
            ':nomProduitTag',
            ':descriptionTag',
            ':prixProduitTag',
            ':quantiteStockTag',
            ':niveauReapprovisionnementTag',
            ':numeroFournisseurTag',
            ':cheminImageTag'
        ];
    }

    protected function construireDepuisTableauSQL(array $objetFormatTableau): AbstractDataObject {
        return new Produit(
            $objetFormatTableau['id'],
            $objetFormatTableau['nom'],
            $objetFormatTableau['description'] ?? '',
            $objetFormatTableau['prix'],
            $objetFormatTableau['quantiteStock'],
            $objetFormatTableau['niveauReapprovisionnement'],
            $objetFormatTableau['numFournisseur'],
            $objetFormatTableau['image'] ?? ''
        );
    }


    protected function formatTableauSQL(AbstractDataObject $objet): array {
        /** @var Produit $objet */
        return [
            ':idProduitTag' => $objet->getIdProduit(),
            ':nomProduitTag' => $objet->getNomProduit(),
            ':descriptionTag' => $objet->getDescription(),
            ':prixProduitTag' => $objet->getPrixProduit(),
            ':quantiteStockTag' => $objet->getQuantiteStock(),
            ':niveauReapprovisionnementTag' => $objet->getNiveauReapprovisionnement(),
            ':numeroFournisseurTag' => $objet->getNumeroFournisseur(),
            ':cheminImageTag' => $objet->getCheminImage()
        ];
    }

    public function getCategory(Produit $produit): ?array {
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query("SELECT nomCategorie from MDS_estDeCategorie WHERE referenceProduit ='".$produit->getIdProduit()."'");
        $tableau = $pdoStatement->fetch();
        if (!$tableau) {
            return null;
        }
        $tableau["nomCategorie"] = null;
        return $tableau;
    }

    public function getTableauCategories(Produit $produit): array {
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare(
            "SELECT nomCategorie FROM MDS_estDeCategorie WHERE referenceProduit = :idProduit"
        );
        $pdoStatement->execute(['idProduit' => $produit->getIdProduit()]);
        return $pdoStatement->fetchAll(\PDO::FETCH_COLUMN) ?: [];
    }

    protected function getNomClePrimaire(): string {
        return "id";
    }

}
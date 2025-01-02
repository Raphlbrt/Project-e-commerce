<?php
namespace App\MDS\Modele\Repository;

use App\MDS\Modele\DataObject\AbstractDataObject;
use App\MDS\Modele\DataObject\Ville;
use App\MDS\Modele\ConnexionBaseDeDonnees;
use PDO;
class VilleRepository extends AbstractRepository {

    protected function getNomTable(): string
    {
        return 'MDS_Ville';
    }

    public function construireDepuisTableauSQL(array $objetFormatTableau): AbstractDataObject
    {
        return new Ville($objetFormatTableau['id'], $objetFormatTableau['nom'], $objetFormatTableau['departement'], $objetFormatTableau['code_postal']);
    }

    protected function getNomClePrimaire(): string
    {
        return "id";
    }

    protected function getNomsColonnes(): array
    {
        return ['nom', 'departement', 'code_postal'];
    }

    protected function formatTableauSQL(AbstractDataObject $trajet): array
    {
        return array(
            'nomTag' => $trajet->getNom(),
            'departementTag' => $trajet->getDepartement(),
            'codePostalTag' => $trajet->getCodePostal(),
        );
    }

    protected function getTags(): array
    {
        return [":nomTag", ":departementTag", ":codePostalTag"];
    }

    public static function getLastInsertedVille(): int {
        $pdo = ConnexionBaseDeDonnees::getPdo();
        return $pdo->lastInsertId();
    }
}

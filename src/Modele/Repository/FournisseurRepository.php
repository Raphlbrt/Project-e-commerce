<?php
namespace App\MDS\Modele\Repository;

use App\MDS\Modele\Repository\AbstractRepository;
use App\MDS\Modele\DataObject\AbstractDataObject;
use App\MDS\Modele\DataObject\Fournisseur;

class FournisseurRepository extends AbstractRepository {
    protected function getNomTable(): string {
        return "MDS_Fournisseur";
    }

    protected function construireDepuisTableauSQL(array $objetFormatTableau): AbstractDataObject {
        return new Fournisseur(
            $objetFormatTableau['numFournisseur'],
            $objetFormatTableau['societe'],
            $objetFormatTableau['adresse'],
            $objetFormatTableau['tel'],
            $objetFormatTableau['mail'],
            $objetFormatTableau['idVille']
        );
    }
    protected function getNomClePrimaire(): string {
        return "numFournisseur";
    }
    protected function getNomsColonnes(): array {
        return ["societe", "adresse", "tel", "mail", "idVille"];
    }
    protected function formatTableauSQL(AbstractDataObject $objet): array {
        /** @var Fournisseur $objet */
        return [
            ":societe" => $objet->getSociete(),
            ":adresse" => $objet->getAdresse(),
            ":tel" => $objet->getTel(),
            ":mail" => $objet->getMail(),
            ":idVille" => $objet->getIdVille()
        ];
    }
    protected function getTags(): array {
        return [":societe", ":adresse", ":tel", ":mail", ":idVille"];
    }

}

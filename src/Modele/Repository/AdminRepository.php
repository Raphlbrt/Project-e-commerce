<?php
namespace App\MDS\Modele\Repository;

use App\MDS\Modele\DataObject\Admin;
use App\MDS\Modele\DataObject\AbstractDataObject;

class AdminRepository extends AbstractRepository {
    protected function getNomTable(): string {
        return 'MDS_Admin';
    }

    protected function getNomClePrimaire(): string {
        return 'loginAdmin';
    }

    protected function getNomsColonnes(): array {
        return ['loginAdmin', 'mdp'];
    }

    protected function getTags(): array {
        return [':loginAdminTag', ':mdpTag'];
    }

    protected function construireDepuisTableauSQL(array $objetFormatTableau): AbstractDataObject {
        return new Admin(
            $objetFormatTableau['loginAdmin'],
            $objetFormatTableau['mdp']
        );
    }

    protected function formatTableauSQL(AbstractDataObject $admin): array {
        return [
            ':loginAdminTag' => $admin->getLoginAdmin(),
            ':mdpTag' => $admin->getMdp()
        ];
    }
}

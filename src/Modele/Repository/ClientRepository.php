<?php

namespace App\MDS\Modele\Repository;

use App\MDS\Modele\ConnexionBaseDeDonnees;
use App\MDS\Modele\DataObject\AbstractDataObject;
use App\MDS\Modele\DataObject\Client;
use App\MDS\Modele\DataObject\Ville;

class ClientRepository extends AbstractRepository {

    protected function getNomTable(): string
    {
        return "MDS_Client";
    }

    protected function construireDepuisTableauSQL(array $objetFormatTableau): AbstractDataObject {
        $villeRepo = new VilleRepository();
        $ville = $villeRepo->recupererParClePrimaire($objetFormatTableau['idVille']);

        if (!$ville) {
            throw new \Exception("Ville non trouvée pour l'ID : " . $objetFormatTableau['idVille']);
        }

        return new Client(
            $objetFormatTableau['login'],
            $objetFormatTableau['mdp'],
            $objetFormatTableau['nom'],
            $objetFormatTableau['prenom'],
            $objetFormatTableau['email'],
            $objetFormatTableau['emailAValider'] ?? null,
            $objetFormatTableau['nonce'] ?? null,
            $objetFormatTableau['tel'] ?? null,
            $objetFormatTableau['adresse'],
            $ville
        );
    }

    protected function getNomClePrimaire(): string
    {
        return 'login';
    }

    protected function getNomsColonnes(): array {
        return ['login', 'mdp', 'nom', 'prenom', 'email', 'emailAValider', 'nonce', 'tel', 'adresse', 'idVille'];
    }

    protected function formatTableauSQL(AbstractDataObject $client): array {
        /** @var Client $client */
        return [
            ':loginTag' => $client->getLogin(),
            ':mdpTag' => $client->getMdp(),
            ':nomTag' => $client->getNom(),
            ':prenomTag' => $client->getPrenom(),
            ':emailTag' => $client->getEmail(),
            ':emailAValiderTag' => $client->getEmailAValider(),
            ':nonceTag' => $client->getNonce(),
            ':telTag' => $client->getTel(),
            ':adresseTag' => $client->getAdresse(),
            ':idVilleTag' => $client->getVille()->getId(),
        ];
    }

    protected function getTags(): array {
        return [':loginTag', ':mdpTag', ':nomTag', ':prenomTag', ':emailTag', ':emailAValiderTag', ':nonceTag', ':telTag', ':adresseTag', ':idVilleTag'];
    }


    public function verifierNonce(string $login, string $nonce): bool {
        $pdo = ConnexionBaseDeDonnees::getPdo();
        $sql = "SELECT nonce FROM MDS_Client WHERE login = :login";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':login' => $login]);

        $nonceEnregistre = $stmt->fetchColumn();
        return $nonceEnregistre === $nonce;
    }
    public function verifierEmailExistant(string $email): bool
    {
        $pdo = ConnexionBaseDeDonnees::getPdo();
        $sql = "SELECT COUNT(*) FROM MDS_Client WHERE email = :email OR emailAValider = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetchColumn() > 0;
    }

    public function verifierLoginExistant(string $login): bool
    {
        $pdo = ConnexionBaseDeDonnees::getPdo();
        $sql = "SELECT COUNT(*) FROM MDS_Client WHERE login = :login";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':login' => $login]);
        return $stmt->fetchColumn() > 0;
    }


}
<?php

namespace App\MDS\Lib;

use App\MDS\Modele\DataObject\Client;
use App\MDS\Modele\HTTP\Session;
use App\MDS\Modele\Repository\AdminRepository;
use App\MDS\Modele\Repository\ClientRepository;

class ConnexionUtilisateur
{
// L'utilisateur connecté sera enregistré en session associé à la clé suivante
    private static string $cleConnexion = "_utilisateurConnecte";

    public static function connecter(string $loginUtilisateur): void
    {
        $session = Session::getInstance();
        $session->enregistrer(self::$cleConnexion, $loginUtilisateur);

        $clientRepo = new ClientRepository();
        $client = $clientRepo->recupererParClePrimaire($loginUtilisateur);

        $adminRepo = new AdminRepository();
        $admin = $adminRepo->recupererParClePrimaire($loginUtilisateur);

        if ($admin) {
            $session->enregistrer('role', 'admin');
        } elseif ($client) {
            $session->enregistrer('role', 'client');
        }
    }

    public static function estConnecte(): bool
    {
        return Session::getInstance()->contient(self::$cleConnexion);
    }

    public static function deconnecter(): void
    {
        Session::getInstance()->detruire();
    }

    public static function getLoginUtilisateurConnecte(): ?string
    {
        return Session::getInstance()->lire(self::$cleConnexion);
    }

    public static function estUtilisateur($login): bool
    {
        return self::getLoginUtilisateurConnecte() === $login;
    }

    public static function estAdministrateur(): bool
    {
        $session = Session::getInstance();
        return $session->estAdministrateur() && self::estConnecte();
    }
}

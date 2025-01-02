<?php

namespace App\MDS\Modele\HTTP;

use App\MDS\Modele\Repository\AdminRepository;
use App\MDS\Modele\Repository\ClientRepository;
use Exception;
use http\Exception\RuntimeException;

class Session{
    private static ?Session $instance = null;

    /**
     * @throws Exception
     */
    private function __construct()
    {
        if (session_start() === false) {
            throw new Exception("La session n'a pas réussi à démarrer.");
        }
    }

    public static function getInstance(): Session
    {
        if (is_null(Session::$instance)) {
            Session::$instance = new Session();
        }
        Session::$instance->verifierDerniereActivite();
        return Session::$instance;
    }

    public function contient($nom): bool
    {
        return (isset($_SESSION[$nom]));
    }

    public function enregistrer(string $nom, mixed $valeur): void
    {
        $_SESSION[$nom] = $valeur;

        if ($nom === '_utilisateurConnecte') {
            $client = (new ClientRepository())->recupererParClePrimaire($valeur);
            $admin = (new AdminRepository())->recupererParClePrimaire($valeur);

            if ($admin) {
                $_SESSION['admin'] = 1;
            } elseif ($client) {
                $_SESSION['admin'] = 0;
            }
        }
    }


    public function lire(string $nom): mixed
    {
        return $_SESSION[$nom] ?? null;
    }


    public function supprimer($nom): void
    {
        unset($_SESSION[$nom]);
    }

    public function verifierDerniereActivite() : void
    {
        if (isset($_SESSION['dureeExpiration'])) {
            $dureeExpiration = $_SESSION['dureeExpiration'];
            if (isset($_SESSION['derniereActivite']) && (time() - $_SESSION['derniereActivite'] > ($dureeExpiration))) {
                session_unset();     // unset $_SESSION variable for the run-time
            }
        }
        $_SESSION['derniereActivite'] = time(); // update last activity time stamp
    }

    public function setmaxLifeTime(int $time) :  void
    {
        $_SESSION['dureeExpiration'] = $time;
    }

    public function detruire() : void
    {
        session_unset();     // unset $_SESSION variable for the run-time
        session_destroy();   // destroy session data in storage
        Cookie::supprimer(session_name()); // deletes the session cookie
        // Il faudra reconstruire la session au prochain appel de getInstance()
        Session::$instance = null;
    }

    public function estAdministrateur() : bool
    {
        if (isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {
            return true;
        }
        return false;
    }
    public function ajouterMessageFlash(string $type, string $message): void
    {
        $_SESSION['flash_messages'][$type][] = $message;
    }

    public function recupererMessagesFlash(): array
    {
        $messages = $_SESSION['flash_messages'] ?? [];
        unset($_SESSION['flash_messages']); // Les messages sont supprimés après récupération
        return $messages;
    }


}

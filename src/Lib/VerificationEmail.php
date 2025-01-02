<?php
namespace App\MDS\Lib;

use App\MDS\Configuration\ConfigurationSite;
use App\MDS\Modele\DataObject\Client;
use App\MDS\Modele\Repository\ClientRepository;

class VerificationEmail
{
    public static function envoiEmailValidation(Client $client): void
    {
        $destinataire = $client->getEmailAValider();
        $sujet = "Validation de l'adresse email";
        // Pour envoyer un email contenant du HTML
        $enTete = "MIME-Version: 1.0\r\n";
        $enTete .= "Content-type:text/html;charset=UTF-8\r\n";

        $loginURL = rawurlencode($client->getLogin());
        $nonceURL = rawurlencode($client->getNonce());
        $URLAbsolue = ConfigurationSite::getURLAbsolue();
        $lienValidationEmail = "$URLAbsolue?action=validerEmail&controleur=client&login=$loginURL&nonce=$nonceURL";
        $corpsEmailHTML = "<a href=\"$lienValidationEmail\">Validation</a>";

        // Simulation d'envoi d'email (à remplacer par mail() une fois configuré)
        echo "Simulation d'envoi d'un mail<br> Destinataire : $destinataire<br> Sujet : $sujet<br> Corps : <br>$corpsEmailHTML";
    }

    public static function traiterEmailValidation($login, $nonce): bool
    {
        $clientRepo = new ClientRepository();
        $client = $clientRepo->recupererParClePrimaire($login);
        if (!isset($client)) {
            echo "L'utilisateur n'existe pas dans la base de données.";
            return false;
        }
        if ($client->getEmail() !== null && strlen($client->getEmail()) > 0) {
            return true; // L'email a déjà été validé
        } elseif ($clientRepo->verifierNonce($login, $nonce)) {
            $client->setEmail($client->getEmailAValider());
            $client->setEmailAValider(null);
            $client->setNonce(null);
            $clientRepo->mettreAJour($client);
            return true;
        }
        return false;
    }


    public static function aValideEmail(Client $client): bool
    {
        return !empty($client->getEmail());
    }
}


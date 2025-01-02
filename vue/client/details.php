<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Client</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
/** @var Client $client */

use App\MDS\Lib\ConnexionUtilisateur;
use App\MDS\Modele\DataObject\Client;
if (ConnexionUtilisateur::estAdministrateur()){
    $controleur = "admin";
} else {
    $controleur = "client";
}
$controleur = "controleur=" . $controleur . "&";

echo '<br>Login : ' . htmlspecialchars($client->getLogin());

if (\App\MDS\Lib\ConnexionUtilisateur::estUtilisateur($client->getLogin()) || \App\MDS\Lib\ConnexionUtilisateur::estAdministrateur()){ // l'utilisateur connecté est lui-même soit c'est un admin
    echo '<br>Nom : ' . htmlspecialchars($client->getNom());
    echo '<br>Prénom : ' . htmlspecialchars($client->getPrenom());
    echo '<br>Email : ' . htmlspecialchars($client->getEmail() . "");
    echo '<br>Numéro de téléphone : ' . htmlspecialchars($client->getTel());
    echo '<br>Adresse : ' . htmlspecialchars($client->getAdresse());
    echo '<br>Ville : ' . htmlspecialchars($client->getVille()->getNom());
    $loginURL = rawurlencode($client->getLogin());
    if (ConnexionUtilisateur::estUtilisateur($client->getLogin())){ //la personne se change elle-même
        if (ConnexionUtilisateur::estAdministrateur()){ // la personne se change elle-même et est un admin mais ne peut pas s'auto-supprimer
            $lien = '../web/controleurFrontal.php?controleur=client&action=afficherFormulaireMiseAJour&login='.$loginURL;
            echo "<p><a href='$lien' ><input type='submit' value='Modifier' /></a></p>";
        } else { // la personne se change/supprimer elle-même et n'est pas un admin
            $lien = "../web/controleurFrontal.php?controleur=client&action=supprimerSelfClient&login=$loginURL";

            echo "<p><a href='$lien' ><input type='submit' value='Supprimer' /></a>";

            $lien = '../web/controleurFrontal.php?controleur=client&action=afficherFormulaireMiseAJour&login='.$loginURL;
            echo "<a href='$lien' ><input type='submit' value='Modifier' /></a></p>";
        }

    } else if (ConnexionUtilisateur::estAdministrateur()) { // si l'utilisateur co est administrateur et qu'il change qqn d'autre
        // s'il modifie un autre amdin => suppression qui mène à vue de suppression
        // s'il modifie un utilisateur lambda => modif + suppression
        $admin = (new \App\MDS\Modele\Repository\AdminRepository())->recupererParClePrimaire($client->getLogin());
        if (!isset($admin)){ //modification et suppression d'un client
            $lien = '../web/controleurFrontal.php?controleur=client&action=afficherFormulaireMiseAJour&login='.$loginURL;
            echo "<a href='$lien' ><input type='submit' value='Modifier' /></a>";
            $lien = "../web/controleurFrontal.php?controleur=admin&action=supprimerClient&login=$loginURL";
            echo "<a href='$lien' ><input type='submit' value='Supprimer' /></a>";
        }
        else { // suppression d'un autre admin
            $lien = "../web/controleurFrontal.php?controleur=admin&action=supprimerAdmin&login=$loginURL";
            echo "<a href='$lien' ><input type='submit' value='Supprimer' /></a>";
        }
    }

}
?>
</body>
</html>

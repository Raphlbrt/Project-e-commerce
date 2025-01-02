<!DOCTYPE html>
<html>

<body>
<?php
/** @var Client[] $clients */

use App\MDS\Modele\DataObject\Client;

foreach ($clients as $client) {
    $login = $client->getLogin();
    $loginHTML = htmlspecialchars($login);
    $loginURL = rawurlencode($login);
    $admin = (new \App\MDS\Modele\Repository\AdminRepository())->recupererParClePrimaire($login);

    $lien = "../web/controleurFrontal.php?controleur=client&action=afficherDetails&login=" . $loginURL;
    echo "<p> Utilisateur de login <a href = '$lien' > " . $loginHTML . "</a>";
    if (isset($admin)){
        echo " (admin)";
    }
    echo ".</p>";
}
$lien = "../web/controleurFrontal.php?controleur=admin&action=creerUtilisateur";

echo "<a href='$lien' ><input type='submit' value='Créer un utilisateur' /></a>";
?>
</body>
</html>

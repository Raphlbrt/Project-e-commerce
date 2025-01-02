<?php
/**
 * @var Client $client
 */

use App\MDS\Configuration\ConfigurationSite;
use App\MDS\Modele\DataObject\Client;

?>
<!DOCTYPE html>
<html lang="en">

<body>
<?php
if (ConfigurationSite::getDebug()){
    $method = "get";
} else {
    $method = "post";
}
echo "<form method='$method' action='../web/controleurFrontal.php'>";
?>
<fieldset>
    <input type='hidden' name='action' value='mettreAJour'>
    <?php
    if (\App\MDS\Lib\ConnexionUtilisateur::estAdministrateur()){
        $controleur = "admin";
    } else {
        $controleur = "client";
    }
    echo "<input type='hidden' name='controleur' value = '$controleur'>"
    ?>
    <legend>Mon formulaire :</legend>
    <p class="InputAddOn">
        <label class="InputAddOn-item" for="login_id">Login&#4;</label>
        <?php
        /**
         * @var Client $client
         */
        $login = $client->getLogin();
        $login = htmlspecialchars($login);
        echo "<input class=InputAddOn-field' type='text' value='$login' name='login' id='login_id' readonly>";
        ?>
    </p>

    <p class="InputAddOn">

        <label class="InputAddOn-item" for="nom_id">Nom&#42;</label>
        <?php
        /**
         * @var Client $client
         */
        $nom = $client->getNom();
        $nom = htmlspecialchars($nom);
        echo " <input class='InputAddOn-field' type='text' value='$nom' name='nom' id='nom_id' required />"
        ?>

    </p>
    <p class="InputAddOn">
        <label class="InputAddOn-item" for="prenom_id">Prénom&#42;</label>
        <?php
        /**
         * @var Client $client
         */
        $prenom = $client->getPrenom();
        $prenom = htmlspecialchars($prenom);
        echo "<input class='InputAddOn-field' type='text' value='$prenom' name='prenom' id='prenom_id' required/>"
        ?>
    </p>
    <p class="InputAddOn">
        <label class="InputAddOn-item" for="ancienmdp_id">Ancien mot de passe&#42;</label>
        <input class="InputAddOn-field" type="password" value="" placeholder="" name="ancienmdp" id="ancienmdp_id" required>
    </p>
    <p class="InputAddOn">
        <label class="InputAddOn-item" for="mdp_id">Mot de passe&#2;</label>
        <input class="InputAddOn-field" type="password" value="" placeholder="" name="mdp" id="mdp_id">
    </p>
    <p class="InputAddOn">
        <label class="InputAddOn-item" for="mdp2_id">Vérification du mot de passe&#2;</label>
        <input class="InputAddOn-field" type="password" value="" placeholder="" name="mdp2" id="mdp2_id">
    </p>
    <p class="InputAddOn">
        <label class="InputAddOn-item" for="email_id">Email&#42;</label>
        <?php
        $email = $client->getEmail();
        $email = htmlspecialchars($email);
        echo "<input class='InputAddOn-field' type='email' value='$email' name='email' id='email_id' required/>"
        ?>
    </p>

    <p class="InputAddOn">
        <label class="InputAddOn-item" for="tel_id">Numéro de téléphone&#42;</label>
        <?php
        $tel = $client->getTel();
        $tel = htmlspecialchars($tel);
        echo "<input class='InputAddOn-field' type='tel' value='$tel' name='tel' id='tel_id' required/>"
        ?>
    </p>

    <p class="InputAddOn">
        <label class="InputAddOn-item" for="adr_id">Adresse&#42;</label>
        <?php
        $adr = $client->getAdresse();
        $adr = htmlspecialchars($adr);
        echo "<input class='InputAddOn-field' type='text' value='$adr' name='adr' id='adr_id' required/>"
        ?>
    </p>

    <p class="InputAddOn">
        <label class="InputAddOn-item" for="ville_id">Ville&#42;</label>
        <select id="ville_id" name="idVille">
        <?php
        echo "<option value='" . $client->getVille()->getId() . "'>" . htmlspecialchars($client->getVille()->getNom()) . ", " . htmlspecialchars($client->getVille()->getCodePostal()) . ", " . (htmlspecialchars($client->getVille()->getDepartement())) . "</option>";

        /** @var Ville[] $villes */

        use App\MDS\Modele\DataObject\Ville;

        foreach ($villes as $ville):
            if ($ville != $client->getVille()) {
                echo "<option value='" . $ville->getId() . "'>" .
                    htmlspecialchars($ville->getNom()) . ", " . htmlspecialchars($ville->getCodePostal()) . ", " . (htmlspecialchars($ville->getDepartement()));
                "</option>";
            }
        endforeach; ?>
        </select></p>
    <?php
        if (\App\MDS\Lib\ConnexionUtilisateur::estAdministrateur() && \App\MDS\Controleur\ControleurAdmin::estAdministrateur($_REQUEST['login']) && \App\MDS\Lib\ConnexionUtilisateur::getLoginUtilisateurConnecte() != $_REQUEST['login']) {
            echo "<submit type='checkboxb'>Administrateur</submit>";
        }
    ?>
    <p>
        <input type="submit" value="Envoyer" />
    </p>

</fieldset>
</body>
</html>
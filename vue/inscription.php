<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>
<h1>Inscription</h1>
<?php
use App\MDS\Lib\ConnexionUtilisateur;
if (ConnexionUtilisateur::estAdministrateur()){
    echo "<form method='post' action='controleurFrontal.php?controleur=admin&action=inscrireClientouAdministrateur'>";
} else {
    echo "<form method='post' action='controleurFrontal.php?controleur=client&action=inscrireClient'>";
}
?>
    <!-- Identifiant -->
    <label for="login">Identifiant :</label>
    <input type="text" id="login" name="login" required><br><br>

    <!-- Mot de passe -->
    <label for="mdp">Mot de passe :</label>
    <input type="password" id="mdp" name="mdp" required><br><br>

    <!-- Confirmation du mot de passe -->
    <label for="mdp_confirmation">Confirmez le mot de passe :</label>
    <input type="password" id="mdp_confirmation" name="mdp_confirmation" required><br><br>

    <!-- Informations personnelles -->
    <label for="nom">Nom :</label>
    <input type="text" id="nom" name="nom" required><br><br>

    <label for="prenom">Prénom :</label>
    <input type="text" id="prenom" name="prenom" required><br><br>

    <label for="email">Email :</label>
    <input type="email" id="email" name="email" required><br><br>

    <!-- Numéro de téléphone -->
    <label for="tel">Téléphone :</label>
    <input type="text" id="tel" name="tel" required><br><br>

    <!-- Adresse -->
    <label for="adresse">Adresse :</label>
    <input type="text" id="adresse" name="adresse" required><br><br>

    <!-- Choisir une ville existante -->
    <fieldset>
        <legend>Choisir une ville existante :</legend>
        <label for="idVille">Ville existante :</label>
        <select id="idVille" name="idVille">
            <option value="">--- Sélectionnez une ville ---</option>
            <?php
            /** @var Ville[] $villes */

            use App\MDS\Modele\DataObject\Ville;

            foreach ($villes as $ville):
                echo "<option value=" . $ville->getId() . ">" .
                    $ville->getNom() . ", " . htmlspecialchars($ville->getCodePostal()) . ", " . (htmlspecialchars($ville->getDepartement()));
                "</option>";
            endforeach; ?>
        </select>
    </fieldset>

    <br><br>

    <!-- Ajouter une nouvelle ville -->
    <fieldset>
        <legend>Ou ajouter une nouvelle ville :</legend>
        <label for="nomVille">Nom de la ville :</label>
        <input type="text" id="nomVille" name="nomVille"><br><br>

        <label for="departement">Département :</label>
        <input type="text" id="departement" name="departement"><br><br>

        <label for="code_postal">Code postal :</label>
        <input type="text" id="code_postal" name="code_postal"><br><br>
    </fieldset>

    <?php
    if (ConnexionUtilisateur::estAdministrateur()){
        echo "<input type='checkbox' name='admin'>Administrateur</input>";
    }
    ?>

    <button type="submit">Créer un compte</button>
</form>
</body>
</html>
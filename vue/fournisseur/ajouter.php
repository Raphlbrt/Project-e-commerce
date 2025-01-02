<h1>Ajouter un fournisseur</h1>
<form method="post" action="controleurFrontal.php">
    <input type="hidden" name='action' value='ajouterFournisseur'>
    <input type="hidden" name='controleur' value='fournisseur'>
    <fieldset>
        <label for="societe_id">Société :</label>
        <input type="text" id="societe_id" name="societe" required><br><br>

        <label for="adresse_id">Adresse :</label>
        <input type="text" id="adresse_id" name="adresse" required><br><br>

        <label for="tel_id">Téléphone :</label>
        <input type="text" id="tel_id" name="tel" required><br><br>

        <label for="mail_id">Email :</label>
        <input type="email" id="mail_id" name="mail" required><br><br>

    </fieldset>
    <!-- Ville existante -->
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
    <!-- Nouvelle ville -->
    <fieldset>
        <legend>Ou ajouter une nouvelle ville :</legend>
        <label for="nom">Nom de la ville :</label>
        <input type="text" id="nom" name="nom"><br><br>

        <label for="departement">Département :</label>
        <input type="text" id="departement" name="departement"><br><br>

        <label for="code_postal">Code postal :</label>
        <input type="text" id="code_postal" name="code_postal"><br><br>
    </fieldset>
    <button type="submit">Ajouter</button>
</form>
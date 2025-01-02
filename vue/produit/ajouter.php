<?php
use App\MDS\Modele\DataObject\Fournisseur;
/** @var Fournisseur[] $fournisseurs */

?>

<h1>Ajouter un Produit</h1>
<form method="post" action="controleurFrontal.php?controleur=produit&action=ajouterProduit" enctype="multipart/form-data">
    <!-- Informations sur le produit -->
    <label for="nomProduit">Nom du produit :</label>
    <input type="text" id="nomProduit" name="nomProduit" required><br><br>

    <label for="description">Description :</label>
    <textarea id="description" name="description"></textarea><br><br>

    <label for="prixUnite">Prix unitaire :</label>
    <input type="number" step="0.01" id="prixUnite" name="prixUnite" required><br><br>

    <!-- Fournisseur existant -->
    <label for="numeroFournisseur">Choisir un fournisseur :</label>
    <select id="numeroFournisseur" name="numeroFournisseur" required>
        <option value="">--- Sélectionnez un fournisseur ---</option>
        <?php foreach ($fournisseurs as $fournisseur): ?>
            <option value="<?= htmlspecialchars($fournisseur->getNumFournisseur()) ?>">
                <?= htmlspecialchars($fournisseur->getSociete()) ?> - <?= htmlspecialchars($fournisseur->getAdresse()) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <br><br>
    <label for="imageProduit">Image du produit :</label>
    <input type="file" id="imageProduit" name="imageProduit" accept="image/*" required><br><br>
    <br><br>

    <button type="submit">Ajouter</button>
</form>
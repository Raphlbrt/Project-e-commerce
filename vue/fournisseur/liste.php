<?php
/** @var Fournisseur[] $fournisseurs */

use App\MDS\Modele\DataObject\Fournisseur;

echo "<section class='fournisseur'>";
foreach ($fournisseurs as $fournisseur) {
    $adresse = $fournisseur->getAdresse();
    if (strlen($adresse) > 50) {
        $adresse = substr($adresse, 0, 50) . "...";
    }

    echo "<a href='controleurFrontal.php?controleur=fournisseur&action=afficherDetail&numFournisseur=" . $fournisseur->getNumFournisseur() . "' class='produitInfo'><article class='container'>
            <div class='corpArticle'>
                <div class='contenuArticle'>
                    <h3> " . htmlspecialchars($fournisseur->getSociete()) . "</h3>
                    <p><B>Adresse :</B> " . $adresse . "</p>
                    <p><B>Mail :</B> " . htmlspecialchars($fournisseur->getMail()) . "</p>      
                    <p><B>Téléphone :</B> " . htmlspecialchars($fournisseur->getTel()) . "</p>
                </div>
            </div>
          </article></a>";
}
echo "</section>";


$lien = "../web/controleurFrontal.php?controleur=fournisseur&action=afficherAjoutFournisseur";

echo "<a href='$lien' ><input type='submit' value='Ajouter un fournisseur' /></a>";
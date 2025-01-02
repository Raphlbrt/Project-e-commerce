<?php
use App\MDS\Modele\DataObject\Produit;
use App\MDS\Modele\DataObject\Fournisseur;
use App\MDS\Modele\Repository\FournisseurRepository;
use App\MDS\Modele\Repository\ProduitRepository;

/** @var Produit $produit */
/** @var Fournisseur $fournisseur */
$fournisseur = (new FournisseurRepository())->recupererParClePrimaire($produit->getNumeroFournisseur());
$lienImg = "";
if ($produit->getCheminImage() != ""){$lienImg = "../";}
$lienImg = $lienImg . $produit->getCheminImage();
$description = $produit->getDescription();

$text = "";
if ($produit->getQuantiteStock() == 0){
    if ($produit->getNiveauReapprovisionnement()<1){
        $text = "<h4>Nous ne vendons plus ce produit</h4>";
    }else{
        $text = "<h4>Ce Produit est momentanément indisponible</h4>";
    }
} elseif ($produit->getNiveauReapprovisionnement()<1){
    $text = "<h4>Dernière vente pour ce produit</h4>";
} elseif ($produit->getQuantiteStock()<$produit->getNiveauReapprovisionnement()) {
    $text = "<h4>Dernière vente avant restock du produit</h4>";
}

$categories = (new ProduitRepository())->getCategory($produit) ?? [];
$categoriesAffichees = is_array($categories) ? implode(" ", $categories) : "Aucune catégorie";

?>
<section class="container">
    <div class="en_tete">
        <div class="imageDetail">
            <img src='<?= htmlspecialchars($lienImg) ?>' alt='Image du produit'>
        </div>
        <div class="titreDetail">
            <h1><?= htmlspecialchars($produit->getNomProduit()) ?></h1>
            <h2>Prix : <?= htmlspecialchars($produit->getPrixProduit()) ?>€ </h2>
        </div>
    </div>
    <div class="contenuDetail">
        <h4>Description :</h4>
        <p><?= htmlspecialchars($produit->getDescription()) ?></p>
        <?= $text ?>
        <p><b>Categorie(s):</b> <?= htmlspecialchars($categoriesAffichees) ?></p>
        <h4>
            Pour tout problème avec cet article, veuillez contacter :
            <br>
            <?= htmlspecialchars($fournisseur->getSociete()) ?> par téléphone au 0<?= htmlspecialchars($fournisseur->getTel()) ?> ou par mail à l'adresse <?= htmlspecialchars($fournisseur->getMail()) ?>
        </h4>
    </div>
    <form id='BoutonPannier' method='POST' action='controleurFrontal.php?controleur=produit&action=ajouterAuPanier'>
        <input type='hidden' name='idProduit' value='<?= htmlspecialchars($produit->getIdProduit()) ?>'>
        <button type='submit'>Ajouter au panier</button>
    </form>
</section>


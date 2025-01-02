<?php
use App\MDS\Modele\DataObject\Produit;
use App\MDS\Modele\Repository\ProduitRepository;
/** @var Produit[] $produits */

$critereTri = $_GET['sort'] ?? '';
$categoriesFiltres = $_GET['categories'] ?? []; // Récupération des catégories sélectionnées

function trierProduits(array $produits, string $critereTri): array {
    usort($produits, function (Produit $asc, Produit $des) use ($critereTri) {
        switch ($critereTri) {
            case 'nomAsc':
                return $asc->getNomProduit() <=> $des->getNomProduit();
            case 'nomDes':
                return $des->getNomProduit() <=> $asc->getNomProduit();
            case 'prixAsc':
                return $asc->getPrixProduit() <=> $des->getPrixProduit();
            case 'prixDes':
                return $des->getPrixProduit() <=> $asc->getPrixProduit();
            default:
                return 0;
        }
    });
    return $produits;
}

function filtrerProduitsParCategories(array $produits, array $categoriesFiltrees): array {
    if (empty($categoriesFiltrees)) {
        return $produits; // Aucun filtre sélectionné, on retourne tous les produits
    }

    $produitRepo = new ProduitRepository();

    return array_filter($produits, function (Produit $produit) use ($categoriesFiltrees, $produitRepo) {
        $categoriesProduit = $produitRepo->getCategory($produit) ?? [];
        return !empty(array_intersect($categoriesProduit, $categoriesFiltrees));
    });
}

$produits = filtrerProduitsParCategories($produits, $categoriesFiltres);
$produits = trierProduits($produits, $critereTri);

echo "<div class='columnFilter'>
    <div>
        <h4>Trier par :</h4>
        <form method='get' class='sort_form'>
            <input type='hidden' name='controleur' value='produit'>
            <input type='hidden' name='action' value='afficherListe'>
";

foreach ($categoriesFiltres as $categorie) {
    echo "<input type='hidden' name='categories[]' value='" . htmlspecialchars($categorie) . "'>";
}

echo "      <select name='sort' onchange='this.form.submit()'>
                <option value=''>---</option>
                <option value='nomAsc' " . ($critereTri == 'nomAsc' ? 'selected' : '') . ">alphabétique</option>
                <option value='nomDes' " . ($critereTri == 'nomDes' ? 'selected' : '') . ">anti-alphabétique</option>
                <option value='prixAsc' " . ($critereTri == 'prixAsc' ? 'selected' : '') . ">prix croissant</option>
                <option value='prixDes' " . ($critereTri == 'prixDes' ? 'selected' : '') . ">prix décroissant</option>
            </select>
        </form>
    </div>
    <p>____________________</p>
    <fieldset class='categories'>
        <h4>Catégories</h4>
        <br>
        <form method='get'>
            <input type='hidden' name='controleur' value='produit'>
            <input type='hidden' name='action' value='afficherListe'>
";

// Liste des catégories
$categoriesDisponibles = [
    'arme blanche' => 'Armes blanches',
    'cercueil' => 'Cercueils',
    'corde' => 'Cordes',
    'poison' => 'Poison',
    'post-mortem' => 'Post-Mortem',
    'Recettes empoisonnées' => 'Recettes empoisonnées',
    'sac plastique' => 'Sacs plastiques',
    'usage unique' => 'Usages uniques',
    'virus' => 'Virus',
    'autre' => 'Autres'
];

foreach ($categoriesDisponibles as $id => $label) {
    $checked = in_array($id, $categoriesFiltres) ? 'checked' : '';
    echo "
        <div>
            <input type='checkbox' id='$id' name='categories[]' value='$id' $checked />
            <label for='$id'>$label</label>
        </div>
        ";
}

echo "
            <button type='submit'>Appliquer filtres</button>
        </form>
    </fieldset>
</div>";

echo "<section class='produit'>";
foreach ($produits as $produit) {
    $lienImg = "";
    if ($produit->getCheminImage() != "") {
        $lienImg = "../";
    }
    $lienImg = $lienImg . $produit->getCheminImage();

    $description = $produit->getDescription();
    if (strlen($description) > 50) {
        $description = substr($description, 0, 50) . "...";
    }

    $categories = (new ProduitRepository())->getCategory($produit) ?? [];
    $categoriesAffichees = is_array($categories) ? implode(", ", $categories) : "Aucune catégorie";

    echo "<a href='controleurFrontal.php?controleur=produit&action=afficherDetail&id=" . $produit->getIdProduit() . "' class='produitInfo'><article class='container'>
            
            <div class='corpArticle'>
                <div class='imageArticle'>
                    <img src='" . $lienImg . "' alt='Image du produit'>
                </div>
                <div class='contenuArticle'>
                    <h3> " . htmlspecialchars($produit->getNomProduit()) . "</h3>
                    <h4>Prix : " . $produit->getPrixProduit() . "€</h4>
                    <p><B>Description :</B> " . $description . "</p>      
                    <p><B>Categorie(s):</B> " . htmlspecialchars($categoriesAffichees) . "</p>
                    <form id='BoutonPannier' method='POST' action='controleurFrontal.php?controleur=produit&action=ajouterAuPanier'>
                        <input type='hidden' name='idProduit' value='" . $produit->getIdProduit() . "'>
                        <button type='submit'>Ajouter au panier</button>
                    </form>
                </div>
            </div>
          </article></a>";
}
echo "</section>";
?>
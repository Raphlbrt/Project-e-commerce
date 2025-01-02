<h2>Votre panier</h2>
<?php use App\MDS\Modele\Repository\ProduitRepository;

if (empty($panier)): ?>
    <p>Votre panier est vide.</p>
<?php else: ?>
    <div class="panier">
        <?php
        $produitRepo = new ProduitRepository();
        $total = 0;

        foreach ($panier as $item):
            $produit = $produitRepo->recupererParClePrimaire($item->getIdProduit());
            if (!$produit) {
                continue;
            }

            $sousTotal = $produit->getPrixProduit() * $item->getQuantite();
            $total += $sousTotal;
            ?>
            <div class="panier-item">
                <div class="panier-item-image">
                    <img src="<?= htmlspecialchars("../".($produit->getCheminImage())) ?>" alt="<?= htmlspecialchars($produit->getNomProduit()) ?>">
                </div>
                <div class="panier-item-details">
                    <h3><?= htmlspecialchars($produit->getNomProduit()) ?></h3>
                    <p>Prix unitaire : <?= htmlspecialchars($produit->getPrixProduit()) ?> €</p>
                    <p>Sous-total : <?= htmlspecialchars($sousTotal) ?> €</p>
                    <form method="POST" action="controleurFrontal.php?controleur=produit&action=mettreAJourQuantite">
                        <input type="hidden" name="idProduit" value="<?= $item->getIdProduit() ?>">
                        <?php if ($item->getQuantite() > 1): ?>
                            <button type="submit" name="quantite" value="<?= $item->getQuantite() - 1 ?>">-</button>
                        <?php endif; ?>
                        <span><?= htmlspecialchars($item->getQuantite()) ?></span>
                        <button type="submit" name="quantite" value="<?= $item->getQuantite() + 1 ?>">+</button>
                    </form>
                    <form method="POST" action="controleurFrontal.php?controleur=produit&action=retirerProduit">
                        <input type="hidden" name="idProduit" value="<?= $item->getIdProduit() ?>">
                        <button type="submit">Retirer</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="panier-total">
            <h3>Total : <?= htmlspecialchars($total) ?> €</h3>
            <a href="controleurFrontal.php?controleur=produit&action=commander">
                <button type="button">Commander</button>
            </a>
        </div>
    </div>
<?php endif; ?>

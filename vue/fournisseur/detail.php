<?php
use App\MDS\Modele\DataObject\Fournisseur;
use App\MDS\Modele\DataObject\Ville;
use App\MDS\Modele\Repository\VilleRepository;

/** @var Fournisseur $fournisseur */
/** @var Ville $ville */
$ville = (new VilleRepository())->recupererParClePrimaire($fournisseur->getIdVille());

?>
<section class="container">
    <div class="contenuDetail">
        <h1><?= htmlspecialchars($fournisseur->getSociete()) ?></h1>
        <p><b>Adresse :</b> <?= htmlspecialchars($fournisseur->getAdresse()) ?>, <?= htmlspecialchars($ville->getNom()) ?>, <?= htmlspecialchars($ville->getCodePostal()) ?>, <?= htmlspecialchars($ville->getDepartement()) ?></p>
        <p><b>Mail :</b> <?= htmlspecialchars($fournisseur->getMail()) ?></p>
        <p><b>Téléphone :</b> <?= htmlspecialchars($fournisseur->getTel()) ?></p>
    </div>
</section>
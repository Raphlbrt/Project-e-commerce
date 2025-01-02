<nav>
    <div><a href="controleurFrontal.php">MDS</a>
        <div class="submenu">
            <div><a href="controleurFrontal.php?controleur=produit&action=afficherListe">Produits</a>
                <div class="subsubmenu">
                    <div><a href="controleurFrontal.php?controleur=produit&action=afficherListe&categorie=arme blanche">Armes Blanches</a></div>
                    <div><a href="controleurFrontal.php?controleur=produit&action=afficherListe&categorie=cercueil">Cercueils</a></div>
                    <div><a href="controleurFrontal.php?controleur=produit&action=afficherListe&categorie=corde">Cordes</a></div>
                    <div><a href="controleurFrontal.php?controleur=produit&action=afficherListe&categorie=poison">Poisons</a></div>
                    <div><a href="controleurFrontal.php?controleur=produit&action=afficherListe&categorie=post-mortem">Post-Mortem</a></div>
                    <div><a href="controleurFrontal.php?controleur=produit&action=afficherListe&categorie=Recettes empoisonnées">Recettes empoisonnées</a></div>
                    <div><a href="controleurFrontal.php?controleur=produit&action=afficherListe&categorie=sac plastique">Sacs plastiques</a></div>
                    <div><a href="controleurFrontal.php?controleur=produit&action=afficherListe&categorie=usage unique">Usages uniques</a></div>
                    <div><a href="controleurFrontal.php?controleur=produit&action=afficherListe&categorie=virus">Virus</a></div>
                    <div><a href="controleurFrontal.php?controleur=produit&action=afficherListe&categorie=autre">Autres</a></div>
                </div>
            </div>
        </div>
    </div>

    <?php
    use App\MDS\Lib\ConnexionUtilisateur;
    if (!ConnexionUtilisateur::estConnecte()):?>
        <div><a href="controleurFrontal.php?controleur=client&action=afficherInscription">Inscription</a></div>
        <div><a href="controleurFrontal.php?controleur=connexion&action=afficherConnexion">Connexion</a></div>
    <?php endif;?>

    <?php

    if (ConnexionUtilisateur::estAdministrateur()): ?>
        <div>
            <a>Modification Admin</a>
            <div class="submenu">
                <div><a href="controleurFrontal.php?controleur=produit&action=afficherAjout">Ajouter un produit</a></div>
                <div><a href="controleurFrontal.php?controleur=fournisseur&action=afficherListe">Liste des fournisseurs</a></div>
                <div><a href="controleurFrontal.php?controleur=fournisseur&action=afficherAjoutFournisseur">Ajouter un fournisseur</a></div>
                <div><a href="controleurFrontal.php?controleur=client&action=afficherListe">Liste des utilisateurs</a></div>
                <div><a href="controleurFrontal.php?controleur=admin&action=creerUtilisateur">Créer un nouvel utilisateur</a></div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (ConnexionUtilisateur::estConnecte()):
        echo "
            <div><a href='controleurFrontal.php?controleur=client&action=afficherDetails'>Détails</a></div>
            <div><a href='controleurFrontal.php?controleur=produit&action=afficherPanier'>Mon panier</a></div>
            <div><a href='controleurFrontal.php?controleur=connexion&action=deconnexion'>Déconnexion</a></div>
        ";
    endif; ?>
</nav>

<div class="burger">
    <img src="../../NeuilleVille/src/ressources/img/burger.png" alt="burger" width="50">
    <div id="menu2">
        <div>
            <h2>MDS</h2>
            <img src="../../NeuilleVille/src/ressources/img/fermer-la-croix-modified.png" alt="burger" width="40" height="40" tabindex="0"
                 onclick="toggleMenu()">
        </div>
        <div><a href="controleurFrontal.php?controleur=generique&action=afficherAccueil">MDS</a></div>

        <?php
        if (ConnexionUtilisateur::estAdministrateur()): ?>
        <div class="AdminContainer">
            <div class="AdminHead">
                <a>Admin</a>
                <div>
                    <svg onclick="ToggleAdmin(this)" style="cursor: pointer;" xmlns="http://www.w3.org/2000/svg"
                         x="0px" y="0px" width="20" height="20" viewBox="0 0 30 30">
                        <path d="M 24.990234 8.9863281 A 1.0001 1.0001 0 0 0 24.292969 9.2929688 L 15 18.585938 L 5.7070312 9.2929688 A 1.0001 1.0001 0 0 0 4.9902344 8.9902344 A 1.0001 1.0001 0 0 0 4.2929688 10.707031 L 14.292969 20.707031 A 1.0001 1.0001 0 0 0 15.707031 20.707031 L 25.707031 10.707031 A 1.0001 1.0001 0 0 0 24.990234 8.9863281 z"></path>
                    </svg>
                </div>
            </div>
            <div class="AdminBody">
                <div class="AdminContainer">
                    <div class="AdminHead">
                        <div><a href="controleurFrontal.php?controleur=produit&action=afficherAjout">Ajouter un produit</a></div>
                        <div><a href="controleurFrontal.php?controleur=fournisseur&action=afficherAjoutFournisseur">Ajouter un fournisseur</a></div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php if (ConnexionUtilisateur::estConnecte()):
            $login = ConnexionUtilisateur::getLoginUtilisateurConnecte();
            echo "<div><a href='controleurFrontal.php?controleur=client&action=afficherDetails'>Détails</a></div>"; ?>
            <div><a href="controleurFrontal.php?controleur=produit&action=afficherPanier">Mon panier</a></div>
            <div><a href="controleurFrontal.php?controleur=connexion&action=deconnexion">Déconnexion</a></div>
        <?php endif; ?>
    </div>
</div>
<script>
    function toggleMenu() {
        var menu = document.getElementById('menu2');
        if (menu.style.display === 'block') {
            menu.style.display = 'none';
        } else {
            menu.style.display = 'block';
        }
    }

    var burgerImage = document.querySelector('.burger img');
    burgerImage.addEventListener('click', toggleMenu);


    function ToggleAdmin(element) {
        if (element.parentElement.parentElement.parentElement.classList.contains("open")) {
            element.parentElement.parentElement.parentElement.classList.remove("open");

        } else {
            element.parentElement.parentElement.parentElement.classList.add("open");
        }
    }

</script>

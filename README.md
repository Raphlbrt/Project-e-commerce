# README

## Récapitulatif et Pourcentage d'Investissement des Membres

Ce fichier résume la contribution de chaque membre du groupe dans le projet, en précisant les fonctionnalités et tâches qu’ils ont réalisées. Voici la répartition :

---

### **Raphaël (45%)**
**Gestion des Produits :**
- Systeme Ajout de produits.

**Gestion du Panier :**
- Ajout de produits au panier avec incrémentation automatique si le produit existe déjà.
- Mise à jour des quantités dans le panier avec gestion des limites (pas de décrémentation en dessous de 1).
- Suppression de produits spécifiques du panier et vidage complet du panier.
- Affichage d'un total dans le panier avec des boutons d'interaction (augmenter, diminuer, supprimer).
- Confirmation de commande et vidage automatique du panier après validation.

**Gestion des Fournisseurs :**
- Systeme Ajout de fournisseurs avec sélection ou création de villes associées.

**Gestion des Clients :**
- Inscription avec sélection ou création de villes associées.
- Gestion des connexions, avec un système limitant les tentatives de connexion (protection brute force).
- Validation des e-mails et affichage des détails des clients.

**Interface et Responsivité :**
- Création d'une interface utilisateur élégante et responsive avec un design sombre, boutons interactifs, et sections bien délimitées.
- Navigation améliorée avec sous-menus et responsive design pour les appareils mobiles.

**Messages Flash :**
- Mise en place d'un système de messages flash pour notifier les actions réussies (ajout, suppression, modification) ou afficher les erreurs de formulaire. Cela a permis de réduire la dépendance à des vues spécifiques pour ces cas.

---

### **Héloïse (38%)**
**Gestion des Clients :**
- Mise en œuvre complète des fonctionnalités clients :
    - Création, modification, suppression.
    - Détails des clients.

**Gestion des Administrateurs :**
- Implémentation de la création, modification, et suppression des administrateurs.
- Gestion des clients par les administrateurs (création, modification, suppression).

**Liste des Utilisateurs :**
- Affichage et gestion d'une liste détaillée des utilisateurs pour les administrateurs.

---

### **Alexandre (12%)**
**Gestion des Produits :**
- Implémentation de la liste des produits.
- Détails des produits (sans bouton de commande intégré).

**Historique des Commandes :**
- Mise en place d’un historique des commandes côté serveur (non intégré à l’interface utilisateur donc pas d'implémentation et pas finis).

---

### **Quentin (5%)**
**Filtrage des Produits :**
- Implémentation des filtres pour les produits.
- Gestion et classification des catégories de produits.

---

## Synthèse Globale
- **Raphaël** : Gestion du panier, fournisseurs, messages flash, design, et connectivité utilisateur.
- **Héloïse** : Gestion des clients, administrateurs et interfaces d’administration.
- **Alexandre** : Liste des produits, détails des produits, et gestion des historiques côté serveur.
- **Quentin** : Filtres et catégories pour les produits.

---

## Contribution Totale
- **Raphaël : 45%**
- **Héloïse : 38%**
- **Alexandre : 12%**
- **Quentin : 5%**

https://gitlabinfo.iutmontp.univ-montp2.fr/rigauxh/projetphp

https://webinfo.iutmontp.univ-montp2.fr/~lambertr/projetphp/web/controleurFrontal.php
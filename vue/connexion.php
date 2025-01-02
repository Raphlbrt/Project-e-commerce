<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>
<h1>Connexion</h1>
<form method="post" action="controleurFrontal.php?controleur=connexion&action=verifierConnexion">
    <!-- Identifiant -->
    <label for="login">Identifiant :</label>
    <input type="text" id="login" name="login" required><br><br>

    <!-- Mot de passe -->
    <label for="mdp">Mot de passe :</label>
    <input type="password" id="mdp" name="mdp" required><br><br>

    <button type="submit">Se connecter</button>
</form>
</body>
</html>

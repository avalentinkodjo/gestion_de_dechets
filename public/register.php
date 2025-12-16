<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="css/inscon.css">  <!-- Style CSS -->
</head>
<body>
    <div class="form-container">
        <h2>Inscription</h2>
        <form action="../controllers/AuthController.php" method="post" autocomplete="off">
            <input type="text" name="nom" placeholder="Nom complet" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
            <select name="role" required>
                <option value="citoyen">Citoyen</option>
                <option value="collecteur">Collecteur</option>
                <option value="entreprise">Entreprise de collecte</option>
            </select>
            <button type="submit" name="inscription">S'inscrire</button>
        </form>
    </div>
</body>
</html>

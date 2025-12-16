<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Connexion / Inscription</title>
  <link rel="stylesheet" href="css/inscon.css">
</head>
<body>
  <div class="container" id="container">
    <!-- Formulaire d'inscription -->
    <div class="form-container sign-up-container">
      <form action="../controllers/AuthController.php" method="post" autocomplete="off">
        <h2>Créer un compte</h2>
        <input type="text" name="nom" placeholder="Nom complet" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
        <select name="role" required>
          <option value="">-- Choisir un rôle --</option>
          <option value="citoyen">Citoyen</option>
          <option value="collecteur">Collecteur</option>
          <option value="entreprise">Entreprise de collecte</option>
        </select>
        <button type="submit" name="inscription">S'inscrire</button>
      </form>
    </div>

    <!-- Formulaire de connexion -->
    <div class="form-container sign-in-container">
      <form action="../controllers/AuthController.php" method="post" autocomplete="off">
        <h2>Connexion</h2>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
        <button type="submit" name="connexion">Se connecter</button>
      </form>
    </div>

    <!-- Panneau latéral -->
    <div class="overlay-container">
      <div class="overlay">
        <div class="overlay-panel overlay-left">
          <h3>Déjà inscrit ?</h3>
          <p>Connecte-toi avec ton compte existant</p>
          <button class="ghost" id="signIn">Se connecter</button>
        </div>
        <div class="overlay-panel overlay-right">
          <h3>Nouveau ici ?</h3>
          <p>Crée un compte pour accéder à la plateforme</p>
          <button class="ghost" id="signUp">S'inscrire</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');
    const container = document.getElementById('container');

    signUpButton.addEventListener('click', () => {
      container.classList.add("right-panel-active");
    });

    signInButton.addEventListener('click', () => {
      container.classList.remove("right-panel-active");
    });
  </script>
</body>
</html>

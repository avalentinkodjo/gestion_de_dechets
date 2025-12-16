<?php
session_start();
if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'collecteur') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Accueil - Collecteur</title>
  <link rel="stylesheet" href="public/css/accueil.css">
</head>
<body>
  <header class="hero">
    <div class="container">
      <h1>Bienvenue, collecteur 🚛</h1>
      <p>Accédez à vos missions et assurez un Togo plus propre.</p>
      <a href="missions.php" class="btn">Voir mes missions</a>
    </div>
  </header>

  <section class="infos">
    <div class="container">
      <h2>📌 Statut actuel</h2>
      <p><strong>Statut :</strong> Disponible</p>
      <a href="changer_statut.php" class="btn btn-outline">Changer mon statut</a>
    </div>
  </section>

  <section class="actions">
    <div class="container">
      <h2>📷 Preuves de collecte</h2>
      <a href="preuves.php" class="btn btn-outline">Gérer mes preuves</a>
    </div>
  </section>

  <footer>
    <p>&copy; 2025 - Plateforme de gestion des déchets du Togo</p>
  </footer>
</body>
</html>

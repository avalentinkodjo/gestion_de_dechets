<?php
session_start();
if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'citoyen') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Accueil - Citoyen</title>
  <link rel="stylesheet" href="public/css/accueil.css">
</head>
<body>
  <header class="hero">
    <div class="container">
      <h1>Bienvenue, citoyen 👋</h1>
      <p>Déclarez vos déchets en quelques clics pour un Togo plus propre.</p>
      <a href="demande_collecte.php" class="btn">Faire une demande de collecte</a>
    </div>
  </header>

  <section class="infos">
    <div class="container">
      <h2>📦 Vos dernières demandes</h2>
      <ul class="infos-list">
        <li>Demande du 25 avril - <strong>En attente</strong></li>
        <li>Demande du 20 avril - <strong>Collectée</strong></li>
      </ul>
    </div>
  </section>

  <section class="actions">
    <div class="container">
      <h2>💬 Feedback</h2>
      <a href="avis.php" class="btn btn-outline">Laisser un avis</a>
      <a href="historique.php" class="btn btn-outline">Voir l'historique</a>
    </div>
  </section>

  <footer>
    <p>&copy; 2025 - Plateforme de gestion des déchets du Togo</p>
  </footer>
</body>
</html>

<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: auth.html");
    exit();
}

include '../config/database.php';

$user_id = $_SESSION['id'];
$nom = $_SESSION['nom'] ?? 'Utilisateur';

// Récupérer le nombre de notifications non lues
$stmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE destinataire_role = ? AND statut = 'non_lu'");
$stmt->execute([$user_id]);
$notif_count = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Accueil Citoyen - Gestion des Déchets</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/accueil_citoyen.css">
</head>
<body>
  <header class="hero">
    <nav>
      <div class="logo">🌿 Déchets-Togo</div>
      <ul>
        <li><a href="demande_collecte.php">Faire une demande</a></li>
        <li><a href="messages.php">Messages</a></li>
        <li><a href="notifications.php">Notifications (<?= $notif_count ?>)</a></li>
        <li><a href="historique.php">Historique</a></li>
        <li><a href="logout.php">Déconnexion</a></li>
      </ul>
    </nav>
    <div class="hero-content">
      <h1>Bienvenue <?= htmlspecialchars($nom) ?> 👋</h1>
      <p>Merci de contribuer à un Togo plus propre. Faites une demande, suivez vos collectes, et communiquez avec les entreprises engagées pour l’environnement.</p>
      <a href="demande_collecte.php" class="btn">Demander une collecte</a>
    </div>
  </header>

  <section class="features">
    <div class="feature">
      <img src="images/collecte.png" alt="Collecte">
      <h3>Collecte à domicile</h3>
      <p>Planifiez une intervention rapide à votre adresse.</p>
    </div>
    <div class="feature">
      <img src="images/messagerie.png" alt="Messagerie">
      <h3>Dialogue avec les entreprises</h3>
      <p>Échangez avec les professionnels de la gestion des déchets.</p>
    </div>
    <div class="feature">
      <img src="images/suivi.png" alt="Historique">
      <h3>Suivi de vos actions</h3>
      <p>Consultez l'historique de toutes vos demandes.</p>
    </div>
  </section>

  <footer>
    <p>&copy; 2025 - Plateforme de Gestion des Déchets - Togo</p>
  </footer>
</body>
</html>

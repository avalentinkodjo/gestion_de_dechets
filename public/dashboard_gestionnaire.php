<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'gestionnaire') {
    header("Location: auth.html");
    exit();
}
include '../config/database.php';

// Mettre à jour les notifications comme "lu" avec l'heure de lecture
$stmt = $pdo->prepare("UPDATE notifications SET statut = 'lu', date_vue = NOW() WHERE destinataire_role = 'gestionnaire' AND statut = 'non_lu'");
$stmt->execute();

// Récupérer les notifications encore valides (moins de 30 minutes après lecture)
$stmtNotif = $pdo->prepare("SELECT * FROM notifications WHERE destinataire_role = 'gestionnaire' AND statut = 'lu' AND date_vue >= NOW() - INTERVAL 30 MINUTE");
$stmtNotif->execute();
$notifications = $stmtNotif->fetchAll(PDO::FETCH_ASSOC);

// Supprimer les anciennes notifications (plus de 30 min après avoir été vues)
$stmtDelete = $pdo->prepare("DELETE FROM notifications WHERE statut = 'lu' AND date_vue < NOW() - INTERVAL 30 MINUTE");
$stmtDelete->execute();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil - Gestionnaire</title>
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
    <!-- Barre de navigation -->
    <nav class="navbar">
        <div class="navbar-container">
            <h1 id="lib">TogoEthic</h1>
            <ul class="nav-links">
                <li><a href="dashboard_gestionnaire.php">Accueil</a></li>
                <li><a href="gestion_collectes.php">Gérer les Collectes</a></li>
                <li><a href="statistiques.php">Statistiques</a></li>
                <li><a href="logout.php" class="logout-btn">Se Déconnecter</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero">
        <div class="container">
            <h1>Bienvenue dans la gestion des déchets</h1>
            <p>Une plateforme innovante pour gérer efficacement les déchets au Togo 🌱</p>
        </div>
    </header>

    <!-- Notifications Section -->
    <section class="notifications">
        <div class="container">
            <h2>📢 Notifications Récentes</h2>
            <?php if (count($notifications) > 0): ?>
                <ul>
                    <?php foreach ($notifications as $notif): ?>
                        <li>
                            <p><?php echo $notif['message']; ?> - <small><?php echo $notif['date']; ?></small></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Aucune notification récente.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Action Cards Section -->
    <section class="action-cards">
        <div class="container">
            <div class="card">
                <h3>📊 Statistiques des Collectes</h3>
                <p>Consultez les performances et l'efficacité des collectes.</p>
                <a href="statistiques.php" class="btn">Voir les statistiques</a>
            </div>

            <div class="card">
                <h3>💼 Gérer les Collectes</h3>
                <p>Supervisez et gérez les collectes terminées ou en attente.</p>
                <a href="gestion_collectes.php" class="btn">Voir les collectes</a>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 Plateforme de Gestion des Déchets - Togo. Tous droits réservés.</p>
    </footer>
</body>
</html>

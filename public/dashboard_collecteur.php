<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'collecteur') {
    header("Location: auth.html");
    exit();
}

include '../config/database.php';

// Récupérer les notifications
$stmt = $pdo->prepare("SELECT notifications FROM collecteurs WHERE collecteur_id = ?");
$stmt->execute([$_SESSION['id']]);
$collecteur = $stmt->fetch(PDO::FETCH_ASSOC);
$notifications = $collecteur['notifications'] ?? "Aucune nouvelle notification.";

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Collecteur</title>
    <link rel="stylesheet" href="css/accueil_collecteurss.css">
</head>
<body>
    <header class="header">
        <h1>🌍 Gestion des Déchets - Bienvenue Collecteur</h1>
        <nav class="nav">
            <a href="profil_collecteur.php">Mon profil</a>
            <a href="../views/collectes/mes_collecte.php">Mes Collectes</a>
            <a href="logout.php" class="logout">Se Déconnecter</a>
        </nav>
    </header>

    <main class="main">
        <section class="hero">
            <div class="hero-content">
                <h2>Bonjour, <?php echo $_SESSION['nom']; ?> 👋</h2>
                <p>Merci de contribuer activement à la propreté de votre communauté.</p>
            </div>
            <img src="images/collecteur_welcome.png" alt="Collecteur illustration" class="hero-img">
        </section>

        <section class="grid-cards">
            <div class="card">
                <h3>📢 Notifications</h3>
                <p><?= $notifications ?: "Aucune nouvelle notification." ?></p>
            </div>

            <div class="card">
                <h3>🛠️ Mes Collectes</h3>
                <p>Consultez et gérez les collectes en cours.</p>
                <a href="../views/collectes/mes_collecte.php" class="button">Voir mes collectes</a>
            </div>

            <div class="card">
                <h3>👨‍💼 Mon profil</h3>
                <p>Accédez à mon profil. Pour en créer ou modifier</p>
                <a href="profil_collecteur.php" class="button">Voir mon profil</a>
            </div>
        </section>
    </main>

    <footer class="footer">
        <p>&copy; 2025 Gestion des Déchets - Togo. Tous droits réservés.</p>
    </footer>
</body>
</html>

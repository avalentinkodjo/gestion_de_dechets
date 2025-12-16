<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'entreprise') {
    header("Location: auth.html");
    exit();
}

include '../config/database.php';
$stmt = $pdo->prepare("SELECT * FROM demandes WHERE statut = 'en attente'");
$stmt->execute();
$demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil Entreprise - Gestion Déchets</title>
    <link rel="stylesheet" href="css/accueil_entreprisesss.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <header class="main-header">
        <nav class="navbar">
            <div class="logo">♻️ TogoEthic</div>
            <ul class="nav-links">
                <li><a href="#fonctionnalites">Fonctionnalités</a></li>
                <li><a href="#statistiques">Statistiques</a></li>
                <li><a href="#avis">Avis</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
        </nav>
        <div class="hero">
            <h1>Bienvenue <?php echo $_SESSION['nom']; ?> 🌿</h1>
            <p>Optimisez la gestion de vos déchets et contribuez à un environnement plus sain.</p>
            <div class="buttons">
                <a href="recrutement_collecteur.php" class="btn">Recruter un collecteur</a>
                <a href="modifier_collecteur.php" class="btn btn-secondary">Mes collecteurs</a>
            </div>
        </div>
    </header>

    <main>
        <section id="fonctionnalites" class="section features">
            <h2>🌱 Fonctionnalités</h2>
            <div class="cards">
                <div class="card">
                    <i class="fas fa-truck"></i>
                    <h3>Collectes en cours</h3>
                    <p>Suivi des collectes en temps réel.</p>
                    <a href="collectes_en_cours.php">Voir plus</a>
                </div>
                <div class="card">
                    <i class="fas fa-history"></i>
                    <h3>Historique</h3>
                    <p>Gardez une trace des anciennes collectes.</p>
                    <a href="historique_collectes.php">Voir l’historique</a>
                </div>
                <div class="card">
                    <i class="fa-solid fa-message"></i>
                    <h3>Message</h3>
                    <p>Echangez des Conversation avec les citoyens.</p>
                    <a href="messages.php">Voir les messages</a>
                </div>
                <div class="card">
                    <i class="fas fa-leaf"></i>
                    <h3>Collectes en attente</h3>
                    <p>Consultez les demandes de collecte des citoyens.</p>
                    <a href="collecteAttente.php">Voir impact</a>
                </div>
            </div>
        </section>     

        <section id="statistiques" class="section stats">
            <h2>📊 Statistiques & Performances</h2>
            <div class="cards">
                <div class="card">
                    <h3>Statistiques des collectes réalisées</h3>
                    <a href="stats_entreprise.php">Voir statistiques</a>
                </div>
                <div class="card">
                    <h3>Performances des collecteurs</h3>
                    <a href="performace_collecteur.php">Voir performances</a>
                </div>
            </div>
        </section>

        <section id="avis" class="section feedback">
            <h2>🗣️ Avis des clients</h2>
            <p>Consultez les retours les plus récents.</p>
            <a href="avis_collecteurs.php" class="btn">Lire les avis</a>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Gestion des Déchets - Togo | Tous droits réservés</p>
    </footer>
</body>
</html>

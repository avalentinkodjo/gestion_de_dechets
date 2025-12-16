<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'gestionnaire') {
    header("Location: auth.html");
    exit();
}

include '../config/database.php';

// Statistiques des collectes par type de déchet
$stmtDechet = $pdo->prepare("SELECT type_dechet, COUNT(*) AS count FROM demandes WHERE statut = 'validée' GROUP BY type_dechet");
$stmtDechet->execute();
$dechets = $stmtDechet->fetchAll(PDO::FETCH_ASSOC);

// Statistiques sur les collectes par mois
$stmtMois = $pdo->prepare("SELECT MONTH(date_demande) AS mois, COUNT(*) AS count FROM demandes WHERE statut = 'validée' GROUP BY MONTH(date_demande)");
$stmtMois->execute();
$collectesParMois = $stmtMois->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les notifications comme avant
$stmtNotif = $pdo->prepare("SELECT * FROM notifications WHERE destinataire_role = 'gestionnaire' AND statut = 'lu' AND date_vue >= NOW() - INTERVAL 30 MINUTE");
$stmtNotif->execute();
$notifications = $stmtNotif->fetchAll(PDO::FETCH_ASSOC);

// Mettre à jour les notifications comme "lu" avec l'heure de lecture
$stmtUpdate = $pdo->prepare("UPDATE notifications SET statut = 'lu', date_vue = NOW() WHERE destinataire_role = 'gestionnaire' AND statut = 'non_lu'");
$stmtUpdate->execute();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques - Gestionnaire</title>
    <link rel="stylesheet" href="css/home.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    <!-- Section des graphiques -->
    <header class="hero">
        <div class="container">
            <h1>📊 Statistiques des Collectes</h1>
        </div>
    </header>

    <section class="statistics">
        <div class="container">
            <h2>📉 Nombre de Collectes par Type de Déchet</h2>
            <canvas id="dechetChart"></canvas>

            <h2>📆 Nombre de Collectes par Mois</h2>
            <canvas id="moisChart"></canvas>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 Plateforme de Gestion des Déchets - Togo. Tous droits réservés.</p>
    </footer>

    <script>
        // Graphique des collectes par type de déchet
        const dechetCtx = document.getElementById('dechetChart').getContext('2d');
        const dechetChart = new Chart(dechetCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($dechets, 'type_dechet')); ?>,
                datasets: [{
                    label: 'Nombre de Collectes',
                    data: <?php echo json_encode(array_column($dechets, 'count')); ?>,
                    backgroundColor: '#16a085',
                    borderColor: '#1abc9c',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Graphique des collectes par mois
        const moisCtx = document.getElementById('moisChart').getContext('2d');
        const moisChart = new Chart(moisCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($collectesParMois, 'mois')); ?>,
                datasets: [{
                    label: 'Nombre de Collectes',
                    data: <?php echo json_encode(array_column($collectesParMois, 'count')); ?>,
                    backgroundColor: 'rgba(22, 160, 133, 0.2)',
                    borderColor: '#16a085',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

</body>
</html>

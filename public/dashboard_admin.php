<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: auth.html');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Gestion Déchets</title>
    <link rel="stylesheet" href="css/admin_dashboardss.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<header>
    <h1>Tableau de Bord Admin</h1>
    <p>Bienvenue, <?php echo $_SESSION['nom']; ?> 👋</p>
</header>

<div class="sidebar">
    <ul>
        <li><a href="utilisateurs.php"><i class="fas fa-users"></i> Utilisateurs</a></li>
        <li><a href="collecteurs.php"><i class="fas fa-truck"></i> Collecteurs</a></li>
        <li><a href="demandes.php"><i class="fas fa-recycle"></i> Demandes</a></li>
        <li><a href="collectes.php"><i class="fas fa-dumpster"></i> Collectes</a></li>
        <li><a href="messagess.php"><i class="fas fa-envelope"></i> Messages</a></li>
        <li><a href="notificationss.php"><i class="fas fa-bell"></i> Notifications</a></li>
        <li><a href="aviss.php"><i class="fas fa-star"></i> Avis</a></li>
        <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
    </ul>
</div>

<div class="main-content">
    <div class="dashboard-section">

        <div class="card">
            <h3><i class="fas fa-users"></i> Utilisateurs</h3>
            <p>Consulter et gérer tous les citoyens, entreprises, gestionnaires.</p>
        </div>

        <div class="card">
            <h3><i class="fas fa-truck"></i> Collecteurs</h3>
            <p>Gérer les collecteurs recrutés par les entreprises.</p>
        </div>

        <div class="card">
            <h3><i class="fas fa-recycle"></i> Demandes</h3>
            <p>Voir les demandes en attente, validées ou en cours.</p>
        </div>

        <div class="card">
            <h3><i class="fas fa-dumpster"></i> Collectes</h3>
            <p>Suivre les collectes programmées ou terminées.</p>
        </div>

        <div class="card">
            <h3><i class="fas fa-envelope"></i> Messages</h3>
            <p>Messagerie entre utilisateurs et administrateurs.</p>
        </div>

        <div class="card">
            <h3><i class="fas fa-bell"></i> Notifications</h3>
            <p>Voir toutes les notifications envoyées aux utilisateurs.</p>
        </div>

        <div class="card">
            <h3><i class="fas fa-star"></i> Avis</h3>
            <p>Lire les retours des citoyens sur les collecteurs.</p>
        </div>

    </div>
</div>

<footer>
    &copy; 2025 - Plateforme de gestion des déchets | Admin
</footer>

</body>
</html>

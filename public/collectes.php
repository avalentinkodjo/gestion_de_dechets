<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: auth.html');
    exit;
}

include '../config/database.php';

// Récupérer toutes les collectes
$stmt = $pdo->prepare("SELECT c.id, u.nom AS utilisateur, c.localisation, c.type_dechet, c.statut, c.date_demande
                        FROM demandes c
                        JOIN utilisateurs u ON c.user_id = u.id
                        WHERE statut = 'en cours'
                        OR  statut = 'validée'
                        OR  statut = 'en attente_validation'
                        ORDER BY c.date_demande DESC");
$stmt->execute();
$collectes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Collectes - Admin Dashboard</title>
    <link rel="stylesheet" href="css/admin_collectes.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<header>
    <h1>Collectes - Tableau de Bord Admin</h1>
    <p>Gérer les collectes de déchets</p>
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
        <li><a href="auth.html"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
    </ul>
</div>

<div class="main-content">
    <div class="table-container">
        <h2>Liste des Collectes</h2>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Utilisateur</th>
                    <th>Adresse</th>
                    <th>Type de Déchet</th>
                    <th>Statut</th>
                    <th>Date de Demande</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($collectes as $collecte): ?>
                <tr>
                    <td><?php echo $collecte['id']; ?></td>
                    <td><?php echo $collecte['utilisateur']; ?></td>
                    <td><?php echo $collecte['localisation']; ?></td>
                    <td><?php echo ucfirst($collecte['type_dechet']); ?></td>
                    <td>
                        <span class="status <?php echo strtolower($collecte['statut']); ?>"><?php echo ucfirst($collecte['statut']); ?></span>
                    </td>
                    <td><?php echo $collecte['date_demande']; ?></td>
                    
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


</body>
</html>

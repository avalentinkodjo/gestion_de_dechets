<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

include '../config/database.php';

// Récupérer tous les messages
$stmt = $pdo->prepare("SELECT m.id, m.contenu, m.date_envoi, u1.nom AS expediteur, u2.nom AS destinataire
                       FROM messages m
                       JOIN utilisateurs u1 ON m.expediteur_id = u1.id
                       JOIN utilisateurs u2 ON m.destinataire_id = u2.id
                       ORDER BY m.date_envoi DESC");
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Admin Dashboard</title>
    <link rel="stylesheet" href="css/admin_dashboardss.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<header>
    <h1>Gestion des Messages</h1>
    <p>Consulter les messages envoyés et reçus par les utilisateurs.</p>
</header>
<style>
    
/* Style du tableau des messages */
.message-section {
    padding: 20px;
}

.message-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.message-table th, .message-table td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.message-table th {
    background-color: #f4f4f4;
    color: #333;
}

.message-table tr:hover {
    background-color: #f1f1f1;
}


/* Main content */
.main-content {
    margin-left: 270px;
    padding: 20px;
}

footer {
    text-align: center;
    padding: 10px;
    background-color: #34495e;
    color: white;
    position: fixed;
    bottom: 0;
    width: 100%;
}

</style>
<div class="sidebar">
    <ul>
        <li><a href="dashboard_admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="utilisateurs.php"><i class="fas fa-users"></i> Utilisateurs</a></li>
        <li><a href="collecteurs.php"><i class="fas fa-truck"></i> Collecteurs</a></li>
        <li><a href="demandes.php"><i class="fas fa-recycle"></i> Demandes</a></li>
        <li><a href="collectes.php"><i class="fas fa-dumpster"></i> Collectes</a></li>
        <li><a href="notificationss.php"><i class="fas fa-bell"></i> Notifications</a></li>
        <li><a href="aviss.php"><i class="fas fa-star"></i> Avis</a></li>
        <li><a href="auth.html"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
    </ul>
</div>

<div class="main-content">
    <div class="message-section">
        <h2>Liste des Messages</h2>
        <?php if (count($messages) > 0): ?>
            <table class="message-table">
                <thead>
                    <tr>
                        <th>Expéditeur</th>
                        <th>Destinataire</th>
                        <th>Message</th>
                        <th>Date d'envoi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $message): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($message['expediteur']); ?></td>
                            <td><?php echo htmlspecialchars($message['destinataire']); ?></td>
                            <td><?php echo $message['contenu']; ?></td>
                            <td><?php echo htmlspecialchars($message['date_envoi']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucun message à afficher.</p>
        <?php endif; ?>
    </div>
</div>

<footer>
    &copy; 2025 - Plateforme de gestion des déchets | Admin
</footer>

</body>
</html>

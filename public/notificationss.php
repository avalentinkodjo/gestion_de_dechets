<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

// Récupération des notifications
$sql = "SELECT * FROM notifications ORDER BY date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Notifications - Admin</title>
    <link rel="stylesheet" href="css/admin_dashboardss.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<header>
    <h1><i class="fas fa-bell"></i> Notifications</h1>
</header>

<style>

table {
    width: 95%;
    margin: 20px auto;
    border-collapse: collapse;
    background-color: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

table th, table td {
    padding: 12px 15px;
    border-bottom: 1px solid #eee;
    text-align: left;
}

table th {
    background-color: #04a96d;
    color: white;
}

.btn-delete {
    background-color: transparent;
    border: none;
    color: red;
    cursor: pointer;
    font-size: 1.2em;
}
</style>

<div class="sidebar">
    <ul>
        <li><a href="dashboard_admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="notifications.php" class="active"><i class="fas fa-bell"></i> Notifications</a></li>
        <li><a href="auth.html"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
    </ul>
</div>

<div class="main-content">
    <h2>Liste des Notifications</h2>

    <table>
        <thead>
            <tr>
                <th>Message</th>
                <th>Type</th>
                <th>Destinataire</th>
                <th>Statut</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($notifications) > 0): ?>
                <?php foreach ($notifications as $notif): ?>
                    <tr>
                        <td><?= htmlspecialchars($notif['message']) ?></td>
                        <td><?= htmlspecialchars($notif['type']) ?></td>
                        <td><?= htmlspecialchars($notif['destinataire_role']) ?></td>
                        <td><?= $notif['statut'] == 'non_lu' ? 'Non lu' : 'Lu' ?></td>
                        <td><?= $notif['date'] ?></td>
                        <td>
                            <form method="post" action="supprimer_notification.php" onsubmit="return confirm('Supprimer cette notification ?');" autocomplete="off">
                                <input type="hidden" name="id" value="<?= $notif['id'] ?>">
                                <button type="submit" class="btn-delete"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6">Aucune notification trouvée.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>

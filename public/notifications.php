<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['id'])) {
    header("Location: auth.html");
    exit();
}

$user_id = $_SESSION['id'];

$stmt = $pdo->prepare("SELECT * FROM notifications WHERE destinataire_role = ? ORDER BY date DESC");
$stmt->execute([$user_id]);
$notifications = $stmt->fetchAll();

$updateStmt = $pdo->prepare("UPDATE notifications SET statut = 'lu', date_vue = NOW() WHERE destinataire_role = ? AND statut = 'non_lu'");
$updateStmt->execute([$user_id]);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Notifications - Citoyen</title>
    <link rel="stylesheet" href="../public/css/notifications.css">
</head>
<body>
    <header class="header">
        <h1>🌍 TogoEthic - Notifications</h1>
        <a href="dashboard_citoyen.php" class="btn-back">← Retour</a>
    </header>

    <main class="container">
        <section class="notifications-box">
            <h2>📢 Vos Notifications</h2>

            <?php if (empty($notifications)) : ?>
                <div class="notification empty">Aucune notification pour le moment.</div>
            <?php else : ?>
                <?php foreach ($notifications as $notif) : ?>
                    <div class="notification <?= $notif['statut'] == 'non_lu' ? 'unread' : '' ?>">
                        <p><?= htmlspecialchars($notif['message']) ?></p>
                        <span class="date"><?= date('d/m/Y H:i', strtotime($notif['date'])) ?></span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>

    <footer class="footer">
        &copy; 2025 Gestion des Déchets - Togo
    </footer>
</body>
</html>

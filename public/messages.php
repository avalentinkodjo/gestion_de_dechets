<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];

// Récupération des messages
$stmt = $pdo->prepare("
    SELECT m.*, u.nom AS expediteur_nom 
    FROM messages m 
    JOIN utilisateurs u ON m.expediteur_id = u.id 
    WHERE m.destinataire_id = ? OR m.expediteur_id = ? 
    ORDER BY m.date_envoi ASC
");
$stmt->execute([$user_id, $user_id]);
$messages = $stmt->fetchAll();

// Organisation par fil de discussion
$messages_by_parent = [];
foreach ($messages as $message) {
    if ($message['message_parent_id'] === NULL) {
        $messages_by_parent[$message['id']] = ['message' => $message, 'reponses' => []];
    } else {
        $messages_by_parent[$message['message_parent_id']]['reponses'][] = $message;
    }
}

// Marquer comme lus
$stmt = $pdo->prepare("UPDATE messages SET statut = 'lu' WHERE destinataire_id = ?");
$stmt->execute([$user_id]);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Messagerie - Gestion Déchets</title>
    <link rel="stylesheet" href="../public/css/messages.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<!-- Barre de navigation -->
<nav class="navbar">
    <div class="nav-logo">Gestion Déchets</div>
    <ul class="nav-links">
        <li><a href="#" onclick="window.history.back();"><i class="fas fa-home"></i> Accueil</a></li>
    </ul>

</nav>

<div class="container">
    <h2><i class="fas fa-comments"></i> Vos Conversations</h2>

    <?php foreach ($messages_by_parent as $msg_data): ?>
        <div class="message">
            <div class="message-header">
                <i class="fas fa-user"></i>
                <strong><?= $msg_data['message']['expediteur_nom'] ?></strong>
            </div>
            <div class="message-body">
                <p><?= $msg_data['message']['contenu'] ?></p>
                <span class="message-date"><?= date('d/m/Y H:i', strtotime($msg_data['message']['date_envoi'])) ?></span>
            </div>

            <!-- Formulaire de réponse -->
            <form class="reply-form" method="POST" autocomplete="off">
                <input type="hidden" name="message_parent_id" value="<?= $msg_data['message']['id'] ?>">
                <input type="hidden" name="destinataire_id" value="<?= $msg_data['message']['expediteur_id'] ?>">
                <textarea name="contenu" required placeholder="Votre réponse..."></textarea>
                <button type="submit"><i class="fas fa-paper-plane"></i> Répondre</button>
            </form>

            <!-- Réponses -->
            <?php foreach ($msg_data['reponses'] as $reponse): ?>
                <div class="reponse">
                    <div class="message-header">
                        <i class="fas fa-user"></i>
                        <strong><?= htmlspecialchars($reponse['expediteur_nom']) ?></strong>
                    </div>
                    <div class="message-body">
                        <p><?= $reponse['contenu'] ?></p>
                        <span class="message-date"><?= date('d/m/Y H:i', strtotime($reponse['date_envoi'])) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>

<!-- JS AJAX pour envoi sans rechargement -->
<script>
document.querySelectorAll('.reply-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch('repondre_message.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.text())
        .then(response => {
            alert(response);
            window.location.reload(); // Recharge la page pour voir la réponse apparaître
        })
        .catch(err => {
            alert("Erreur lors de l'envoi.");
            console.error(err);
        });
    });
});
</script>

</body>
</html>

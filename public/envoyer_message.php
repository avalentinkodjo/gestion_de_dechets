<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $expediteur_id = $_SESSION['id'];
    $destinataire_id = $_POST['destinataire_id'];
    $contenu = htmlspecialchars($_POST['contenu']);

    $stmt = $pdo->prepare("INSERT INTO messages (expediteur_id, destinataire_id, contenu) VALUES (?, ?, ?)");
    if ($stmt->execute([$expediteur_id, $destinataire_id, $contenu])) {
        $notification = $pdo->prepare("INSERT INTO notifications (message, type, destinataire_role) VALUES (?, 'message', ?)");
        $notification->execute(["Vous avez reçu un nouveau message.", $destinataire_id]);
        $message_success = "Message envoyé avec succès !";
    } else {
        $message_error = "Erreur lors de l'envoi du message.";
    }
}

// Récupérer la liste des entreprises pour permettre aux citoyens d'envoyer des messages
$stmt = $pdo->query("SELECT id, nom FROM utilisateurs WHERE role = 'entreprise'");
$entreprises = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Envoyer un message</title>
    <link rel="stylesheet" href="../public/css/envoyer_message.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

</head>
<body>

    <div class="message-container">
        <h2><i class="fas fa-envelope"></i> Envoyer un message</h2>

        <?php if (isset($message_success)) : ?>
            <p class="success-message"><i class="fas fa-check-circle"></i> <?= $message_success; ?></p>
        <?php elseif (isset($message_error)) : ?>
            <p class="error-message"><i class="fas fa-times-circle"></i> <?= $message_error; ?></p>
        <?php endif; ?>

        <form method="POST" autocomplete="off">
            <div class="input-group">
                <label><i class="fas fa-user-tie"></i> Destinataire (Entreprise) :</label>
                <select name="destinataire_id" required>
                    <?php foreach ($entreprises as $entreprise): ?>
                        <option value="<?= $entreprise['id'] ?>"><?= $entreprise['nom'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="input-group">
                <label><i class="fas fa-comment-alt"></i> Message :</label>
                <textarea name="contenu" required></textarea>
            </div>

            <button type="submit" class="send-btn"><i class="fas fa-paper-plane"></i> Envoyer</button>
        </form>
    </div>

</body>
</html>

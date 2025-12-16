<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['id'])) {
    echo "Utilisateur non connecté.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $expediteur_id = $_SESSION['id'];
    $destinataire_id = $_POST['destinataire_id'];
    $contenu = htmlspecialchars($_POST['contenu']);
    $message_parent_id = $_POST['message_parent_id'];

    $stmt = $pdo->prepare("INSERT INTO messages (expediteur_id, destinataire_id, contenu, message_parent_id) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$expediteur_id, $destinataire_id, $contenu, $message_parent_id])) {
        $notification = "Vous avez un nouveau message de " . $_SESSION['nom'];
        $stmtNotif = $pdo->prepare("INSERT INTO notifications (message, type, destinataire_role, statut) VALUES (?, 'message', ?, 'non_lu')");
        $stmtNotif->execute([$notification, $destinataire_id]);

        echo "Réponse envoyée avec succès !";
    } else {
        echo "Erreur lors de l'envoi de la réponse.";
    }
}

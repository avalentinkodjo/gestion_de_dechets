<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['id'])) {
    exit();
}

$user_id = $_SESSION['id'];

$stmt = $pdo->prepare("
    SELECT m.*, u.nom AS expediteur_nom 
    FROM messages m 
    JOIN utilisateurs u ON m.expediteur_id = u.id 
    WHERE m.destinataire_id = ? OR m.expediteur_id = ? 
    ORDER BY m.date_envoi ASC
");
$stmt->execute([$user_id, $user_id]);
$messages = $stmt->fetchAll();

foreach ($messages as $message) {
    echo "<p><strong>{$message['expediteur_nom']} :</strong> {$message['contenu']}</p>";
}
?>

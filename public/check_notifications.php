<?php
session_start();
include '../config/database.php';

$user_id = $_SESSION['id'];

$stmt = $pdo->prepare("SELECT COUNT(*) AS non_lus FROM messages WHERE destinataire_id = ? AND statut = 'non_lu'");
$stmt->execute([$user_id]);
$data = $stmt->fetch();

echo json_encode(["non_lus" => $data['non_lus']]);
?>

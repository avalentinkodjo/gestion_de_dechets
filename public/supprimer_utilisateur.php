<?php
session_start();
require_once('../config/database.php');

// Vérifie si l'utilisateur est connecté en tant qu'admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: .auth.html');
    exit;
}

// Vérifie si l'ID de l'utilisateur à supprimer est présent
if (!isset($_GET['id'])) {
    header('Location: utilisateurs.php');
    exit;
}

$id = intval($_GET['id']);

// Vérifie que l'utilisateur existe
$check = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$check->execute([$id]);
$utilisateur = $check->fetch(PDO::FETCH_ASSOC);

if (!$utilisateur) {
    echo "Utilisateur introuvable.";
    exit;
}

// Supprimer l'utilisateur
$supprimer = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
$supprimer->execute([$id]);

header("Location: utilisateurs.php?success=1");
exit;
?>

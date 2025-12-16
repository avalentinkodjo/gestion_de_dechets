<?php
include '../config/database.php';
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'entreprise') {
    header("Location: ../public/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: ../public/liste_collecteurs.php");
    exit();
}

$id_collecteur = $_GET['id'];
$entreprise_id = $_SESSION['id'];

// Supprimer le collecteur de la base de données
$stmt = $pdo->prepare("DELETE FROM collecteurs WHERE id = ? AND entreprise_id = ?");
if ($stmt->execute([$id_collecteur, $entreprise_id])) {
    header("Location: ../public/liste_collecteurs.php");
    exit();
} else {
    echo json_encode(['error' => 'Erreur lors de la suppression du collecteur.']);
}
?>

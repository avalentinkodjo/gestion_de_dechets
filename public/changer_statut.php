<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $pdo->prepare("SELECT statut FROM demandes WHERE id = ?");
    $stmt->execute([$id]);
    $current = $stmt->fetchColumn();

    $newStatut = match ($current) {
        'en attente' => 'en cours',
        'en cours' => 'en attente_validation',
        'en attente_validation' => 'validée',
        default => 'validée'
    };

    $update = $pdo->prepare("UPDATE demandes SET statut = ? WHERE id = ?");
    $update->execute([$newStatut, $id]);

    header("Location: demandes.php");
    exit;
}
?>

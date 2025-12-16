<?php
include '../config/database.php';
session_start();

// Vérifie que la requête est bien envoyée via POST et que l'utilisateur est connecté
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];
    $demande_id = $_POST['demande_id'] ?? null;

    if ($demande_id) {
        try {
            // Prépare la requête de mise à jour
            $stmt = $pdo->prepare("UPDATE demandes SET user_id = ?, statut = 'en cours' WHERE id = ?");
            if ($stmt->execute([$user_id, $demande_id])) {
                echo json_encode(['success' => 'La collecte a été acceptée.']);
            } else {
                echo json_encode(['error' => 'Erreur lors de l\'acceptation de la collecte.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erreur de base de données : ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['error' => 'ID de demande manquant.']);
    }
} else {
    echo json_encode(['error' => 'Requête non autorisée.']);
}

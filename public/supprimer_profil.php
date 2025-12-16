<?php
session_start();
include '../config/database.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'collecteur') {
    header("Location: login.php");
    exit();
}

$collecteur_id = $_POST['id'] ?? null;

if ($collecteur_id) {
    // Récupérer la photo du collecteur avant suppression
    $stmt = $pdo->prepare("SELECT photo FROM profil_collecteurs WHERE id = ?");
    $stmt->execute([$collecteur_id]);
    $collecteur = $stmt->fetch();

    // Supprimer la photo si elle existe
    if ($collecteur && !empty($collecteur['photo'])) {
        $photo_path = "../uploads/photos/" . $collecteur['photo'];
        if (file_exists($photo_path)) {
            unlink($photo_path); // Supprime la photo du serveur
        }
    }

    // Supprimer le profil du collecteur
    $stmt = $pdo->prepare("DELETE FROM profil_collecteurs WHERE id = ?");
    if ($stmt->execute([$collecteur_id])) {
        echo json_encode(['success' => 'Votre profil a été supprimé avec succès.']);
    } else {
        echo json_encode(['success' => "Une erreur s'est produite lors de la suppression."]);
    }
}

?>

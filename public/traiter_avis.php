<?php
session_start();
include '../config/database.php';

// Vérifier si l'utilisateur est bien un citoyen
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'citoyen') {
    die("Accès refusé.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $citoyen_id = $_POST['citoyen_id'];
    $collecteur_id = $_POST['collecteur_id'];
    $demande_id = $_POST['demande_id'];
    $note = $_POST['note'];
    $commentaire = htmlspecialchars($_POST['commentaire']);

    // Vérifier si un avis a déjà été laissé pour cette demande
    $stmt = $pdo->prepare("SELECT * FROM avis WHERE demande_id = ?");
    $stmt->execute([$demande_id]);

    if ($stmt->rowCount() > 0) {
        // Si un avis existe déjà, retourner un message JSON
        echo json_encode(["message" => "Vous avez déjà laissé un avis pour cette collecte."]);
        exit();
    }

    // Insérer l'avis dans la base
    $stmt = $pdo->prepare("INSERT INTO avis (citoyen_id, collecteur_id, demande_id, note, commentaire) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$citoyen_id, $collecteur_id, $demande_id, $note, $commentaire]);

    // Retourner un message de succès en JSON avec une redirection vers la page du tableau de bord
    echo json_encode([
        "message" => "Avis enregistré avec succès.",
        "redirect" => "dashboard_citoyen.php"  // Redirection après soumission réussie
    ]);
}
?>

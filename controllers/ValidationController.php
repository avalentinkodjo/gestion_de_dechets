<?php
include '../config/database.php';

if (isset($_POST['valider']) || isset($_POST['annuler'])) {
    $demande_id = $_POST['demande_id'];
    $nouveau_statut = isset($_POST['valider']) ? 'validée' : 'annulée';

    // Mettre à jour le statut de la collecte
    $stmt = $pdo->prepare("UPDATE demandes SET statut = ? WHERE id = ?");
    if ($stmt->execute([$nouveau_statut, $demande_id])) {
        // Libérer le collecteur si la collecte est terminée ou annulée
        $stmt = $pdo->prepare("UPDATE collecteurs SET statut = 'disponible' 
                               WHERE id = (SELECT collecteur_id FROM demandes WHERE id = ?)");
        $stmt->execute([$demande_id]);

        // Message de succès
        $_SESSION['success'] = "✅ La collecte a été mise à jour avec succès.";
    } else {
        // Message d'erreur
        $_SESSION['error'] = "❌ Erreur lors de la mise à jour.";
    }

    // Redirection vers la page de gestion des collectes
    header("Location: ../public/gestion_collectes.php");
    exit();
}
?>

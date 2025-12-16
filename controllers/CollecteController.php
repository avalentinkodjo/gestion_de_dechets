<?php
session_start();
include '../config/database.php';

if (isset($_POST['confirmer'])) {
    $demande_id = $_POST['demande_id'];

    // Traitement de la photo
    $preuve_photo = null;
    if (!empty($_FILES['preuve']['name'])) {
        $upload_dir = "../uploads/";
        $photo_nom = time() . "_" . basename($_FILES['preuve']['name']);
        $preuve_photo = $upload_dir . $photo_nom;

        if (move_uploaded_file($_FILES['preuve']['tmp_name'], $preuve_photo)) {
            $preuve_photo = $photo_nom; // Stocker uniquement le nom du fichier
        } else {
            echo json_encode(['error' => 'Erreur lors du téléchargement de la photo.']);
            exit();
        }
    }

    // Mise à jour du statut de la collecte et enregistrement de la preuve
    $stmt = $pdo->prepare("UPDATE demandes SET confirmation_collecteur = 1, statut = 'en attente_validation', preuve_photo = ? WHERE id = ?");
    $stmt->execute([$photo_nom, $demande_id]);

    // Notifier le gestionnaire
    $stmtNotif = $pdo->prepare("INSERT INTO notifications (message, type, destinataire_role) VALUES (?, ?, ?)");
    if ($stmtNotif->execute(["Une collecte est terminée et en attente de validation.", "collecte", "gestionnaire"])) {
        $_SESSION['success'] = "✅ Votre demande de validation a été soumise avec succès.";
    } else {
        $_SESSION['error'] = "❌ Erreur lors de la soumission de la demande de validation.";
    }
    header("Location: ../views/collectes/mes_collecte.php");
    exit();
    
}
?>

<?php
include '../config/database.php';
session_start();

if (isset($_POST['modifier_collecteur'])) {
    $id_collecteur = $_POST['id'];
    $nom = trim($_POST['nom']);
    $telephone = trim($_POST['telephone']);
    $email = trim($_POST['email']);
    $entreprise_id = $_SESSION['id'];

    // Vérifier si l'email est déjà utilisé par un autre collecteur
    $stmt = $pdo->prepare("SELECT id FROM collecteurs WHERE email = ? AND id != ?");
    $stmt->execute([$email, $id_collecteur]);
    if ($stmt->fetch()) {
        echo json_encode(['error' => 'Cet email est déjà utilisé.']);
        exit();
    }

    // Mettre à jour le collecteur
    $stmt = $pdo->prepare("UPDATE collecteurs SET nom = ?, telephone = ?, email = ? WHERE id = ? AND entreprise_id = ?");
    if ($stmt->execute([$nom, $telephone, $email, $id_collecteur, $entreprise_id])) {
        echo json_encode(['success' => 'Collecteur modifié avec succès.']);
        header("Location: ../public/liste_collecteurs.php");
        exit();
    } else {
        echo json_encode(['error' => 'Erreur lors de la modification.']);
    }
}
?>

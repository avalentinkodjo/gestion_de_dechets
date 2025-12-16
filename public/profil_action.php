<?php
session_start();
include '../config/database.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    echo json_encode(['error' => 'Utilisateur non connecté. Veuillez vous connecter.']);
    exit();
}

// Création ou mise à jour du profil
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $utilisateur_id = $_SESSION['id'];

    $nom = $_POST['nom'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $adresse = $_POST['adresse'];
    $ville = $_POST['ville'];
    $photo = $_FILES['photo']['name'] ?? null; 
    $photo_cni = $_FILES['carte_identite']['name'] ?? null;

    move_uploaded_file($_FILES['photo']['tmp_name'], '../uploads/' . $photo);
    
    if (!empty($_FILES['carte_identite']['name'])) {
        move_uploaded_file($_FILES['carte_identite']['tmp_name'], '../uploads/' . $photo_cni);
    }

    // Vérifier si le profil existe
    $stmt = $pdo->prepare("SELECT id FROM profil_collecteurs WHERE user_id = ?");
    $stmt->execute([$utilisateur_id]); 
    $profil_exist = $stmt->fetch();

    if ($profil_exist) {
        $id_profil = $profil_exist['id'];
        // Mise à jour du profil 
        $stmt = $pdo->prepare("UPDATE profil_collecteurs SET nom = ?, telephone = ?, email = ?, adresse = ?, ville = ?, photo = ?, carte_identite = ?  WHERE id = ?");
        if ($stmt->execute([$nom, $telephone, $email, $adresse, $ville, $photo, $photo_cni, $id_profil])) {
            echo json_encode(['success' => 'Profil mis à jours avec succès.']);
        } else {
            echo json_encode(['success' => 'Erreur lors de la mise à jour du profil.']);
        }
    } else {
        // Création du profil 
        $stmt = $pdo->prepare("INSERT INTO profil_collecteurs (user_id, nom, telephone, email, adresse, ville, photo, carte_identite) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$utilisateur_id, $nom, $telephone, $email, $adresse, $ville, $photo, $photo_cni])) {
            echo json_encode(['success' => 'Profil enregistrer avec succès.']);
        } else {
            echo json_encode(['success' => 'Erreur lors de la création du profil.']);
        }
    }

}
?>

<?php
session_start();
header('Content-Type: application/json'); // On indique qu'on renvoie du JSON

include '../config/database.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    echo json_encode(['error' => 'Utilisateur non connecté. Veuillez vous connecter.']);
    exit();
}

// Vérifie si la requête est bien une demande de collecte
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $localisation = $_POST['localisation'] ?? '';
    $type_dechet = $_POST['type_dechet'] ?? '';
    $date_heure = $_POST['date_heure'] ?? '';
    $latitude = $_POST['latitude'] ?? null;
    $longitude = $_POST['longitude'] ?? null;

    // Validation basique
    if (empty($localisation) || empty($type_dechet) || empty($date_heure)) {
        echo json_encode(['error' => 'Veuillez remplir tous les champs requis.']);
        exit();
    }

    // Traitement de la photo
    $photo_dechet = null;
    if (!empty($_FILES['photo_dechet']['name'])) {
        $upload_dir = '../uploads/';
        $photo_nom = time() . '_' . basename($_FILES['photo_dechet']['name']); // Nom unique
        $photo_chemin = $upload_dir . $photo_nom;

        if (move_uploaded_file($_FILES['photo_dechet']['tmp_name'], $photo_chemin)) {
            $photo_dechet = $photo_nom; // Stocke uniquement le nom en base
        } else {
            echo json_encode(['error' => 'Erreur lors du téléchargement de la photo.']);
            exit();
        }
    }

    // Insertion dans la base de données
    try {
        $stmt = $pdo->prepare("INSERT INTO demandes (client_id, localisation, type_dechet, date_heure, latitude, longitude, photo) 
                               VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_SESSION['id'],
            $localisation,
            $type_dechet,
            $date_heure,
            $latitude,
            $longitude,
            $photo_dechet
        ]);

        echo json_encode(['success' => 'Votre demande de collecte a été soumise avec succès.']);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Erreur lors de la soumission de la demande.']);
    }
}
?>

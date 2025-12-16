<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'entreprise') {
    header("Location: login.php");
    exit();
}

include '../config/database.php';

$stmt = $pdo->prepare("
    SELECT d.localisation, d.type_dechet, d.date_heure, d.photo,
           d.date_demande, d.preuve_photo, c.nom, c.telephone, c.email, c.adresse, c.ville
    FROM demandes d
    JOIN collecteurs c ON d.collecteur_id = c.id
    JOIN utilisateurs u ON c.entreprise_id = u.id
    WHERE d.statut = 'validée'
    AND u.id = :id_utilisateur
");
$stmt->execute(['id_utilisateur' => $_SESSION['id']]);
$collectes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique des collectes</title>
    <link rel="stylesheet" href="css/historiques.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>

<div class="container">
    <a href="dashboard_entreprise.php" class="btn-retour"><i class="fas fa-arrow-left"></i> Retour</a>
    <h1><i class="fas fa-history"></i> Historique des collectes</h1>

    <div class="grid">
        <?php foreach ($collectes as $collecte): ?>
            <div class="card">
                <div class="card-section">
                    <h2><i class="fas fa-trash-alt"></i> Collecte</h2>
                    <p><strong><i class="fas fa-map-marker-alt"></i> Localisation :</strong> <?= htmlspecialchars($collecte['localisation']) ?></p>
                    <p><strong><i class="fas fa-recycle"></i> Type :</strong> <?= ucfirst($collecte['type_dechet']) ?></p>
                    <p><strong><i class="fas fa-calendar-plus"></i> Demande :</strong> <?= $collecte['date_demande'] ?></p>
                    <p><strong><i class="fas fa-calendar-check"></i> Collecte :</strong> <?= $collecte['date_heure'] ?></p>

                    <div class="photo-section">
                        <p><strong><i class="fas fa-camera"></i> Déchets :</strong></p>
                        <?php if ($collecte['photo']): ?>
                            <img src="../uploads/<?= htmlspecialchars($collecte['photo']) ?>" class="photo">
                        <?php else: ?>
                            <em>Aucune photo</em>
                        <?php endif; ?>

                        <p><strong><i class="fas fa-check-circle"></i> Preuve :</strong></p>
                        <?php if ($collecte['preuve_photo']): ?>
                            <img src="../uploads/<?= htmlspecialchars($collecte['preuve_photo']) ?>" class="photo">
                        <?php else: ?>
                            <em>Aucune preuve</em>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card-section">
                    <h2><i class="fas fa-user"></i> Collecteur</h2>
                    <p><strong><i class="fas fa-user-tie"></i> Nom :</strong> <?= ucfirst($collecte['nom']) ?></p>
                    <p><strong><i class="fas fa-phone"></i> Téléphone :</strong> <?= htmlspecialchars($collecte['telephone']) ?></p>
                    <p><strong><i class="fas fa-envelope"></i> Email :</strong> <?= htmlspecialchars($collecte['email']) ?></p>
                    <p><strong><i class="fas fa-map"></i> Adresse :</strong> <?= htmlspecialchars($collecte['adresse']) ?>, <?= htmlspecialchars($collecte['ville']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>

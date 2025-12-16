<?php
session_start();
require_once '../config/database.php'; // fichier de connexion PDO

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: auth.html");
    exit;
}

// Récupération des demandes avec info utilisateur et collecteur
$sql = "
    SELECT d.*, 
           u.nom AS nom_demandeur, 
           c.nom AS nom_collecteur 
    FROM demandes d
    LEFT JOIN utilisateurs u ON d.user_id = u.id
    LEFT JOIN collecteurs c ON d.collecteur_id = c.id
    ORDER BY d.date_demande DESC
";

$stmt = $pdo->query($sql);
$demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Demandes de Collecte - Admin</title>
    <link rel="stylesheet" href="css/admin_collecteur.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<header>
    <i class="fas fa-recycle"></i> Liste des Demandes de Collecte
</header>

<!-- Bouton retour -->
<a href="dashboard_admin.php" class="return-btn"><i class="fas fa-arrow-left"></i> Retour à l'Accueil</a>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Demandeur</th>
                <th>Collecteur</th>
                <th>Type Déchet</th>
                <th>Localisation</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Photo</th>
                <th>Preuve</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($demandes as $demande): ?>
                <tr>
                    <td><?= $demande['id'] ?></td>
                    <td><?= htmlspecialchars($demande['nom_demandeur'] ?? 'Non assigné') ?></td>
                    <td><?= $demande['nom_collecteur'] ?? 'Non assigné' ?></td>
                    <td><?= ucfirst($demande['type_dechet']) ?></td>
                    <td><?= $demande['localisation'] ?? 'Non assigné' ?></td>
                    <td><?= $demande['date_heure'] ?></td>
                    <td><strong><?= strtoupper($demande['statut']) ?></strong></td>
                    <td>
                        <?php if ($demande['photo']): ?>
                            <img src="../uploads/<?= $demande['photo'] ?>" alt="photo" class="photo">
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($demande['preuve_photo']): ?>
                            <a href="../uploads/<?= $demande['preuve_photo'] ?>" target="_blank">Voir</a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="changer_statut.php?id=<?= $demande['id'] ?>" class="action">Changer Statut</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>

<?php
session_start();
include '../config/database.php';

// Vérifier si l'utilisateur est une entreprise
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'entreprise') {
    header("Location: login.php");
    exit();
}

$entreprise_id = $_SESSION['id'];

// Récupérer les avis des collecteurs de cette entreprise
$stmt = $pdo->prepare("
    SELECT a.note, a.commentaire, a.date_avis, c.nom AS nom_collecteur, u.nom AS nom_citoyen
    FROM avis a
    JOIN collecteurs c ON a.collecteur_id = c.id
    JOIN utilisateurs u ON a.citoyen_id = u.id
    WHERE c.entreprise_id = ?
    ORDER BY a.date_avis DESC
");
$stmt->execute([$entreprise_id]);
$avis = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avis sur les collecteurs</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../public/css/avis_collecteurss.css">
</head>
<body>
        <!-- Bouton de retour -->
        <div class="back-button-container">
            <button class="back-button" onclick="window.history.back()">🔙 Retour</button>
        </div>

    <div class="container">
        <h1><i class="fas fa-star"></i> Avis sur vos collecteurs</h1>
        
        <?php if (empty($avis)) : ?>
            <p class="no-reviews">Aucun avis disponible pour l’instant.</p>
        <?php else : ?>
            <table class="avis-table">
                <thead>
                    <tr>
                        <th><i class="fas fa-user"></i> Collecteur</th>
                        <th><i class="fas fa-user-circle"></i> Citoyen</th>
                        <th><i class="fas fa-star"></i> Note</th>
                        <th><i class="fas fa-comment"></i> Commentaire</th>
                        <th><i class="fas fa-calendar"></i> Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($avis as $a) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($a['nom_collecteur']); ?></td>
                            <td><?php echo htmlspecialchars($a['nom_citoyen']); ?></td>
                            <td class="stars">
                                <?php for ($i = 0; $i < $a['note']; $i++) : ?>
                                    <i class="fas fa-star"></i>
                                <?php endfor; ?>
                            </td>
                            <td><?php echo htmlspecialchars($a['commentaire']); ?></td>
                            <td><?php echo date("d/m/Y", strtotime($a['date_avis'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'entreprise') {
    header("Location: login.php");
    exit();
}

include '../config/database.php';

$stmt = $pdo->prepare("SELECT * FROM collecteurs WHERE entreprise_id = ?");
$stmt->execute([$_SESSION['id']]);
$collecteurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collecteurs Recrutés</title>
    <link rel="stylesheet" href="css/liste_collecteurs.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-users"></i> Mes Collecteurs</h1>
            <div class="buttons">
                <a href="recrutement_collecteur.php" class="btn green"><i class="fas fa-plus-circle"></i> Recruter</a>
                <a href="dashboard_entreprise.php" class="btn back"><i class="fas fa-arrow-left"></i> Retour</a>
            </div>
        </div>

        <div class="collecteur-list">
            <?php if (empty($collecteurs)): ?>
                <p class="no-data">😕 Aucun collecteur recruté pour l’instant.</p>
            <?php else: ?>
                <?php foreach ($collecteurs as $collecteur): ?>
                    <div class="collecteur-card">
                        <div class="infos">
                            <h3><i class="fas fa-user"></i> <?= htmlspecialchars($collecteur['nom']) ?></h3>
                            <p><i class="fas fa-phone-alt"></i> <?= htmlspecialchars($collecteur['telephone']) ?></p>
                            <p><i class="fas fa-envelope"></i> <?= htmlspecialchars($collecteur['email']) ?></p>
                            <p><i class="fas fa-check-circle"></i> Statut : <?= ucfirst($collecteur['statut']) ?></p>
                        </div>
                        <div class="actions">
                            <a href="modifier_collecteur.php?id=<?= $collecteur['id'] ?>" class="btn blue"><i class="fas fa-edit"></i> Modifier</a>
                            <a href="../controllers/SupprimerCollecteurController.php?id=<?= $collecteur['id'] ?>" class="btn red" onclick="return confirm('Voulez-vous vraiment virer ce collecteur ?');"><i class="fas fa-trash-alt"></i> Virer</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

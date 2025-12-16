<?php
session_start();

// Lire les messages de session
$successMessage = $_SESSION['success'] ?? null;
$errorMessage = $_SESSION['error'] ?? null;

// Nettoyer la session pour ne pas afficher plusieurs fois
unset($_SESSION['success'], $_SESSION['error']);


if (!isset($_SESSION['id']) || $_SESSION['role'] != 'gestionnaire') {
    header("Location: login.php");
    exit();
}

include '../config/database.php';


// Récupérer les collectes à valider
$stmt = $pdo->prepare("SELECT * FROM demandes WHERE statut = 'en attente_validation'");
$stmt->execute();
$collectes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Collectes - Validation</title>
    <link rel="stylesheet" href="css/gestions.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="dashboard_gestionnaire.php" class="btn-back"><i class="fas fa-arrow-left"></i> Retour</a>
            <h1><i class="fas fa-recycle"></i> Collectes à Valider</h1>
        </div>
        
        <div class="collectes-container">
            <?php foreach ($collectes as $collecte): ?>
                <div class="collecte-card">
                    <h2><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($collecte['localisation']); ?></h2>
                    <p><i class="fas fa-trash-alt"></i> Type de déchet : <strong><?php echo ucfirst(htmlspecialchars($collecte['type_dechet'])); ?></strong></p>
                    <div class="preuve">
                        <p><i class="fas fa-camera"></i> Preuve :</p>
                        <img src="../uploads/<?php echo htmlspecialchars($collecte['preuve_photo']); ?>" alt="Preuve de collecte">
                    </div>
                    <form action="../controllers/ValidationController.php" method="post" autocomplete="off">
                        <input type="hidden" name="demande_id" value="<?php echo $collecte['id']; ?>">
                        <button type="submit" name="valider" class="btn btn-valider"><i class="fas fa-check-circle"></i> Valider</button>
                        <button type="submit" name="annuler" class="btn btn-annuler"><i class="fas fa-times-circle"></i> Annuler</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if ($successMessage): ?>
        <script>alert("<?php echo addslashes($successMessage); ?>");</script>
    <?php endif; ?>

    <?php if ($errorMessage): ?>
        <script>alert("<?php echo addslashes($errorMessage); ?>");</script>
    <?php endif; ?>

</body>
</html>

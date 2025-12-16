<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'entreprise') {
    header("Location: login.php");
    exit();
}

include '../config/database.php';

$stmt = $pdo->prepare("SELECT * FROM demandes WHERE user_id = ? AND statut = 'en cours' AND collecteur_id IS NULL");
$stmt->execute([$_SESSION['id']]);
$demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM collecteurs WHERE entreprise_id = ? AND statut = 'disponible'");
$stmt->execute([$_SESSION['id']]);
$collecteurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Assignation d'un collecteur</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/collecte_en_courss.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <div class="top-bar">
        <a href="dashboard_entreprise.php" class="btn-back"><i class="fas fa-arrow-left"></i> Retour</a>
        <h1><i class="fas fa-recycle"></i> Assignation des collecteurs</h1>
    </div>

    <div class="container">
        <div class="grid">
        <?php foreach ($demandes as $demande): ?>
        
        <div class="card">
            <h2><i class="fas fa-map-marker-alt"></i> <?php echo $demande['localisation']; ?></h2>
            <p><strong><i class="fas fa-dumpster"></i> Type de déchet :</strong> <?php echo ucfirst($demande['type_dechet']); ?></p>
            <p><strong><i class="fas fa-clock"></i> Date & Heure :</strong> <?php echo $demande['date_heure']; ?></p>

            <form action="../controllers/AssignCollecteurController.php" method="post" autocomplete="off">
                <input type="hidden" name="demande_id" value="<?php echo $demande['id']; ?>">

                <label for="collecteur_id"><i class="fas fa-user-tie"></i> Sélectionner un collecteur :</label>
                <select name="collecteur_id" required>
                    <option value="">-- Choisissez --</option>
                    <?php foreach ($collecteurs as $collecteur): ?>
                    <option value="<?php echo $collecteur['id']; ?>">
                        <?php echo $collecteur['nom'] . ' - ' . $collecteur['telephone']; ?>
                    </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" name="assigner_collecteur">
                    <i class="fas fa-check-circle"></i> Assigner
                </button>
            </form>
        </div>
        
        <?php endforeach; ?>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
    <script>alert("✅ Le collecteur a été assigné avec succès !");</script>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
    <script>alert("❌ Une erreur est survenue lors de l’assignation.");</script>
    <?php endif; ?>

</body>
</html>

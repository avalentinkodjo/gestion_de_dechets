<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'entreprise') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: liste_collecteurs.php");
    exit();
}

$id_collecteur = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM collecteurs WHERE id = ? AND entreprise_id = ?");
$stmt->execute([$id_collecteur, $_SESSION['id']]);
$collecteur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$collecteur) {
    echo "Collecteur introuvable.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Collecteur</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/modifier_collecteur.css">
</head>
<body>
    <div class="modifier-container">
        <div class="header">
            <h1><i class="fas fa-user-edit"></i> Modifier un Collecteur</h1>
            <a href="liste_collecteurs.php" class="btn-back"><i class="fas fa-arrow-left"></i> Retour</a>
        </div>

        <form action="../controllers/ModifierCollecteurController.php" method="post" class="modifier-form" autocomplete="off">
            <input type="hidden" name="id" value="<?php echo $collecteur['id']; ?>">

            <div class="form-group">
                <label for="nom"><i class="fas fa-user"></i> Nom :</label>
                <input type="text" name="nom" value="<?php echo htmlspecialchars($collecteur['nom']); ?>" required>
            </div>

            <div class="form-group">
                <label for="telephone"><i class="fas fa-phone"></i> Téléphone :</label>
                <input type="text" name="telephone" value="<?php echo htmlspecialchars($collecteur['telephone']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Email :</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($collecteur['email']); ?>" required>
            </div>

            <button type="submit" name="modifier_collecteur" class="btn-submit">
                <i class="fas fa-save"></i> Enregistrer les modifications
            </button>
        </form>
    </div>
</body>
</html>

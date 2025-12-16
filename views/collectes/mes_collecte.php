<?php
session_start();
$successMessage = $_SESSION['success'] ?? null;
$errorMessage = $_SESSION['error'] ?? null;

// Nettoyer la session pour ne pas afficher plusieurs fois
unset($_SESSION['success'], $_SESSION['error']);

include '../../config/database.php';

// Vérifier si le collecteur est connecté
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'collecteur') {
    header("Location: ../../public/login.php");
    exit();
}

$collecteur_id = $_SESSION['id'];

// Récupérer les collectes assignées à ce collecteur
$stmt = $pdo->prepare("SELECT *
                        FROM demandes d 
                        WHERE d.collecteur_id = ?
                        AND statut = 'en cours'
                        AND confirmation_collecteur = 0");
$stmt->execute([$collecteur_id]);
$collectes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📋 Mes Collectes</title>
    <link rel="stylesheet" href="../../public/css/mes_collectes.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <div class="container">
        <header>
            <div class="header-content">
                <h1>📋 Mes Collectes Assignées</h1>
                <a href="../../public/dashboard_collecteur.php" class="btn-logout">Retour</a>
            </div>
        </header>

        <section class="collectes-list">
            <?php if (count($collectes) > 0): ?>
                <table class="collectes-table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-map-marker-alt"></i> Localisation</th>
                            <th><i class="fas fa-recycle"></i> Type de Déchet</th>
                            <th><i class="fas fa-calendar-alt"></i> Date & Heure</th>
                            <th><i class="fas fa-camera"></i> Photo</th>
                            <th><i class="fas fa-tasks"></i> Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($collectes as $collecte): ?>
                            <tr>
                                <td>
                                    <?php echo htmlspecialchars($collecte['localisation']); ?>
                                    <br><br><a href="localisation_collecte.php?id=<?= $collecte['id'] ?>" class="btn-loc">
                                        <i class="fas fa-map-marked-alt"></i> Voir
                                    </a>
                                </td>
                                <td><?php echo htmlspecialchars($collecte['type_dechet']); ?></td>
                                <td><?php echo htmlspecialchars($collecte['date_heure']); ?></td>
                                <td>
                                    <?php if (!empty($collecte['photo'])): ?>
                                        <img src="../../uploads/<?php echo htmlspecialchars($collecte['photo']); ?>" class="collecte-photo" onclick="openModal(this)">
                                    <?php else: ?>
                                        Aucune photo
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="statut <?php echo htmlspecialchars($collecte['statut']); ?>">
                                        <?php echo htmlspecialchars($collecte['statut']); ?>
                                    </span>
                                </td>
                                <td>
                                    <form action="../../controllers/CollecteController.php" method="POST" enctype="multipart/form-data" autocomplete="off">
                                        <input type="hidden" name="demande_id" value="<?php echo $collecte['id']; ?>">
                                        <label for="preuve">Ajouter une preuve (photo) :</label>
                                        <input type="file" name="preuve" required>
                                        <br>
                                        <button type="submit" name="confirmer" class="btn-loc">✅ Collecte Terminée</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-collecte">🚮 Aucune collecte assignée pour le moment.</p>
            <?php endif; ?>
        </section>

        <!-- Modale d'affichage de l'image -->
        <div id="modal" class="modal">
            <span class="close" onclick="closeModal()">&times;</span>
            <img class="modal-content" id="modal-img">
        </div>
    </div>

    <script src="../../public/js/mes_collecte.js"></script>
    <?php if ($successMessage): ?>
        <script>alert("<?php echo addslashes($successMessage); ?>");</script>
    <?php endif; ?>

    <?php if ($errorMessage): ?>
        <script>alert("<?php echo addslashes($errorMessage); ?>");</script>
    <?php endif; ?>

</body>
</html>

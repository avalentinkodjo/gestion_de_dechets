<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'citoyen') {
    header("Location: login.php");
    exit();
}

$client_id = $_SESSION['id'];

// Récupérer l'historique des demandes
$stmt = $pdo->prepare("SELECT d.id, d.localisation, d.type_dechet, d.date_heure, d.statut, d.preuve_photo, d.collecteur_id, 
                              u.nom AS collecteur_nom
                       FROM demandes d
                       LEFT JOIN utilisateurs u ON d.collecteur_id = u.id
                       WHERE d.client_id = ?
                       ORDER BY d.date_demande DESC");
$stmt->execute([$client_id]);
$demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique des Collectes</title>
    <link rel="stylesheet" href="../public/css/historique_clients.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <header>
        <h1><i class="fas fa-recycle"></i> Historique de Collecte</h1>
        <p>Retrouvez toutes vos demandes passées et l'état de leur traitement.</p>
        <div>
            <a href="dashboard_citoyen.php" class="btn">Acceuille</a>
        </div>
    </header>

    <main class="historique-container">
        <?php if (count($demandes) === 0): ?>
            <p class="empty">Aucune collecte enregistrée pour le moment.</p>
        <?php else: ?>
            <div class="cards-container">
                <?php foreach ($demandes as $demande): ?>
                    <div class="card">
                        <div class="card-header">
                            <h2><i class="fas fa-trash-alt"></i> <?php echo ucfirst($demande['type_dechet']); ?></h2>
                            <span class="statut <?php echo strtolower(str_replace(' ', '-', $demande['statut'])); ?>">
                                <?php echo ucfirst($demande['statut']); ?>
                            </span>
                        </div>
                        <p><i class="fas fa-map-marker-alt"></i> <?php echo $demande['localisation']; ?></p>
                        <p><i class="far fa-calendar-alt"></i> <?php echo $demande['date_heure']; ?></p>
                        <p><i class="fas fa-user"></i> Collecteur : 
                            <?php echo $demande['collecteur_nom'] ?? "Non assigné"; ?>
                        </p>

                        <?php if ($demande['preuve_photo']): ?>
                            <div class="preuve-photo">
                                <a href="../uploads/<?php echo $demande['preuve_photo']; ?>" target="_blank">
                                    <img src="../uploads/<?php echo $demande['preuve_photo']; ?>" alt="Preuve de collecte">
                                </a>
                            </div>
                        <?php else: ?>
                            <p><i class="fas fa-times-circle"></i> Aucune preuve photo</p>
                        <?php endif; ?>

                        <?php if ($demande['statut'] === 'validée'): ?>
                            <a href="avis.php?demande_id=<?php echo $demande['id']; ?>" class="btn-avis">
                                <i class="fas fa-star"></i> Laisser un avis
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2025 Gestion des Déchets - Togo</p>
    </footer>
</body>
</html>

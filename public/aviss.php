<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

require_once '../config/database.php';

// Liste des collecteurs pour le menu déroulant
$collecteurs = $pdo->query("SELECT id, nom FROM collecteurs")->fetchAll(PDO::FETCH_ASSOC);

// Récupère les filtres sélectionnés
$filtre_collecteur = isset($_GET['collecteur']) ? intval($_GET['collecteur']) : 0;
$filtre_note = isset($_GET['note']) ? intval($_GET['note']) : 0;

// Construction de la requête SQL dynamique
$sql = "SELECT a.id, a.note, a.commentaire, a.date_avis,
            u.nom AS citoyen_nom,
            c.nom AS collecteur_nom
        FROM avis a
        INNER JOIN utilisateurs u ON a.citoyen_id = u.id
        INNER JOIN collecteurs c ON a.collecteur_id = c.id
        WHERE 1=1";

$params = [];

if ($filtre_collecteur > 0) {
    $sql .= " AND a.collecteur_id = ?";
    $params[] = $filtre_collecteur;
}

if ($filtre_note > 0) {
    $sql .= " AND a.note = ?";
    $params[] = $filtre_note;
}

$sql .= " ORDER BY a.date_avis DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$avis = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Avis</title>
    <link rel="stylesheet" href="css/admin_dashboardss.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .avis-container {
            margin-left: 220px;
            padding: 20px;
        }
        .avis-card {
            background: #f9f9f9;
            padding: 15px;
            margin-bottom: 15px;
            border-left: 5px solid #28a745;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .avis-card h4 {
            margin: 0;
            color: #333;
        }
        .avis-card .note {
            color: #ffc107;
        }
        .avis-card .date {
            font-size: 0.9em;
            color: #666;
        }

        form {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        form select, form button {
            padding: 5px 10px;
            border-radius: 5px;
        }

    </style>
</head>
<body>

<div class="sidebar">
    <!-- (réutilise le même menu que admin_dashboard.php) -->
    <ul>
        <li><a href="dashboard_admin.php"><i class="fas fa-home"></i> Accueil</a></li>
        <li><a href="utilisateurs.php"><i class="fas fa-users"></i> Utilisateurs</a></li>
        <li><a href="aviss.php" class="active"><i class="fas fa-star"></i> Avis</a></li>
        <li><a href="auth.html"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
    </ul>
</div>

<div class="avis-container">
    <br><br>
    <h2><i class="fas fa-star"></i> Avis des citoyens sur les collecteurs</h2>
    <form method="get" style="margin-bottom: 20px;" autocomplete="off">
        <label for="collecteur">Filtrer par collecteur :</label>
        <select name="collecteur" id="collecteur">
            <option value="0">-- Tous les collecteurs --</option>
            <?php foreach ($collecteurs as $c): ?>
                <option value="<?= $c['id'] ?>" <?= ($filtre_collecteur == $c['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="note">Filtrer par note :</label>
        <select name="note" id="note">
            <option value="0">-- Toutes les notes --</option>
            <?php for ($i = 5; $i >= 1; $i--): ?>
                <option value="<?= $i ?>" <?= ($filtre_note == $i) ? 'selected' : '' ?>><?= $i ?> étoiles</option>
            <?php endfor; ?>
        </select>

        <button type="submit">Appliquer</button>
    </form>


    <?php foreach ($avis as $a): ?>
        <div class="avis-card">
            <h4>
                <?php echo htmlspecialchars($a['citoyen_nom']); ?>
                <span style="font-weight: normal;">a évalué</span>
                <strong><?php echo htmlspecialchars($a['collecteur_nom']); ?></strong>
            </h4>
            <p class="note">
                <?php echo str_repeat('<i class="fas fa-star"></i>', $a['note']); ?>
                <?php echo str_repeat('<i class="far fa-star"></i>', 5 - $a['note']); ?>
            </p>
            <p><?php echo nl2br(htmlspecialchars($a['commentaire'])); ?></p>
            <p class="date"><i class="fas fa-clock"></i> Posté le <?php echo date('d/m/Y à H:i', strtotime($a['date_avis'])); ?></p>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>

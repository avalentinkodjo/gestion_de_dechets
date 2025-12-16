<?php
session_start();
require_once '../config/database.php'; // à adapter selon ton chemin

// Vérification rôle admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: auth.html');
    exit;
}

// Traitement changement de statut
if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'changer_statut') {
    $id = intval($_GET['id']);

    // Récupérer le statut actuel
    $stmt = $pdo->prepare("SELECT statut FROM collecteurs WHERE id = ?");
    $stmt->execute([$id]);
    $current = $stmt->fetchColumn();

    // Inverser le statut
    $newStatus = ($current === 'disponible') ? 'occupé' : 'disponible';

    $update = $pdo->prepare("UPDATE collecteurs SET statut = ? WHERE id = ?");
    $update->execute([$newStatus, $id]);

    header("Location: collecteurs.php");
    exit;
}

// Suppression
if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'supprimer') {
    $id = intval($_GET['id']);
    $del = $pdo->prepare("DELETE FROM collecteurs WHERE id = ?");
    $del->execute([$id]);

    header("Location: collecteurs.php");
    exit;
}

// Liste des collecteurs
$collecteurs = $pdo->query("
    SELECT c.*, u.nom AS nom_entreprise
    FROM collecteurs c
    JOIN utilisateurs u ON c.entreprise_id = u.id
    ORDER BY c.id DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Collecteurs</title>
    <link rel="stylesheet" href="css/admin_collecteur.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<header>
    <h1>Liste des Collecteurs</h1>
    <a href="dashboard_admin.php" class="btn-retour"><i class="fas fa-arrow-left"></i> Retour Dashboard</a>
</header>

<main class="conteneur-table">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Photo</th>
                <th>Nom</th>
                <th>Entreprise</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Ville</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($collecteurs as $c): ?>
            <tr>
                <td><?= $c['id'] ?></td>
                <td>
                    <?php if ($c['photo']): ?>
                        <img src="../uploads/<?= htmlspecialchars($c['photo']) ?>" alt="Photo" width="40">
                    <?php else: ?>
                        <i class="fas fa-user-circle fa-2x"></i>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($c['nom']) ?></td>
                <td><?= htmlspecialchars($c['nom_entreprise']) ?></td>
                <td><?= htmlspecialchars($c['email']) ?></td>
                <td><?= htmlspecialchars($c['telephone']) ?></td>
                <td><?= htmlspecialchars($c['ville']) ?></td>
                <td><?= $c['statut'] ?></td>
                <td>
                    <a href="?action=changer_statut&id=<?= $c['id'] ?>" class="btn-action">Changer Statut</a> <br> <br>
                    <a href="?action=supprimer&id=<?= $c['id'] ?>" class="btn-supprimer" onclick="return confirm('Supprimer ce collecteur ?')">🗑</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>

</body>
</html>

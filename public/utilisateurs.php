<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: auth.html');
    exit;
}

// Filtrage
$filtre_role = isset($_GET['role']) ? $_GET['role'] : '';

$sql = "SELECT * FROM utilisateurs";
if ($filtre_role !== '') {
    $sql .= " WHERE role = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$filtre_role]);
} else {
    $stmt = $pdo->query($sql);
}
$utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Utilisateurs - Admin</title>
    <link rel="stylesheet" href="css/utilisateur.css">
</head>
<body>

<header>
    <h1>Gestion des Utilisateurs</h1>
    <a href="dashboard_admin.php">← Retour au tableau de bord</a>
</header>

<div class="filter-section">
    <form method="GET" action="utilisateurs.php" autocomplete="off">
        <label for="role">Filtrer par rôle :</label>
        <select name="role" id="role">
            <option value="">Tous</option>
            <option value="citoyen" <?= $filtre_role === 'citoyen' ? 'selected' : '' ?>>Citoyen</option>
            <option value="collecteur" <?= $filtre_role === 'collecteur' ? 'selected' : '' ?>>Collecteur</option>
            <option value="entreprise" <?= $filtre_role === 'entreprise' ? 'selected' : '' ?>>Entreprise</option>
            <option value="gestionnaire" <?= $filtre_role === 'gestionnaire' ? 'selected' : '' ?>>Gestionnaire</option>
            <option value="admin" <?= $filtre_role === 'admin' ? 'selected' : '' ?>>Admin</option>
        </select>
        <button type="submit">Filtrer</button>
    </form>
</div>
<a href="ajouter_utilisateur.php" class="btn-ajouter">
    ➕ Ajouter un utilisateur
</a>

<table class="styled-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Date inscription</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($utilisateurs as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['id']) ?></td>
                <td><?= htmlspecialchars($user['nom']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= htmlspecialchars($user['role']) ?></td>
                <td><?= htmlspecialchars($user['date_inscription']) ?></td>
                <td>
                    <a href="modifier_utilisateur.php?id=<?= $user['id'] ?>">✏️ Modifier</a>
                    <a href="supprimer_utilisateur.php?id=<?= $user['id'] ?>" onclick="return confirm('Supprimer cet utilisateur ?')">🗑️ Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>

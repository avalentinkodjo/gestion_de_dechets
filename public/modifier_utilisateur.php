<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: auth.html');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: utilisateurs.php');
    exit;
}

$id = $_GET['id'];

// Récupération de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$id]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$utilisateur) {
    echo "Utilisateur introuvable.";
    exit;
}

// Traitement de la mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $update = $pdo->prepare("UPDATE utilisateurs SET nom = ?, email = ?, role = ? WHERE id = ?");
    $update->execute([$nom, $email, $role, $id]);

    header('Location: utilisateurs.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Utilisateur</title>
    <link rel="stylesheet" href="css/modifier_utilisateur.css">
</head>
<body>

<header>
    <h1>Modifier l'utilisateur #<?= htmlspecialchars($utilisateur['nom']) ?></h1>
    <a href="utilisateurs.php">← Retour</a>
</header>

<form method="POST" autocomplete="off">
    <label>Nom :</label>
    <input type="text" name="nom" value="<?= htmlspecialchars($utilisateur['nom']) ?>" required>

    <label>Email :</label>
    <input type="email" name="email" value="<?= htmlspecialchars($utilisateur['email']) ?>" required>

    <label>Rôle :</label>
    <select name="role" required>
        <?php
        $roles = ['citoyen', 'collecteur', 'entreprise', 'gestionnaire', 'admin'];
        foreach ($roles as $r) {
            $selected = $r === $utilisateur['role'] ? 'selected' : '';
            echo "<option value=\"$r\" $selected>$r</option>";
        }
        ?>
    </select>

    <button type="submit">Mettre à jour</button>
</form>

</body>
</html>

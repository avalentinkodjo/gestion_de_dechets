<?php
session_start();
require_once('../config/database.php');

// Vérifie que l'utilisateur est un administrateur
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: auth.html');
    exit;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Vérifie si l'email existe déjà
    $check = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $check->execute([$email]);
    if ($check->rowCount() > 0) {
        $erreur = "Cet email est déjà utilisé.";
    } else {
        // Insertion du nouvel utilisateur
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$nom, $email, $mot_de_passe, $role])) {
            header('Location: utilisateurs.php?ajout=1');
            exit;
        } else {
            $erreur = "Erreur lors de l'ajout.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un utilisateur</title>
    <link rel="stylesheet" href="css/ajouter_utilisateur.css">
</head>
<body>
    <div class="admin-container">
        <h2>➕ Ajouter un Utilisateur</h2>
        <?php if (isset($erreur)): ?>
            <p class="error"><?php echo $erreur; ?></p>
        <?php endif; ?>
        <form method="POST" class="admin-form" autocomplete="off">
            <label>Nom complet :</label>
            <input type="text" name="nom" required>

            <label>Email :</label>
            <input type="email" name="email" required>

            <label>Mot de passe :</label>
            <input type="password" name="mot_de_passe" required>

            <label>Rôle :</label>
            <select name="role" required>
                <option value="">-- Rôle --</option>
                <option value="citoyen">Citoyen</option>
                <option value="collecteur">Collecteur</option>
                <option value="entreprise">Entreprise</option>
                <option value="gestionnaire">Gestionnaire</option>
                <option value="admin">Admin</option>
            </select>

            <button type="submit">Ajouter</button>
            <a href="utilisateurs.php" class="btn-retour">← Retour</a>
        </form>
    </div>
</body>
</html>

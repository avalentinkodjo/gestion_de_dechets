<?php
include '../config/database.php';


// Inscription
if (isset($_POST['inscription'])) {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        header("Location: ../public/auth.html?error=Email déjà utilisé.");
        exit;
    } else {
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$nom, $email, $mot_de_passe, $role])) {
            header("Location: ../public/auth.html?success=Inscription réussie.");
            exit;
        } else {
            header("Location: ../public/auth.html?error=Erreur lors de l'inscription.");
            exit;
        }
    }
}

// Connexion
if (isset($_POST['connexion'])) {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
        session_start();
        $_SESSION['id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['nom'] = $user['nom'];

        if ($user['role'] == 'citoyen') {
            header("Location: ../public/dashboard_citoyen.php");
        } elseif ($user['role'] == 'entreprise') {
            header("Location: ../public/dashboard_entreprise.php");
        } elseif ($user['role'] == 'collecteur') {
            header("Location: ../public/dashboard_collecteur.php");
        } elseif ($user['role'] == 'gestionnaire') {
            header("Location: ../public/dashboard_gestionnaire.php");
        } elseif ($user['role'] == 'admin') {
            header("Location: ../public/dashboard_admin.php");
        }
    } else {
        header("Location: ../public/auth.html?error=Email ou mot de passe incorrect.");
        exit;
    }
}

?>
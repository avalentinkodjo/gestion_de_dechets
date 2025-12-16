<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'collecteur') {
    header("Location: login.php");
    exit();
}

$utilisateur_id = $_SESSION['id'];

$stmt = $pdo->prepare("SELECT * FROM profil_collecteurs WHERE user_id = ?");
$stmt->execute([$utilisateur_id]);
$collecteur = $stmt->fetch();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $collecteur ? 'Modifier Profil' : 'Créer Profil'; ?></title>
    <link rel="stylesheet" href="css/profil_collecteurss.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
<header>
    <a href="dashboard_collecteur.php" class="btn-back"><i class="fas fa-arrow-left"></i> Retour</a>
</header>

<main class="container">
    <section class="card">
        <h2><i class="fas fa-recycle"></i> <?= $collecteur ? 'Modifier votre profil' : 'Créer votre profil'; ?></h2>
        
        <form id="profilForm" method="POST" enctype="multipart/form-data" autocomplete="off">
            <input type="hidden" name="id" value="<?= $collecteur['id'] ?? ''; ?>">

            <div class="profile-images">
                <div class="image-preview">
                    <img src="../uploads/<?= !empty($collecteur['photo']) ? $collecteur['photo'] : 'default.png'; ?>" id="previewImg">
                    <label for="photo"><i class="fas fa-camera"></i> Photo de profil</label>
                    <input type="file" name="photo" id="photo" onchange="previewFile()" required>
                </div>

                <div class="image-previe">
                    <img src="../uploads/<?= !empty($collecteur['carte_identite']) ? $collecteur['carte_identite'] : 'default_id.png'; ?>" id="previewCarte" width="200">
                    <label for="carte_identite"><i class="fas fa-id-card"></i> Carte d'identité</label>
                    <input type="file" name="carte_identite" id="carte_identite" onchange="previewCarteFile()" required>
                </div>
            </div>

            <div class="input-group">
                <label for="nom"><i class="fas fa-user"></i> Nom</label>
                <input type="text" name="nom" id="nom" value="<?= $collecteur['nom'] ?? ''; ?>" required>
            </div>

            <div class="input-group">
                <label for="telephone"><i class="fas fa-phone"></i> Téléphone</label>
                <input type="text" name="telephone" id="telephone" value="<?= $collecteur['telephone'] ?? ''; ?>" required>
            </div>

            <div class="input-group">
                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                <input type="email" name="email" id="email" value="<?= $collecteur['email'] ?? ''; ?>" required>
            </div>

            <div class="input-group">
                <label for="adresse"><i class="fas fa-location-dot"></i> Adresse</label>
                <input type="text" name="adresse" id="adresse" value="<?= $collecteur['adresse'] ?? ''; ?>" required>
            </div>

            <div class="input-group">
                <label for="ville"><i class="fas fa-city"></i> Ville</label>
                <input type="text" name="ville" id="ville" value="<?= $collecteur['ville'] ?? ''; ?>" required>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> <?= $collecteur ? 'Mettre à jour' : 'Créer Profil'; ?>
            </button>
        </form>

        <?php if ($collecteur): ?>
            <button type="button" id="btnSupprimerProfil" class="btn-delete" data-id="<?= $collecteur['id']; ?>">
                <i class="fas fa-trash"></i> Supprimer mon profil
            </button>

        <?php endif; ?>
    </section>
</main>

<!-- JS INCHANGÉ (prévisualisation + fetch) -->
<script>
function previewFile() {
    const preview = document.getElementById("previewImg");
    const file = document.getElementById("photo").files[0];
    const reader = new FileReader();
    reader.onloadend = () => preview.src = reader.result;
    if (file) reader.readAsDataURL(file);
}

function previewCarteFile() {
    const preview = document.getElementById("previewCarte");
    const file = document.getElementById("carte_identite").files[0];
    const reader = new FileReader();
    reader.onloadend = () => preview.src = reader.result;
    if (file) reader.readAsDataURL(file);
}

document.getElementById("profilForm").addEventListener("submit", function(event) {
    event.preventDefault();
    const formData = new FormData(this);
    fetch("profil_action.php", {
        method: "POST",
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert(data.success);
            location.reload();
        } else {
            alert(data.error);
        }
    }).catch(() => alert("Erreur lors de l'envoi."));
});
</script>

<script>
document.getElementById("btnSupprimerProfil")?.addEventListener("click", function () {
    if (!confirm("Voulez-vous vraiment supprimer votre profil ? Cette action est irréversible.")) return;

    const id = this.dataset.id;

    fetch("supprimer_profil.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "id=" + encodeURIComponent(id)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert(data.success);
            window.location.href = "dashboard_collecteur.php"; // ou une redirection vers la page d'accueil
        } else {
            alert("Erreur : " + data.error || "Suppression échouée.");
        }
    })
    .catch(() => alert("Une erreur réseau s'est produite."));
});
</script>

</body>
</html>
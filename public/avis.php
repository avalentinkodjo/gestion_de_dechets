<?php
session_start();
include '../config/database.php';

// Vérifier si l'utilisateur est connecté et est un citoyen
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'citoyen') {
    header("Location: auth.html");
    exit();
}

$citoyen_id = $_SESSION['id'];
$demande_id = $_GET['demande_id'] ?? null;

// Vérifier que la demande existe et est terminée
$stmt = $pdo->prepare("SELECT * FROM demandes WHERE id = ? AND statut = 'validée' AND client_id = ?");
$stmt->execute([$demande_id, $citoyen_id]);
$demande = $stmt->fetch();

if (!$demande) {
    die("<div class='error'>Demande invalide ou déjà notée.</div>");
}

// Récupérer les informations du collecteur
$stmt = $pdo->prepare("SELECT c.id, c.nom FROM collecteurs c JOIN demandes d ON c.id = d.collecteur_id WHERE d.id = ?");
$stmt->execute([$demande_id]);
$collecteur = $stmt->fetch();

if (!$collecteur) {
    die("<div class='error'>Collecteur introuvable.</div>");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noter le collecteur</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../public/css/avis.css">
</head>
<body>
    <div class="container">
        <button class="btn-back" onclick="window.history.back()"><i class="fas fa-arrow-left"></i> Retour</button>
        
        <h2><i class="fas fa-star"></i> Noter le collecteur : <?php echo $collecteur['nom']; ?></h2>
        
        <form id="form-avis" method="POST" autocomplete="off">
            <input type="hidden" name="demande_id" value="<?php echo $demande_id; ?>">
            <input type="hidden" name="collecteur_id" value="<?php echo $collecteur['id']; ?>">
            <input type="hidden" name="citoyen_id" value="<?php echo $citoyen_id; ?>">

            <div class="rating-section">
                <label for="note"><i class="fas fa-star"></i> Note (1 à 5) :</label>
                <select name="note" required>
                    <option value="1">⭐</option>
                    <option value="2">⭐⭐</option>
                    <option value="3">⭐⭐⭐</option>
                    <option value="4">⭐⭐⭐⭐</option>
                    <option value="5">⭐⭐⭐⭐⭐</option>
                </select>
            </div>

            <div class="comment-section">
                <label for="commentaire"><i class="fas fa-comment"></i> Commentaire :</label>
                <textarea name="commentaire" required placeholder="Partagez vos commentaires..."></textarea>
            </div>

            <button type="submit" class="btn-submit"><i class="fas fa-paper-plane"></i> Envoyer</button>
        </form>
    </div>

    <!-- JavaScript AJAX -->
    <script>
        document.getElementById('form-avis').addEventListener('submit', function(e) {
            e.preventDefault();  // Empêcher la soumission classique du formulaire

            var formData = new FormData(this);  // Récupérer les données du formulaire

            // Envoi de la requête AJAX
            fetch('traiter_avis.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);  // Affichage de l'alerte avec le message
                if (data.redirect) {
                    window.location.href = data.redirect;  // Redirection si nécessaire
                }
            })
            .catch(error => {
                alert("Une erreur est survenue. Veuillez réessayer.");
                console.error(error);
            });
        });
    </script>
</body>
</html>
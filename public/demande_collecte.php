<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande de Collecte</title>
    <link rel="stylesheet" href="css/demandes_collectes.css">
</head>
<body>

    <header>
        <nav class="navbar">
            <h1>Gestion des Déchets - Togo</h1>
            <div>
                <a href="dashboard_citoyen.php" class="btn">Accueil</a>
            </div>
        </nav>
    </header>

    <section class="hero">
        <div class="container">
            <h2>Demander une collecte</h2>
            <p>Faites un geste pour l'environnement. Demandez une collecte de déchets à domicile !</p>
        </div>
    </section>

    <section class="form-section">
        <div class="container">
            <h3>Votre Demande</h3>
            <form id="collecteForm" enctype="multipart/form-data" class="collecte-form" autocomplete="off">
                <div class="form-group">
                    <input id="loca" type="text" name="localisation" placeholder="Votre adresse ou utilisez la localisation" required>
                    <button id="position" type="button" onclick="obtenirPosition()" class="btn-location">Localisation 📍</button>
                    <div id="position-text"></div>
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">
                </div>

                <div class="form-group">
                    <select name="type_dechet" required>
                        <option value="">-- Type de déchets --</option>
                        <option value="plastique">Plastique</option>
                        <option value="metal">Métal</option>
                        <option value="organique">Organique</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>

                <div class="form-group">
                    <input type="datetime-local" name="date_heure" required>
                </div>

                <div class="form-group">
                    <input type="file" name="photo_dechet">
                </div>

                <div class="form-group">
                    <button type="button" onclick="envoyerDemande()" class="btn-submit">Soumettre la demande</button>
                </div>
            </form>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2025 Gestion des Déchets - Togo | Tous droits réservés</p>
        </div>
    </footer>

    <script>
        function obtenirPosition() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;

                    document.getElementById('latitude').value = latitude;
                    document.getElementById('longitude').value = longitude;
                    document.getElementById('position-text').innerText = `Votre position : Latitude ${latitude}, Longitude ${longitude}`;
                }, function(error) {
                    alert('Erreur lors de la récupération de la position : ' + error.message);
                });
            } else {
                alert('La géolocalisation n\'est pas supportée par votre navigateur.');
            }
        }

        function envoyerDemande() {
            const form = document.getElementById('collecteForm');
            const formData = new FormData(form);
            formData.append('demander_collecte', '1'); // Simuler le bouton submit

            fetch('../controllers/DemandeController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.success);
                    form.reset(); // Optionnel : réinitialiser le formulaire
                    document.getElementById('position-text').innerText = '';
                    window.location.href = "dashboard_citoyen.php";
                } else if (data.error) {
                    alert("Erreur : " + data.error);
                }
            })
            .catch(error => {
                alert("Une erreur s’est produite : " + error.message);
            });
        }
    </script>

</body>
</html>

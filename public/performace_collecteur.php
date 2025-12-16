<?php
include '../config/database.php';

// Récupérer les collecteurs et leurs moyennes de notes
$stmt = $pdo->prepare("SELECT c.id AS collecteur_id, c.nom AS collecteur_nom, 
                              AVG(a.note) AS moyenne_note 
                       FROM collecteurs c
                       LEFT JOIN avis a ON c.id = a.collecteur_id
                       GROUP BY c.id");
$stmt->execute();
$collecteurs_performance = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Préparer les données pour le graphique
$labels = [];
$data = [];

foreach ($collecteurs_performance as $collecteur) {
    $labels[] = $collecteur['collecteur_nom']; // Noms des collecteurs
    $data[] = $collecteur['moyenne_note'];    // Moyennes des notes
}

// Attribuer les badges aux 3 meilleurs collecteurs
$badges = ['🥇', '🥈', '🥉'];
$top_collecteurs = [];

for ($i = 0; $i < min(3, count($collecteurs_performance)); $i++) {
    // Vérifier si la moyenne des notes est NULL, et la remplacer par 0 si c'est le cas
    $moyenne_note = $collecteurs_performance[$i]['moyenne_note'] ?? 0;

    // Ajouter les données des collecteurs avec les notes arrondies
    $top_collecteurs[] = [
        'nom' => $collecteurs_performance[$i]['collecteur_nom'],
        'note' => round($moyenne_note, 2), // Appliquer round() après vérification
        'badge' => $badges[$i]
    ];
}

$top_collecteurs_json = json_encode($top_collecteurs);

// Convertir en JSON pour l'utiliser dans Chart.js
$labels_json = json_encode($labels);
$data_json = json_encode($data);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Performance des Collecteurs</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- CSS Moderne et Écologique -->
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #e3fce9, #f0fff7);
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
            min-height: 100vh;
        }

        .container {
            background: #ffffff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 128, 0, 0.15);
            width: 90%;
            max-width: 1000px;
            position: relative;
        }

        h2 {
            text-align: center;
            color: #2e7d32;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .icon-header {
            text-align: center;
            margin-bottom: 10px;
        }

        .icon-header i {
            font-size: 40px;
            color: #43a047;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .chart-container {
            position: relative;
            width: 100%;
            height: 400px;
            margin-top: 30px;
        }

        .footer {
            text-align: center;
            font-size: 13px;
            color: #888;
            margin-top: 40px;
        }

        .badge {
            display: inline-block;
            background: #c8e6c9;
            color: #2e7d32;
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 13px;
            margin-left: 8px;
        }

        .top-collecteurs {
    margin-top: 30px;
    text-align: center;
    background: #e8f5e9;
    padding: 15px;
    border-radius: 10px;
    border-left: 5px solid #4caf50;
}

.top-collecteurs h3 {
    font-size: 20px;
    color: #2e7d32;
    margin-bottom: 10px;
}

#topCollecteursList {
    list-style: none;
    padding: 0;
    font-size: 16px;
}

#topCollecteursList li {
    margin: 8px 0;
    font-weight: bold;
}

/* Conteneur du bouton de retour */
.back-button-container {
    text-align: center;
    margin-top: 20px;
    position: relative;
    bottom: 30px;
}

/* Style du bouton de retour */
.back-button {
    background-color: #4CAF50; /* Couleur de fond verte */
    color: white;               /* Couleur du texte */
    border: none;               /* Pas de bordure */
    padding: 10px 20px;         /* Espacement intérieur */
    font-size: 16px;            /* Taille de police */
    border-radius: 5px;         /* Coins arrondis */
    cursor: pointer;           /* Curseur en forme de main */
    transition: background-color 0.3s ease; /* Effet de transition */
}

/* Effet de survol */
.back-button:hover {
    background-color: #45a049; /* Couleur de fond plus sombre au survol */
}

    </style>
</head>
<body>
    <!-- Bouton de retour -->
    <div class="back-button-container">
        <button class="back-button" onclick="window.history.back()">🔙 Retour</button>
    </div>

    <div class="container">
        <div class="icon-header">
            <i class="fas fa-recycle"></i>
        </div>
        <h2><i class="fas fa-star"></i> Performance des Collecteurs <span class="badge">TogoEthic</span></h2>
        <div class="top-collecteurs">
                <h3>🏆 Top Collecteurs</h3>
                <ul id="topCollecteursList"></ul>
        </div>
        <div class="chart-container">
            <canvas id="performanceChart"></canvas>
        </div>

    </div>

    <div class="footer">
        &copy; 2025 Plateforme TogoEthic 🌍
    </div>

    <script>

        const topCollecteurs = <?php echo $top_collecteurs_json; ?>;

        const topList = document.getElementById('topCollecteursList');
        topCollecteurs.forEach(c => {
            const li = document.createElement('li');
            li.textContent = `${c.badge} ${c.nom} - ${c.note}/5`;
            topList.appendChild(li);
        });


        const labels = <?= $labels_json; ?>;
        const data = <?= $data_json; ?>;

        const ctx = document.getElementById('performanceChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Moyenne des avis (sur 5)',
                    data: data,
                    backgroundColor: 'rgba(76, 175, 80, 0.4)',
                    borderColor: 'rgba(56, 142, 60, 1)',
                    borderWidth: 2,
                    hoverBackgroundColor: 'rgba(56, 142, 60, 0.8)',
                    hoverBorderColor: 'rgba(46, 125, 50, 1)',
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                animation: {
                    duration: 1200,
                    easing: 'easeOutElastic'
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Note : ${context.raw} ⭐`;
                            }
                        },
                        backgroundColor: '#4caf50',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderWidth: 1,
                        borderColor: '#2e7d32',
                        padding: 12,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 5,
                        ticks: {
                            stepSize: 1,
                            callback: (val) => val + ' ★'
                        },
                        grid: {
                            color: '#e0f2f1'
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 30,
                            minRotation: 0
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>

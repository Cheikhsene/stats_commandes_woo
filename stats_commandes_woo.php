<?php
// Configuration
define('WOOCOMMERCE_STORE_URL', 'https://votre-site-woocommerce.com/');
define('WOOCOMMERCE_CONSUMER_KEY', 'votre_cle_consommateur');
define('WOOCOMMERCE_CONSUMER_SECRET', 'votre_cle_secrete');

// Fonction pour récupérer toutes les commandes WooCommerce
function obtenirToutesLesCommandesWooCommerce() {
    $page = 1;
    $per_page = 100;
    $toutes_les_commandes = [];

    while (true) {
        $url = WOOCOMMERCE_STORE_URL . "wp-json/wc/v3/orders?page=$page&per_page=$per_page";
        $auth = base64_encode(WOOCOMMERCE_CONSUMER_KEY . ':' . WOOCOMMERCE_CONSUMER_SECRET);

        $reponse = file_get_contents($url, false, stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => "Authorization: Basic " . $auth . "\r\n"
            ]
        ]));

        if ($reponse === FALSE) {
            break;
        }

        $commandes = json_decode($reponse);

        if (empty($commandes)) {
            break;
        }

        $toutes_les_commandes = array_merge($toutes_les_commandes, $commandes);
        $page++;
    }

    return $toutes_les_commandes;
}

// Fonction pour calculer les statistiques
function calculerStatistiques($commandes) {
    $stats = [
        'total' => count($commandes),
        'termine' => ['count' => 0, 'montant' => 0],
        'annule' => ['count' => 0, 'montant' => 0],
        'autres' => ['count' => 0, 'montant' => 0],
    ];

    foreach ($commandes as $commande) {
        $montant = floatval($commande->total);
        switch ($commande->status) {
            case 'completed':
                $stats['termine']['count']++;
                $stats['termine']['montant'] += $montant;
                break;
            case 'cancelled':
                $stats['annule']['count']++;
                $stats['annule']['montant'] += $montant;
                break;
            default:
                $stats['autres']['count']++;
                $stats['autres']['montant'] += $montant;
        }
    }

    $stats['total_termine_annule'] = [
        'count' => $stats['termine']['count'] + $stats['annule']['count'],
        'montant' => $stats['termine']['montant'] + $stats['annule']['montant']
    ];

    return $stats;
}

$commandes = obtenirToutesLesCommandesWooCommerce();
$stats = calculerStatistiques($commandes);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques des Commandes WooCommerce</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .content {
            flex: 1 0 auto;
        }
        .footer {
            flex-shrink: 0;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="content container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6">Statistiques des Commandes WooCommerce</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white p-4 rounded-lg shadow">
                <i class="fas fa-shopping-cart text-3xl text-blue-500 mb-2"></i>
                <h2 class="text-xl font-semibold">Total des Commandes</h2>
                <p class="text-2xl"><?php echo $stats['total']; ?></p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow">
                <i class="fas fa-check-circle text-3xl text-green-500 mb-2"></i>
                <h2 class="text-xl font-semibold">Commandes Terminées</h2>
                <p class="text-2xl"><?php echo $stats['termine']['count']; ?></p>
                <p class="text-lg"><?php echo number_format($stats['termine']['montant'], 2); ?> CFA</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow">
                <i class="fas fa-times-circle text-3xl text-red-500 mb-2"></i>
                <h2 class="text-xl font-semibold">Commandes Annulées</h2>
                <p class="text-2xl"><?php echo $stats['annule']['count']; ?></p>
                <p class="text-lg"><?php echo number_format($stats['annule']['montant'], 2); ?> CFA</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow">
                <i class="fas fa-calculator text-3xl text-purple-500 mb-2"></i>
                <h2 class="text-xl font-semibold">Total Terminé + Annulé</h2>
                <p class="text-2xl"><?php echo $stats['total_termine_annule']['count']; ?></p>
                <p class="text-lg"><?php echo number_format($stats['total_termine_annule']['montant'], 2); ?> CFA</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white p-4 rounded-lg shadow">
                <canvas id="statusChart"></canvas>
            </div>
            <div class="bg-white p-4 rounded-lg shadow">
                <canvas id="montantChart"></canvas>
            </div>
        </div>
    </div>

    <footer class="footer bg-gray-800 text-white py-4 text-center">
        <p>Créé avec ❤️ par <a href="https://cheikhsene.github.io" class="text-blue-400 hover:text-blue-300">CheikhSene</a></p>
    </footer>

    <script>
        // Graphique des statuts
        new Chart(document.getElementById('statusChart'), {
            type: 'pie',
            data: {
                labels: ['Terminé', 'Annulé', 'Autres'],
                datasets: [{
                    data: [
                        <?php echo $stats['termine']['count']; ?>,
                        <?php echo $stats['annule']['count']; ?>,
                        <?php echo $stats['autres']['count']; ?>
                    ],
                    backgroundColor: ['#10B981', '#EF4444', '#6B7280']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: {
                        display: true,
                        text: 'Répartition des Statuts de Commande'
                    }
                }
            }
        });

        // Graphique des montants
        new Chart(document.getElementById('montantChart'), {
            type: 'bar',
            data: {
                labels: ['Terminé', 'Annulé', 'Autres'],
                datasets: [{
                    label: 'Montant en CFA',
                    data: [
                        <?php echo $stats['termine']['montant']; ?>,
                        <?php echo $stats['annule']['montant']; ?>,
                        <?php echo $stats['autres']['montant']; ?>
                    ],
                    backgroundColor: ['#10B981', '#EF4444', '#6B7280']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Montant Total par Statut de Commande'
                    }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
</body>
</html>

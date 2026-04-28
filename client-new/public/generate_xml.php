<?php
require_once '../src/Core/auth.php';
requireRole('gestionnaire');
require_once '../src/Core/utils.php';

$clientRepo = new ClientRepository();
$clients = $clientRepo->getAll();

$xmlOutput = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clientId = intval($_POST['numero_client'] ?? 0);
    if ($clientId <= 0) {
        $error = 'Veuillez sélectionner un client valide.';
    } else {
        $xmlOutput = generateClientEquipmentXML($clientId);
        if ($xmlOutput === '') {
            $error = "Aucun équipement ou client trouvé pour le numéro $clientId.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Générateur XML - CashCash</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="dashboard-page">
    <?php include '../src/Views/components/header.php'; ?>
    
    <div class="dashboard-content">
        <div class="container">
            <div class="dashboard-title">
                <h2>Générateur XML</h2>
                <p class="dashboard-subtitle">Générer un fichier XML des équipements par client</p>
            </div>
            
            <?php include '../src/Views/components/nav_gestionnaire.php'; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error" style="margin-top: 1rem;">
                    <strong>⚠️ Erreur:</strong> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if ($xmlOutput): ?>
                <div class="card" style="margin-top: 1rem; padding: 2rem;">
                    <h3>Résultat XML pour le client N°<?= $clientId ?></h3>
                    <pre style="background:#f0f0f0;padding:1rem;overflow:auto;border-radius:var(--border-radius);max-height:400px;"><?= htmlspecialchars($xmlOutput) ?></pre>
                    <form method="post" action="generate_xml.php?download=1" style="margin-top: 1rem;">
                        <input type="hidden" name="numero_client" value="<?= $clientId ?>">
                        <button type="submit" class="btn btn-secondary">⬇️ Télécharger le XML</button>
                        <a href="generate_xml.php" class="btn btn-outline" style="margin-left: 1rem;">Retour à la liste</a>
                    </form>
                </div>
            <?php else: ?>
                <div class="table-container" style="margin-top: 1rem;">
                    <div class="table-header">
                        <h3 class="table-title">Sélectionner un client</h3>
                        <div class="table-actions" style="width: 300px;">
                            <input type="text" id="clientSearch" class="form-control" placeholder="🔍 Rechercher (Nom, N°...)" onkeyup="filterClients()">
                        </div>
                    </div>
                    
                    <div class="table-wrapper">
                        <table id="clientsTable">
                            <thead>
                                <tr>
                                    <th>N° Client</th>
                                    <th>Raison Sociale</th>
                                    <th>Siren</th>
                                    <th>Code APE</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($clients as $client): ?>
                                    <tr>
                                        <td><strong><?= $client['Numero_Client'] ?></strong></td>
                                        <td><?= htmlspecialchars($client['Raison_Sociale']) ?></td>
                                        <td><?= htmlspecialchars($client['Siren'] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($client['Code_Ape'] ?? 'N/A') ?></td>
                                        <td>
                                            <form method="post" action="generate_xml.php" style="display:inline;">
                                                <input type="hidden" name="numero_client" value="<?= $client['Numero_Client'] ?>">
                                                <button type="submit" class="btn btn-sm btn-primary">📄 Générer XML</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <script>
                function filterClients() {
                    var input, filter, table, tr, td, i, txtValue, match;
                    input = document.getElementById("clientSearch");
                    filter = input.value.toUpperCase();
                    table = document.getElementById("clientsTable");
                    tr = table.getElementsByTagName("tr");
                    
                    for (i = 1; i < tr.length; i++) {
                        match = false;
                        td = tr[i].getElementsByTagName("td");
                        for (var j = 0; j < td.length - 1; j++) {
                            if (td[j]) {
                                txtValue = td[j].textContent || td[j].innerText;
                                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                    match = true;
                                    break;
                                }
                            }
                        }
                        tr[i].style.display = match ? "" : "none";
                    }
                }
                </script>
            <?php endif; ?>
        </div>
    </div>
<?php
if (isset($_GET['download']) && $xmlOutput) {
    header('Content-Type: application/xml');
    header('Content-Disposition: attachment; filename="client_' . $clientId . '.xml"');
    echo $xmlOutput;
    exit;
}
?>
</body>
</html>
